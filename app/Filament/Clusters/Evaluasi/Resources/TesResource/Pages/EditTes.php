<?php

namespace App\Filament\Clusters\Evaluasi\Resources\TesResource\Pages;

use App\Filament\Clusters\Evaluasi\Resources\TesResource;
use App\Models\Pertanyaan;
use App\Models\Percobaan;
use Carbon\Carbon;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\DB;

class EditTes extends EditRecord
{
    protected static string $resource = TesResource::class;

    protected static string $view = 'filament.clusters.evaluasi.resources.tes-resource.pages.edit-tes';

    public function getMaxContentWidth(): ?string
    {
        return '7xl';
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('save')
                ->label('Save changes')
                ->action('save'),
            Actions\ReplicateAction::make()
                ->label('Duplicate')
                ->beforeReplicaSaved(function (\Illuminate\Database\Eloquent\Model $replica) {
                    $replica->judul = $replica->judul . ' - Copy';
                })
                ->after(function (\Illuminate\Database\Eloquent\Model $replica, \Illuminate\Database\Eloquent\Model $record) {
                    // Deep copy relationships (Pertanyaan)
                    foreach ($record->pertanyaan as $pertanyaan) {
                        $newPertanyaan = $pertanyaan->replicate();
                        $newPertanyaan->tes_id = $replica->id;
                        $newPertanyaan->save();
                    }
                })
                ->successNotificationTitle('Tes berhasil diduplikasi'),
            Actions\DeleteAction::make(),
        ];
    }

    /**
     * Kirim data tambahan ke Blade: distribusi soal & rekap nilai + analisis kategori
     */
    protected function getViewData(): array
    {
        $tes = $this->record;

        // =========================================================
        // 1) ANALISIS KESUKARAN GLOBAL (mudah/sedang/sulit)
        // =========================================================
        $mudah = 0;
        $sedang = 0;
        $sulit = 0;

        // Ambil pertanyaan + hitung jumlah jawaban dan jumlah benar
        $pertanyaans = Pertanyaan::where('tes_id', $tes->id)
            ->withCount([
                'jawabanUsers',
                'jawabanUsers as benar_count' => fn ($q) =>
                    $q->whereHas('opsiJawaban', fn ($q) => $q->where('apakah_benar', true)),
            ])
            ->with(['opsiJawabans']) // untuk analisis distribusi jawaban
            ->get();

        // Detail butir untuk analisis kompleks
        $butirDetails = [];

        foreach ($pertanyaans as $p) {
            $total = (int) $p->jawaban_users_count;
            if ($total <= 0) continue;

            $benar = (int) $p->benar_count;
            $pIndex = $total > 0 ? ($benar / $total) : 0.0; // 0–1

            // Kategori kesukaran
            $level = null;
            if ($pIndex <= 0.30) {
                $sulit++;
                $level = 'sulit';
            } elseif ($pIndex <= 0.70) {
                $sedang++;
                $level = 'sedang';
            } else {
                $mudah++;
                $level = 'mudah';
            }

            // Flag “bermasalah” (kompleks):
            // - terlalu sulit (pIndex < 0.2)
            // - terlalu mudah (pIndex > 0.9)
            // - kunci tidak dominan (prop benar < 0.4)
            $flags = [];
            if ($pIndex < 0.20) $flags[] = 'Terlalu sulit';
            if ($pIndex > 0.90) $flags[] = 'Terlalu mudah';
            if ($pIndex < 0.40) $flags[] = 'Kunci lemah / banyak salah';

            $butirDetails[] = [
                'id'        => $p->id,
                'kategori'  => trim((string) ($p->kategori ?? 'Tanpa Kategori')) ?: 'Tanpa Kategori',
                'teks'      => strip_tags((string) ($p->teks_pertanyaan ?? '')),
                'total'     => $total,
                'benar'     => $benar,
                'p_index'   => round($pIndex * 100, 2), // persen
                'level'     => $level,
                'flags'     => $flags,
            ];
        }

        // =========================================================
        // 2) ANALISIS PER KATEGORI (GROUPED)
        // =========================================================
        $kategoriStats = collect($butirDetails)
            ->groupBy('kategori')
            ->map(function ($items, $kategori) {
                $items = collect($items);
                $n = $items->count();

                $avgP = $n ? round($items->avg('p_index'), 2) : 0;

                $mudah = $items->where('level', 'mudah')->count();
                $sedang = $items->where('level', 'sedang')->count();
                $sulit = $items->where('level', 'sulit')->count();

                $flagCounts = $items->pluck('flags')->flatten()->countBy()->toArray();

                // “Kesehatan kategori” sederhana:
                // bagus kalau dominan sedang, tidak ekstrem.
                $health = 'aman';
                if ($sulit > ($n * 0.5)) $health = 'rawan_sulit';
                if ($mudah > ($n * 0.5)) $health = 'rawan_mudah';
                if (($flagCounts['Kunci lemah / banyak salah'] ?? 0) >= 2) $health = 'rawan_kunci';

                return [
                    'kategori'     => $kategori,
                    'jumlah_soal'  => $n,
                    'avg_p'        => $avgP, // persen
                    'mudah'        => $mudah,
                    'sedang'       => $sedang,
                    'sulit'        => $sulit,
                    'health'       => $health,
                    'flag_counts'  => $flagCounts,
                ];
            })
            ->sortByDesc('jumlah_soal')
            ->values()
            ->all();

        // =========================================================
        // 3) DISTRIBUSI JAWABAN (Top soal bermasalah)
        // =========================================================
        // Ambil 3 soal paling “rawan” (pIndex rendah) untuk ditampilkan seperti mockup
        $topBermasalah = collect($butirDetails)
            ->sortBy('p_index') // paling kecil dulu
            ->take(3)
            ->values()
            ->all();

        // Hitung distribusi opsi untuk soal-soal tersebut (persentase tiap opsi)
        // Catatan: ini query agregasi cepat tanpa N+1.
        $distribusiJawaban = [];
        foreach ($topBermasalah as $item) {
            $qid = (int) $item['id'];

            // Total jawaban pada pertanyaan
            $totalJawaban = (int) DB::table('jawaban_user')
                ->where('pertanyaan_id', $qid)
                ->count();

            // Distribusi per opsi_jawaban_id
            $byOpsi = DB::table('jawaban_user as ju')
                ->select('ju.opsi_jawaban_id', DB::raw('COUNT(*) as cnt'))
                ->where('ju.pertanyaan_id', $qid)
                ->groupBy('ju.opsi_jawaban_id')
                ->pluck('cnt', 'opsi_jawaban_id')
                ->toArray();

            // Ambil opsi jawaban untuk mapping teks + apakah_benar
            $opsiList = DB::table('opsi_jawaban')
                ->select('id', 'teks_opsi', 'apakah_benar')
                ->where('pertanyaan_id', $qid)
                ->orderBy('id')
                ->get();

            $opsiFormatted = [];
            foreach ($opsiList as $o) {
                $cnt = (int) ($byOpsi[$o->id] ?? 0);
                $pct = $totalJawaban > 0 ? round(($cnt / $totalJawaban) * 100, 2) : 0.0;

                $opsiFormatted[] = [
                    'opsi_id'     => $o->id,
                    'teks'        => (string) $o->teks_opsi,
                    'is_kunci'    => (bool) $o->apakah_benar,
                    'count'       => $cnt,
                    'percent'     => $pct,
                ];
            }

            $distribusiJawaban[] = [
                'pertanyaan_id' => $qid,
                'teks'          => $item['teks'],
                'kategori'      => $item['kategori'],
                'p_index'       => $item['p_index'],
                'total_jawaban' => $totalJawaban,
                'opsi'          => $opsiFormatted,
            ];
        }

        // =========================================================
        // 4) REKAP NILAI (FIX anti halu kemarin)
        // =========================================================
        $latestIds = DB::table('percobaan as p')
            ->where('p.tes_id', $tes->id)
            ->whereNotNull('p.waktu_selesai')
            ->whereNotNull('p.skor')
            ->selectRaw('MAX(p.id) as id')
            ->groupBy(DB::raw('COALESCE(p.peserta_id, -1), COALESCE(p.peserta_survei_id, -1)'))
            ->pluck('id')
            ->filter()
            ->values()
            ->all();

        $rekapNilai = Percobaan::query()
            ->whereIn('id', $latestIds)
            ->with(['peserta', 'pesertaSurvei'])
            ->orderByDesc('waktu_selesai')
            ->get()
            ->map(function (Percobaan $percobaan) use ($tes) {
                $nama = '-';
                if (($tes->tipe ?? null) === 'survei' || ($tes->tipe ?? null) === 'survey') {
                    $nama = $percobaan->pesertaSurvei->nama
                        ?? $percobaan->peserta->nama
                        ?? '-';
                } else {
                    $nama = $percobaan->peserta->nama
                        ?? $percobaan->pesertaSurvei->nama
                        ?? '-';
                }

                $durasi = null;
                if ($percobaan->waktu_mulai && $percobaan->waktu_selesai) {
                    $mulai = $percobaan->waktu_mulai instanceof Carbon
                        ? $percobaan->waktu_mulai
                        : Carbon::parse($percobaan->waktu_mulai);

                    $selesai = $percobaan->waktu_selesai instanceof Carbon
                        ? $percobaan->waktu_selesai
                        : Carbon::parse($percobaan->waktu_selesai);

                    $durasi = $mulai->diffInMinutes($selesai);
                }

                $skor = $percobaan->skor;
                $lulus = !is_null($skor) && $skor >= 75;

                return [
                    'nama'   => $nama,
                    'skor'   => $skor,
                    'lulus'  => $lulus,
                    'durasi' => $durasi,
                ];
            });

        // =========================================================
        // Return ke Blade
        // =========================================================
        return [
            'mudah'              => $mudah,
            'sedang'             => $sedang,
            'sulit'              => $sulit,

            'kategoriStats'      => $kategoriStats,
            'butirDetails'       => $butirDetails,
            'distribusiJawaban'  => $distribusiJawaban,

            'rekapNilai'         => $rekapNilai,
        ];
    }

    /**
     * Download rekap nilai Tes ini dalam bentuk CSV
     */
    public function downloadRekap()
    {
        $tes = $this->record;

        $latestIds = DB::table('percobaan as p')
            ->where('p.tes_id', $tes->id)
            ->whereNotNull('p.waktu_selesai')
            ->whereNotNull('p.skor')
            ->selectRaw('MAX(p.id) as id')
            ->groupBy(DB::raw('COALESCE(p.peserta_id, -1), COALESCE(p.peserta_survei_id, -1)'))
            ->pluck('id')
            ->filter()
            ->values()
            ->all();

        $rekap = Percobaan::query()
            ->whereIn('id', $latestIds)
            ->with(['peserta', 'pesertaSurvei'])
            ->orderByDesc('waktu_selesai')
            ->get()
            ->map(function (Percobaan $percobaan) use ($tes) {
                $nama = '-';
                if (($tes->tipe ?? null) === 'survei' || ($tes->tipe ?? null) === 'survey') {
                    $nama = $percobaan->pesertaSurvei->nama
                        ?? $percobaan->peserta->nama
                        ?? '-';
                } else {
                    $nama = $percobaan->peserta->nama
                        ?? $percobaan->pesertaSurvei->nama
                        ?? '-';
                }

                $skor = $percobaan->skor;
                $lulus = !is_null($skor) && $skor >= 75;

                $durasi = null;
                if ($percobaan->waktu_mulai && $percobaan->waktu_selesai) {
                    $mulai = $percobaan->waktu_mulai instanceof Carbon
                        ? $percobaan->waktu_mulai
                        : Carbon::parse($percobaan->waktu_mulai);

                    $selesai = $percobaan->waktu_selesai instanceof Carbon
                        ? $percobaan->waktu_selesai
                        : Carbon::parse($percobaan->waktu_selesai);

                    $durasi = $mulai->diffInMinutes($selesai);
                }

                return [
                    'nama'   => $nama,
                    'skor'   => $skor,
                    'status' => $lulus ? 'Lulus' : 'Remedial',
                    'durasi' => $durasi,
                ];
            });

        $fileName = 'rekap-nilai-tes-' . $tes->id . '.csv';

        return response()->streamDownload(function () use ($rekap) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Nama Peserta', 'Skor', 'Status', 'Durasi (Menit)']);

            foreach ($rekap as $row) {
                fputcsv($handle, [
                    $row['nama'],
                    $row['skor'],
                    $row['status'],
                    $row['durasi'] ?? '-',
                ]);
            }
            fclose($handle);
        }, $fileName, [
            'Content-Type' => 'text/csv',
        ]);
    }
}
