<?php

namespace App\Filament\Resources\JawabanSurveiResource\Widgets;

use App\Models\JawabanUser;
use App\Models\OpsiJawaban;
use App\Models\Pertanyaan;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

trait BuildsLikertData
{
    /** Ambil pertanyaan_id berbasis pelatihan → tes(survei) → pertanyaan(skala_likert). */
    protected function collectPertanyaanIds(?int $pelatihanId): Collection
    {
        return JawabanUser::query()
            ->from('jawaban_user as ju')
            ->join('percobaan as pr', 'pr.id', '=', 'ju.percobaan_id')
            ->join('tes as t', 't.id', '=', 'pr.tes_id')
            ->join('pertanyaan as p', 'p.id', '=', 'ju.pertanyaan_id')
            ->where('t.tipe', 'survei')
            ->where('p.tipe_jawaban', 'skala_likert')
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
    public function buildPerKategori(?int $pelatihanId, array $range = []): array
    {
        $ids = $this->collectPertanyaanIds($pelatihanId);
        if ($ids->isEmpty()) return ['labels' => [], 'datasets' => [], 'options' => []];

        [$pivot, $opsiIdToSkala, $opsiTextToId] = $this->buildLikertMaps($ids);
        $answers = $this->normalizedAnswers($pelatihanId, $ids, $pivot, $opsiIdToSkala, $opsiTextToId);

        // Peta kategori; fallback ke "Pertanyaan #id" bila kolom kategori null/tidak ada
        $pertanyaanRows = Pertanyaan::whereIn('id', $ids->all())->get();
        $kategoriMap = $pertanyaanRows->mapWithKeys(fn($p) => [
            $p->id => ($p->kategori ?? $p->judul ?? $p->teks ?? ('Pertanyaan #' . $p->id)),
        ]);

        $maxScale = 0;
        foreach ($opsiIdToSkala as $map) {
            $maxScale = max($maxScale, count($map));
        }
        if ($maxScale <= 0) $maxScale = 5; // default Likert 1..5

        // Hitung per kategori × skala
        $perKategori = [];
        foreach ($answers as $a) {
            if ($a['skala'] === null) continue;
            $cat = $kategoriMap[$a['pertanyaan_id']] ?? ('Pertanyaan #' . $a['pertanyaan_id']);
            $perKategori[$cat] = $perKategori[$cat] ?? array_fill(1, $maxScale, 0);
            $sk = (int) $a['skala'];
            if ($sk >= 1 && $sk <= $maxScale) {
                $perKategori[$cat][$sk]++;
            }
        }

        // Susun label kategori (x-axis) terurut
        ksort($perKategori, SORT_NATURAL | SORT_FLAG_CASE);
        $labels = array_keys($perKategori);

        // Susun datasets per skala; label dataset berisi persentase kumulatif
        $fmt = fn($n) => number_format((float) $n, 1, ',', '.');
        $datasets = [];
        for ($s = 1; $s <= $maxScale; $s++) {
            $series = array_map(fn($row) => (int) $row[$s], $perKategori);
            $totalS = array_sum($series);
            $totalAll = array_sum(array_map('array_sum', $perKategori));
            $pct = $totalAll > 0 ? $fmt(100 * $totalS / $totalAll) : '0,0';
            $datasets[] = [
                'label' => 'Skala ' . $s . ' — ' . $pct . '%',
                'data'  => array_values($series),
                'stack' => 'total',
            ];
        }

        $options = [
            'responsive' => true,
            'plugins' => ['legend' => ['display' => true]],
            'scales' => [
                'x' => ['stacked' => true],
                'y' => ['stacked' => true, 'beginAtZero' => true],
            ],
        ];

        return ['labels' => $labels, 'datasets' => $datasets, 'options' => $options];
    }

    /**
     * Pie/doughnut: komposisi skala untuk satu pertanyaan.
     * Jika $pertanyaanId null, otomatis pakai pertanyaan Likert pertama.
     */
    public function buildPiePerPertanyaan(?int $pelatihanId, array $range = [], ?int $pertanyaanId = null): array
    {
        $ids = $this->collectPertanyaanIds($pelatihanId);
        if ($ids->isEmpty()) return ['labels' => [], 'datasets' => [], 'options' => []];

        $pid = $pertanyaanId ?: (int) $ids->first();
        [$pivot, $opsiIdToSkala, $opsiTextToId] = $this->buildLikertMaps([$pid]);
        $answers = $this->normalizedAnswers($pelatihanId, [$pid], $pivot, $opsiIdToSkala, $opsiTextToId);

        $source = $opsiIdToSkala->get($pid) ? $pid : ($pivot[$pid] ?? $pid);
        $maxScale = max(1, (int) count($opsiIdToSkala->get($source, [])));
        if ($maxScale <= 0) $maxScale = 5;

        $counts = array_fill(1, $maxScale, 0);
        foreach ($answers as $a) {
            if ($a['skala'] === null) continue;
            $sk = (int) $a['skala'];
            if ($sk >= 1 && $sk <= $maxScale) $counts[$sk]++;
        }

        $total = array_sum($counts);
        $fmt = fn($n) => number_format((float) $n, 1, ',', '.');
        $labels = [];
        $values = [];
        for ($s = 1; $s <= $maxScale; $s++) {
            $values[] = (int) $counts[$s];
            $pct = $total > 0 ? $fmt(100 * $counts[$s] / $total) : '0,0';
            $labels[] = 'Skala ' . $s . ' — ' . $pct . '%';
        }

        $data = ['labels' => $labels, 'datasets' => [['label' => 'Total', 'data' => $values]]];
        $options = ['responsive' => true, 'plugins' => ['legend' => ['display' => true]]];
        return ['labels' => $labels, 'datasets' => $data['datasets'], 'options' => $options];
    }

    /**
     * Line: akumulasi jumlah baris jawaban per hari untuk semua pertanyaan Likert.
     */
    public function buildAkumulatif(?int $pelatihanId, array $range = []): array
    {
        $ids = $this->collectPertanyaanIds($pelatihanId);
        if ($ids->isEmpty()) return ['labels' => [], 'datasets' => [], 'options' => []];

        $q = DB::table('jawaban_user as ju')
            ->join('percobaan as pr', 'pr.id', '=', 'ju.percobaan_id')
            ->join('tes as t', 't.id', '=', 'pr.tes_id')
            ->whereIn('ju.pertanyaan_id', $ids->all())
            ->where('t.tipe', 'survei');

        if (!empty($range['from'])) {
            $q->whereDate('ju.created_at', '>=', $range['from']);
        }
        if (!empty($range['to'])) {
            $q->whereDate('ju.created_at', '<=', $range['to']);
        }

        if ($pelatihanId) $q->where('t.pelatihan_id', $pelatihanId);

        $rows = $q->selectRaw('DATE(ju.created_at) as d, COUNT(*) as n')
            ->groupBy('d')
            ->orderBy('d')
            ->get();

        $labels = [];
        $values = [];
        $acc = 0;
        foreach ($rows as $r) {
            $acc += (int) $r->n;
            $labels[] = (string) $r->d;
            $values[] = $acc;
        }

        $data = ['labels' => $labels, 'datasets' => [['label' => 'Akumulatif', 'data' => $values]]];
        $options = ['responsive' => true, 'plugins' => ['legend' => ['display' => true]]];
        return ['labels' => $labels, 'datasets' => $data['datasets'], 'options' => $options];
    }
}
