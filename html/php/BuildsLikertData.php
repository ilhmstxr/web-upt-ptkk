<?php

namespace App\Filament\Resources\JawabanSurveiResource\Widgets;

use App\Models\Kompetensi;
use App\Models\Instruktur;
use App\Models\JawabanUser;
use App\Models\OpsiJawaban;
use App\Models\Pelatihan;
use App\Models\Pertanyaan;
use App\Models\Peserta;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

trait BuildsLikertData
{
    /** Ambil pertanyaan_id berbasis pelatihan → tes(survei) → pertanyaan(skala_likert). */
    // protected function collectPertanyaanIds(?int $pelatihanId, ?string $tipe = 'survei'): Collection
    protected function collectPertanyaanIds(?int $pelatihanId, ?string $tipe = 'survei'): Collection
    {
        $localTipe = $tipe ?? 'survei';

        return JawabanUser::query()
            ->from('jawaban_user as ju')
            ->join('percobaan as pr', 'pr.id', '=', 'ju.percobaan_id')
            ->join('tes as t', 't.id', '=', 'pr.tes_id')
            ->join('pertanyaan as p', 'p.id', '=', 'ju.pertanyaan_id')
            ->where('t.tipe', $localTipe)
            // --- LOGIKA KONDISIONAL: Filter skala_likert HANYA jika 'survei' ---
            ->when($localTipe === 'survei', function ($query) {
                return $query->where('p.tipe_jawaban', 'skala_likert');
            })
            ->when($pelatihanId, fn($q) => $q->where('t.pelatihan_id', $pelatihanId))
            ->distinct()
            ->pluck('ju.pertanyaan_id')
            ->values();
    }

    /** Normalisasi input apa pun menjadi daftar integer pertanyaan_id unik. */
    protected function normalizePertanyaanIds(mixed $input): Collection
    {
        return collect($input)
            ->flatten(6)
            ->map(function ($v) {
                if ($v instanceof Pertanyaan) return $v->id;
                if (is_array($v) && array_key_exists('id', $v)) return $v['id'];
                return $v;
            })
            ->filter(fn($v) => is_numeric($v))
            ->map(fn($v) => (int) $v)
            ->unique()
            ->values();
    }

    /** Bangun peta pivot & opsi → skala. */
    protected function buildLikertMaps($pertanyaanIds): array
    {
        $ids = $this->normalizePertanyaanIds($pertanyaanIds);
        if ($ids->isEmpty()) {
            return [collect(), collect(), collect()];
        }

        $pivot = DB::table('pivot_jawaban')
            ->whereIn('pertanyaan_id', $ids->all())
            ->pluck('template_pertanyaan_id', 'pertanyaan_id');

        $opsi = OpsiJawaban::whereIn(
            'pertanyaan_id',
            $ids->merge($pivot->values())->unique()->all()
        )->orderBy('id')->get(['id', 'pertanyaan_id', 'teks_opsi']);

        $opsiIdToSkala = $opsi->groupBy('pertanyaan_id')->map(function ($rows) {
            $map = [];
            foreach ($rows->pluck('id')->values() as $i => $id) {
                $map[$id] = $i + 1; // urutan opsi → skala 1..n
            }
            return $map;
        });

        $opsiTextToId = $opsi->groupBy('pertanyaan_id')
            ->map(fn($rows) => $rows->pluck('id', 'teks_opsi')->mapWithKeys(
                fn($id, $teks) => [trim($teks) => $id]
            ));

        return [$pivot, $opsiIdToSkala, $opsiTextToId];
    }

    /** Normalisasi jawaban menjadi {pertanyaan_id, opsi_jawaban_id, skala}. */
    protected function normalizedAnswers(
        ?int $pelatihanId,
        $pertanyaanIds,
        $pivot,
        $opsiIdToSkala,
        $opsiTextToId
    ): Collection {
        $ids = $this->normalizePertanyaanIds($pertanyaanIds);
        if ($ids->isEmpty()) return collect();

        $jawaban = JawabanUser::query()
            ->join('percobaan as pr', 'pr.id', '=', 'jawaban_user.percobaan_id')
            ->join('tes as t', 't.id', '=', 'pr.tes_id')
            ->whereIn('jawaban_user.pertanyaan_id', $ids->all())
            ->when($pelatihanId, fn($q) => $q->where('t.pelatihan_id', $pelatihanId))
            ->select([
                'jawaban_user.pertanyaan_id',
                'jawaban_user.opsi_jawaban_id',
                'jawaban_user.jawaban_teks',
            ])
            ->get();

        return $jawaban->map(function ($j) use ($pivot, $opsiIdToSkala, $opsiTextToId) {
            $pid = (int) $j->pertanyaan_id;
            $source = $opsiIdToSkala->get($pid) ? $pid : ($pivot[$pid] ?? $pid);

            $opsiId = $j->opsi_jawaban_id;
            if (!$opsiId && $j->jawaban_teks) {
                $opsiId = optional($opsiTextToId->get($source))->get(trim((string) $j->jawaban_teks));
            }

            $skalaMap = $opsiIdToSkala->get($source, []);
            $skala = $opsiId ? ($skalaMap[$opsiId] ?? null) : null;
            if ($skala !== null) {
                $maxScale = count($skalaMap);
                $skala = $maxScale > 0 ? max(1, min($maxScale, (int) $skala)) : (int) $skala;
            }

            return [
                'pertanyaan_id'   => $pid,
                'opsi_jawaban_id' => $opsiId ? (int) $opsiId : null,
                'skala'           => $skala,
            ];
        });
    }

    // =====================================================================
    // FUNGSI-FUNGSI CHART UNTUK DIPANGGIL OLEH ExportController
    // =====================================================================

    /**
     * Bar/stacked: distribusi skala per kategori pertanyaan.
     * Dataset label digabung dengan persentase kumulatif antar semua kategori.
     * Asumsi kolom kategori tersedia pada tabel `pertanyaan` (ubah jika berbeda).
     */
    public function buildPerKategori(?int $pelatihanId, array $range = [], ?string $tipe = 'survei'): array
    {
        $localTipe = $tipe ?? 'survei';

        // --- INI ADALAH LOGIKA BARU YANG ANDA MINTA ---
        // Jika tipenya BUKAN 'survei', maka chart "Per Kategori" ini tidak relevan
        // karena Pre-test/Post-test tidak memiliki kategori kompleks.
        // Kita kembalikan data kosong agar chart tidak ditampilkan.
        if ($localTipe !== 'survei') {
            return ['labels' => [], 'datasets' => []];
        }
        // --- AKHIR LOGIKA BARU ---

        // Jika kita sampai di sini, berarti $localTipe === 'survei'.
        // Kita jalankan semua logika kompleks khusus untuk survei.

        // 1. Ambil data dasar (ini sudah dinamis)
        $ids = $this->collectPertanyaanIds($pelatihanId, $localTipe);
        if ($ids->isEmpty()) {
            return ['labels' => [], 'datasets' => []];
        }

        [$pivot, $opsiIdToSkala, $opsiTextToId] = $this->buildLikertMaps($ids);
        $answers = $this->normalizedAnswers($pelatihanId, $ids, $pivot, $opsiIdToSkala, $opsiTextToId);

        // 2. Panggil helper untuk memetakan kategori berdasarkan tipe
        // (Ini adalah bagian yang kita refactor, dan sekarang HANYA berjalan untuk survei)
        $mapKategori = $this->buildKategoriMap($pelatihanId, $localTipe);

        // 3. Hitung jawaban per kategori untuk setiap skala (Logika ini umum)
        $countsMatrix = [];
        foreach ($answers as $a) {
            $pertanyaanId = $a['pertanyaan_id'];
            if (!isset($mapKategori[$pertanyaanId]) || $a['skala'] === null) {
                continue;
            }

            $cat = $mapKategori[$pertanyaanId];
            // Inisialisasi array untuk 4 skala
            $countsMatrix[$cat] = $countsMatrix[$cat] ?? [1 => 0, 2 => 0, 3 => 0, 4 => 0];
            $sk = (int) $a['skala'];
            if ($sk >= 1 && $sk <= 4) {
                $countsMatrix[$cat][$sk]++;
            }
        }

        if (empty($countsMatrix)) {
            return ['labels' => [], 'datasets' => []];
        }

        // 4. Susun label kategori (sumbu X)
        $labels = array_keys($countsMatrix);

        // 5. Siapkan data untuk setiap dataset (Logika ini umum)
        $C1 = [];
        $C2 = [];
        $C3 = [];
        $C4 = [];
        foreach ($labels as $cat) {
            $C1[] = $countsMatrix[$cat][1] ?? 0;
            $C2[] = $countsMatrix[$cat][2] ?? 0;
            $C3[] = $countsMatrix[$cat][3] ?? 0;
            $C4[] = $countsMatrix[$cat][4] ?? 0;
        }

        // 6. Hitung persentase total untuk setiap skala (Logika ini umum)
        $t1 = array_sum($C1);
        $t2 = array_sum($C2);
        $t3 = array_sum($C3);
        $t4 = array_sum($C4);
        $grandTotal = max(1, $t1 + $t2 + $t3 + $t4);

        $p1 = round($t1 / $grandTotal * 100, 1);
        $p2 = round($t2 / $grandTotal * 100, 1);
        $p3 = round($t3 / $grandTotal * 100, 1);
        $p4 = round($t4 / $grandTotal * 100, 1);

        $fmt = fn(float $v) => str_replace('.', ',', number_format($v, 1));

        // 7. Kembalikan struktur chart (Logika ini umum)
        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'type' => 'bar',
                    'label' => 'Tidak Memuaskan — ' . $fmt($p1) . '%',
                    'data' => $C1,
                    'yAxisID' => 'y',
                    'stack' => 'count',
                    'backgroundColor' => 'rgba(248,113,113,0.7)',
                    'borderColor' => 'rgb(239,68,68)',
                    'borderWidth' => 1,
                    'order' => 1,
                ],
                [
                    'type' => 'bar',
                    'label' => 'Kurang Memuaskan — ' . $fmt($p2) . '%',
                    'data' => $C2,
                    'yAxisID' => 'y',
                    'stack' => 'count',
                    'backgroundColor' => 'rgba(251,191,36,0.7)',
                    'borderColor' => 'rgb(245,158,11)',
                    'borderWidth' => 1,
                    'order' => 1,
                ],
                [
                    'type' => 'bar',
                    'label' => 'Cukup Memuaskan — ' . $fmt($p3) . '%',
                    'data' => $C3,
                    'yAxisID' => 'y',
                    'stack' => 'count',
                    'backgroundColor' => 'rgba(59,130,246,0.7)',
                    'borderColor' => 'rgb(59,130,246)',
                    'borderWidth' => 1,
                    'order' => 1,
                ],
                [
                    'type' => 'bar',
                    'label' => 'Sangat Memuaskan — ' . $fmt($p4) . '%',
                    'data' => $C4,
                    'yAxisID' => 'y',
                    'stack' => 'count',
                    'backgroundColor' => 'rgba(16,185,129,0.7)',
                    'borderColor' => 'rgb(16,185,129)',
                    'borderWidth' => 1,
                    'order' => 1,
                ],
            ],
            'options' => [
                'responsive' => true,
                'maintainAspectRatio' => false,
                'interaction' => ['mode' => 'index', 'intersect' => false],
                'scales' => [
                    'x' => ['stacked' => true],
                    'y' => [
                        'stacked' => true,
                        'beginAtZero' => true,
                        'title' => ['display' => true, 'text' => 'Total'],
                    ],
                ],
                'plugins' => [
                    'legend' => ['position' => 'right'],
                    'tooltip' => ['enabled' => true],
                ],
            ],
        ];
    }

    /**
     * FUNGSI HELPER BARU
     * Fungsi ini berisi logika spesifik untuk memetakan pertanyaan ke kategori
     * berdasarkan tipe laporannya (survey vs pre/post-test).
     */
    protected function buildKategoriMap(?int $pelatihanId, string $localTipe): array
    {
        $tesIds = DB::table('percobaan as pr')
            ->join('tes as t', 't.id', '=', 'pr.tes_id')
            ->when($pelatihanId, fn($q) => $q->where('t.pelatihan_id', $pelatihanId))
            ->where('t.tipe', $localTipe) // Filter $localTipe
            ->pluck('t.id')->unique()->values();

        $pertanyaanAll = Pertanyaan::whereIn('tes_id', $tesIds)
            ->orderBy('tes_id')->orderBy('nomor')
            ->get(['id', 'tes_id', 'tipe_jawaban', 'teks_pertanyaan']);

        $mapKategori = [];
        $categoryNames = $this->arrayCustom ?? [];

        foreach ($pertanyaanAll->groupBy('tes_id') as $questions) {
            $groupKey = 1;
            $tempQuestions = [];
            foreach ($questions as $q) {
                $tempQuestions[] = $q;
                $isBoundary = $q->tipe_jawaban === 'teks_bebas'
                    && str_starts_with(strtolower(trim($q->teks_pertanyaan)), 'pesan dan kesan');

                if ($isBoundary) {
                    $category = $categoryNames[$groupKey - 1] ?? ('Kategori ' . $groupKey);
                    foreach ($tempQuestions as $item) {
                        // --- LOGIKA KONDISIONAL di sini ---
                        if ($localTipe === 'survei') {
                            if ($item->tipe_jawaban === 'skala_likert') {
                                $mapKategori[$item->id] = $category;
                            }
                        } else {
                            if ($item->tipe_jawaban !== 'teks_bebas') {
                                $mapKategori[$item->id] = $category;
                            }
                        }
                    }
                    $tempQuestions = [];
                    $groupKey++;
                }
            }
            if (!empty($tempQuestions)) {
                $category = $categoryNames[$groupKey - 1] ?? ('Kategori ' . $groupKey);
                foreach ($tempQuestions as $item) {
                    // --- LOGIKA KONDISIONAL di sini (duplikat) ---
                    if ($localTipe === 'survei') {
                        if ($item->tipe_jawaban === 'skala_likert') {
                            $mapKategori[$item->id] = $category;
                        }
                    } else {
                        if ($item->tipe_jawaban !== 'teks_bebas') {
                            $mapKategori[$item->id] = $category;
                        }
                    }
                }
            }
        }

        return $mapKategori;
    }
    public function buildPiePerPertanyaan(?int $pelatihanId, ?int $pertanyaanId, array $range = []): array
    {
        // Jika tidak ada ID pertanyaan spesifik yang diberikan, kembalikan data kosong.
        if (!$pertanyaanId) {
            return [];
        }

        // 1. Ambil data jawaban yang sudah dinormalisasi HANYA untuk satu pertanyaan ini
        // Ini lebih efisien daripada mengambil semua jawaban terlebih dahulu
        [$pivot, $opsiIdToSkala, $opsiTextToId] = $this->buildLikertMaps([$pertanyaanId]);
        $rows = $this->normalizedAnswers($pelatihanId, [$pertanyaanId], $pivot, $opsiIdToSkala, $opsiTextToId);

        // 2. Ambil detail model Pertanyaan (untuk judul dan label)
        $question = Pertanyaan::with('opsiJawabans:id,pertanyaan_id,teks_opsi')->find($pertanyaanId);
        if (!$question) {
            return []; // Kembalikan kosong jika pertanyaan tidak ditemukan
        }

        // Ambil label dari teks opsi jawaban
        $labels = $question->opsiJawabans?->pluck('teks_opsi')->values()->all() ?? [];
        $labelCount = count($labels);

        // Fallback jika tidak ada opsi, buat label generik "Skala X"
        if ($labelCount === 0) {
            $maxScaleOnAnswers = $rows->max('skala') ?? 4;
            $labelCount = $maxScaleOnAnswers;
            $labels = array_map(fn($s) => "Skala {$s}", range(1, $labelCount));
        }

        // 3. Hitung jumlah (count) jawaban untuk setiap skala
        $counts = array_fill(0, $labelCount, 0);
        foreach ($rows as $r) {
            $scale = (int) ($r['skala'] ?? 0);
            if ($scale >= 1 && $scale <= $labelCount) {
                $counts[$scale - 1]++;
            }
        }

        // 4. Hitung persentase
        $total = array_sum($counts);
        $percentages = $total > 0
            ? array_map(fn($c) => round(($c / $total) * 100, 1), $counts)
            : array_fill(0, count($counts), 0);

        // 5. Kembalikan struktur data LENGKAP untuk satu chart
        return [
            'question_label' => $question->teks_pertanyaan,
            'labels'         => $labels,
            'data'           => $counts,
            'percentages'    => $percentages,
        ];
    }
    /**
     * Line: akumulasi jumlah baris jawaban per hari untuk semua pertanyaan Likert.
     */
    public function buildAkumulatif(?int $pelatihanId, array $range = []): array
    {
        // 1. Kumpulkan semua ID pertanyaan dengan tipe skala likert
        $ids = $this->collectPertanyaanIds($pelatihanId);

        // Jika tidak ada pertanyaan, kembalikan data kosong
        if ($ids->isEmpty()) {
            return ['labels' => [], 'datasets' => []];
        }

        // 2. Definisikan label dasar dan warna untuk chart (sesuai referensi)
        $baseLabels = ['Tidak Memuaskan', 'Kurang Memuaskan', 'Cukup Memuaskan', 'Sangat Memuaskan'];
        $backgroundColors = ['rgba(248,113,113,0.7)', 'rgba(251,191,36,0.7)', 'rgba(59,130,246,0.7)', 'rgba(16,185,129,0.7)'];
        $borderColors = ['rgb(239,68,68)', 'rgb(245,158,11)', 'rgb(59,130,246)', 'rgb(16,185,129)'];

        // 3. Hitung jumlah (count) untuk setiap skala jawaban
        $counts = [0, 0, 0, 0]; // Index 0 untuk Skala 1, Index 1 untuk Skala 2, dst.

        [$pivot, $opsiIdToSkala, $opsiTextToId] = $this->buildLikertMaps($ids);
        $rows = $this->normalizedAnswers($pelatihanId, $ids, $pivot, $opsiIdToSkala, $opsiTextToId);

        foreach ($rows as $r) {
            $skala = (int)($r['skala'] ?? 0);
            // Pastikan skala antara 1 dan 4
            if ($skala >= 1 && $skala <= 4) {
                // Skala 1 masuk ke index 0, Skala 2 ke index 1, ...
                $counts[$skala - 1]++;
            }
        }

        // 4. Siapkan label akhir dengan format: "Nama Skala — Persentase%"
        $total = array_sum($counts) ?: 1; // Hindari pembagian dengan nol
        $percentages = array_map(fn($count) => round(($count / $total) * 100, 1), $counts);
        $formatter = fn($value) => str_replace('.', ',', number_format($value, 1));

        $finalLabels = [];
        for ($i = 0; $i < 4; $i++) {
            $finalLabels[] = "{$baseLabels[$i]} — {$formatter($percentages[$i])}%";
        }

        // 5. Susun struktur data akhir sesuai output yang diharapkan
        return [
            'labels' => $finalLabels,
            'datasets' => [[
                'label'           => 'Jumlah Jawaban',
                'data'            => $counts,
                'backgroundColor' => $backgroundColors,
                'borderColor'     => $borderColors,
                'borderWidth'     => 1,
            ]],
            'options' => [
                'plugins' => [
                    'legend'  => ['position' => 'right'],
                    'tooltip' => ['enabled' => true],
                ],
            ],
        ];
    }


    // DATA WIDGET STATS OVERVIEW

    // STATS INSTRUKTUR
    public function instruktur(?int $pelatihan_id)
    {
        $jumlahInstruktur = Instruktur::where('pelatihan_id', $pelatihan_id)->count();
        $jumlahPesertaDiajar = Peserta::where('pelatihan_id', $pelatihan_id)->count();

        return [
            [
                'label' => 'Jumlah Instruktur Pelatihan',
                'value' => $jumlahInstruktur,
                'description' => 'Pelatihan yang sedang atau akan berjalan',
                'descriptionIcon' => 'heroicon-m-arrow-trending-up',
                'color' => 'success',
            ],
            // [
            //     'label' => 'Rata - rata Pengalaman (Tahun)',
            //     'value' => Peserta::count(),
            //     'description' => 'Jumlah seluruh peserta terdaftar',
            //     'descriptionIcon' => 'heroicon-m-users',
            //     'color' => 'info',
            // ],
            [
                'label' => 'Rata - rata Rating',
                // 'value' => Instruktur::count(),
                'value' => '90%',
                'description' => 'Jumlah seluruh instruktur',
                'descriptionIcon' => 'heroicon-m-user-group',
                'color' => 'warning',
            ],
            [
                'label' => 'Total Peserta Diajar',
                'value' => $jumlahPesertaDiajar,
                'description' => 'Jumlah kompetensi keahlian',
                'descriptionIcon' => 'heroicon-m-academic-cap',
                'color' => 'primary',
            ],
        ];
    }


    // STATS peserta
    public function peserta(?int $pelatihan_id)
    {
        $peserta = Peserta::where('pelatihan_id', $pelatihan_id)->get();
        $jumlahLulus = $peserta->where('lulus', true)->count();
        $jumlahTidakLulus = $peserta->where('lulus', false)->count();
        $rataNilai = $peserta->avg('nilai');
        $rataRating = $peserta->avg('nilai'); // di jawaban user

        return [
            [
                'label' => 'Peserta Lulus',
                'value' => $jumlahLulus,
                'description' => 'Pelatihan yang sedang atau akan berjalan',
                'descriptionIcon' => 'heroicon-m-arrow-trending-up',
                'color' => 'success',
            ],
            [
                'label' => 'Peserta Tidak Lulus',
                'value' => $jumlahTidakLulus,
                'description' => 'Jumlah seluruh peserta terdaftar',
                'descriptionIcon' => 'heroicon-m-users',
                'color' => 'info',
            ],
            [
                'label' => 'Rata - rata Nilai ',
                'value' => $rataNilai ? round($rataNilai, 2) : 0,
                'description' => 'Jumlah seluruh instruktur',
                'descriptionIcon' => 'heroicon-m-user-group',
                'color' => 'warning',
            ],
            [
                'label' => 'Rata - rata Rating Pelatihan',
                'value' => $rataRating ? round($rataRating, 2) : 0,
                'description' => 'Jumlah kompetensi keahlian',
                'descriptionIcon' => 'heroicon-m-academic-cap',
                'color' => 'primary',
            ],
        ];
    }

    // STATS kompetensi
    public function kompetensi()
    {

        $a = 'a';
        $b = 'b';
        $c = 'c';
        $d = 'd';
        return [
            [
                'label' => 'Peningkatan Tertinggi',
                'value' => $a,
                'description' => 'Pelatihan yang sedang atau akan berjalan',
                'descriptionIcon' => 'heroicon-m-arrow-trending-up',
                'color' => 'success',
            ],
            [
                'label' => 'Rata - rata Peningkatan Keseluruhan',
                'value' => $b,
                'description' => 'Jumlah seluruh peserta terdaftar',
                'descriptionIcon' => 'heroicon-m-users',
                'color' => 'info',
            ],
            [
                'label' => 'Rata - rata Total Nilai',
                'value' => $c,
                'description' => 'Jumlah seluruh instruktur',
                'descriptionIcon' => 'heroicon-m-user-group',
                'color' => 'warning',
            ],
            [
                'label' => 'Total Peserta Seluruh Kompetensi',
                'value' => $d,
                'description' => 'Jumlah kompetensi keahlian',
                'descriptionIcon' => 'heroicon-m-academic-cap',
                'color' => 'primary',
            ],
        ];
    }

    // STATS dashboard
    public function dashboard()
    {
        // Kembalikan sebagai array biasa
        return [
            [
                'label' => 'Pelatihan Aktif',
                'value' => Pelatihan::where('tanggal_selesai', '>=', Carbon::now())->count(),
                'description' => 'Pelatihan yang sedang atau akan berjalan',
                'descriptionIcon' => 'heroicon-m-arrow-trending-up',
                'color' => 'success',
            ],
            [
                'label' => 'Total Peserta',
                'value' => Peserta::count(),
                'description' => 'Jumlah seluruh peserta terdaftar',
                'descriptionIcon' => 'heroicon-m-users',
                'color' => 'info',
            ],
            [
                'label' => 'Total Instruktur',
                'value' => Instruktur::count(),
                'description' => 'Jumlah seluruh instruktur',
                'descriptionIcon' => 'heroicon-m-user-group',
                'color' => 'warning',
            ],
            [
                'label' => 'Total Kompetensi',
                'value' => Kompetensi::count(),
                'description' => 'Jumlah kompetensi keahlian',
                'descriptionIcon' => 'heroicon-m-academic-cap',
                'color' => 'primary',
            ],
        ];
    }


    // STATS Pelatihan
    protected function pelatihan()
    {
        // Anda bisa kustomisasi query ini
        $totalAktif = Pelatihan::where('status', 'Aktif')->count();
        $totalPendaftar = Peserta::whereHas('pelatihan', function ($query) {
            $query->where('status', 'Aktif');
        })->count();
        $totalSelesai = Pelatihan::where('status', 'Selesai')->count();

        return [
            [
                'label' => 'Pelatihan Aktif',
                'value' => $totalAktif,
                'description' => 'Jumlah pelatihan yang sedang berjalan',
                'descriptionIcon' => 'heroicon-o-academic-cap',
                'color' => 'success',
            ],
            [
                'label' => 'Total Pendaftar (Aktif)',
                'value' => $totalPendaftar,
                'description' => 'Jumlah peserta di pelatihan aktif',
                'descriptionIcon' => 'heroicon-o-users',
                'color' => 'primary',
            ],
            [
                'label' => 'Pelatihan Selesai',
                'value' => $totalSelesai,
                'description' => 'Jumlah pelatihan yang telah selesai',
                'descriptionIcon' => 'heroicon-o-check-badge',
                'color' => 'gray',
            ]
        ];
    }

    public function getPesertaTableConfig(): array
    {
        return [
            'widgetHeading' => 'Manajemen Data Peserta',
            'description' => 'Tabel lengkap data peserta...',
            'model' => Peserta::class, // <-- Kirim nama kelas Model
            'with' => ['kompetensi'],   // <-- Kirim relasi untuk eager loading
            'columnDefinitions' => [
                // 'kode' akan berasal dari relasi, kita ganti
                'pendaftaranPelatihan.nomor_registrasi' => ['label' => 'KODE', 'searchable' => true],
                'nama' => ['label' => 'NAMA PESERTA', 'searchable' => true],
                // 'kompetensi.nama' sudah benar karena ada eager loading 'kompetensi'
                'kompetensi.nama_kompetensi' => ['label' => 'BIDANG', 'sortable' => true],
                'pendaftaranPelatihan.nilai_pre_test' => ['label' => 'PRE-TEST'],
                'pendaftaranPelatihan.nilai_post_test' => ['label' => 'POST-TEST'],
                'pendaftaranPelatihan.nilai_praktek' => ['label' => 'PRAKTEK'],
                'pendaftaranPelatihan.rata_rata' => ['label' => 'RATA²'],
                'pendaftaranPelatihan.nilai_survey' => ['label' => 'SURVEI'],
                'pendaftaranPelatihan.status' => [
                    'label' => 'STATUS',
                    'type' => 'badge',
                    'colors' => [
                        'success' => 'Lulus',
                        'danger' => 'Tidak Lulus',
                        'primary' => 'Belum Lulus',
                    ],
                ],
            ],
            'actionDefinitions' => [
                [
                    'name' => 'view',
                    'label' => '',
                    'icon' => 'heroicon-o-eye',
                    'color' => 'gray',
                    'url' => fn(Peserta $record) => '#', // Ganti dengan route Anda
                ],
            ],
        ];
    }

    public function getTopNilaiTableConfig(): array
    {
        return [
            'widgetHeading' => 'Top Nilai Terbaik Per Peserta',
            'description' => 'Tabel ringkas yang menampilkan performa terbaik.',
            'model' => Peserta::class, // <-- Kirim nama kelas Model
            'with' => ['kompetensi', 'pendaftaranPelatihan'], // <-- Eager load relasi
            'orderByColumn' => 'pendaftaranPelatihan.rata_rata', // <-- Kirim kolom untuk diurutkan
            'orderByDirection' => 'desc',
            'limit' => 5, // <-- Kirim limit
            'columnDefinitions' => [
                'pendaftaranPelatihan.nomor_registrasi' => ['label' => 'KODE PESERTA'],
                'nama' => ['label' => 'NAMA PESERTA'],
                'kompetensi.nama_kompetensi' => ['label' => 'BIDANG'],
                'pendaftaranPelatihan.rata_rata' => ['label' => 'RATA-RATA NILAI'],
                'pendaftaranPelatihan.nilai_pre_test' => ['label' => 'NILAI PRE-TEST'],
                'pendaftaranPelatihan.nilai_post_test' => ['label' => 'NILAI POST-TEST'],
            ],
            'actionDefinitions' => [],
        ];
    }
}






// Bagian untuk memetakan pertanyaan ke kategori dinamis juga tetap sama
// $tesIds = DB::table('percobaan as pr')
//     ->join('tes as t', 't.id', '=', 'pr.tes_id')
//     ->when($pelatihanId, fn($q) => $q->where('t.pelatihan_id', $pelatihanId))
//     ->pluck('t.id')->unique()->values();

// $pertanyaanAll = Pertanyaan::whereIn('tes_id', $tesIds)
//     ->orderBy('tes_id')->orderBy('nomor')
//     ->get(['id', 'tes_id', 'tipe_jawaban', 'teks_pertanyaan']);

// $mapKategori = [];
// // Pastikan properti $arrayCustom ada di class Anda
// $categoryNames = $this->arrayCustom ?? [];

// foreach ($pertanyaanAll->groupBy('tes_id') as $questions) {
//     $groupKey = 1;
//     $tempQuestions = [];
//     foreach ($questions as $q) {
//         $tempQuestions[] = $q;
//         $isBoundary = $q->tipe_jawaban === 'teks_bebas'
//             && str_starts_with(strtolower(trim($q->teks_pertanyaan)), 'pesan dan kesan');

//         if ($isBoundary) {
//             $category = $categoryNames[$groupKey - 1] ?? ('Kategori ' . $groupKey);
//             foreach ($tempQuestions as $item) {
//                 if ($item->tipe_jawaban === 'skala_likert') {
//                     $mapKategori[$item->id] = $category;
//                 }
//             }
//             $tempQuestions = [];
//             $groupKey++;
//         }
//     }
//     if (!empty($tempQuestions)) {
//         $category = $categoryNames[$groupKey - 1] ?? ('Kategori ' . $groupKey);
//         foreach ($tempQuestions as $item) {
//             if ($item->tipe_jawaban === 'skala_likert') {
//                 $mapKategori[$item->id] = $category;
//             }
//         }
//     }
// }
