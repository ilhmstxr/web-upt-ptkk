<?php

namespace App\Filament\Resources\JawabanSurveiResource\Widgets;

use App\Models\Pertanyaan;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class JawabanPerKategoriChart extends ChartWidget
{
    use BuildsLikertData;

    protected static ?string $heading = 'Distribusi Skala per Kategori';
    protected int|string|array $columnSpan = 'full';

    private array $arrayCustom = [
        'Pendapat Tentang Penyelenggaran Pelatihan',
        'Persepsi Terhadap Program Pelatihan',
        'Penilaian Terhadap Instruktur',
    ];

    protected function getData(): array
    {
        $pelatihanId   = request()->integer('pelatihanId');
        $pertanyaanIds = $this->collectPertanyaanIds($pelatihanId);
        if ($pertanyaanIds->isEmpty()) return ['labels' => [], 'datasets' => []];

        [$pivot, $opsiIdToSkala, $opsiTextToId] = $this->buildLikertMaps($pertanyaanIds);
        $rows = $this->normalizedAnswers($pelatihanId, $pertanyaanIds, $pivot, $opsiIdToSkala, $opsiTextToId);

        $tesIds = DB::table('percobaan as pr')
            ->join('tes as t', 't.id', '=', 'pr.tes_id')
            ->when($pelatihanId, fn($q) => $q->where('t.pelatihan_id', $pelatihanId))
            ->pluck('t.id')->unique()->values();

        $pertanyaanAll = Pertanyaan::whereIn('tes_id', $tesIds)
            ->orderBy('tes_id')->orderBy('nomor')
            ->get(['id', 'tes_id', 'tipe_jawaban', 'teks_pertanyaan', 'nomor']);

        $mapKategori = [];
        foreach ($pertanyaanAll->groupBy('tes_id') as $questions) {
            $groupKey = 1;
            $temp = [];
            foreach ($questions as $q) {
                $temp[] = $q;
                $isBoundary = $q->tipe_jawaban === 'teks_bebas'
                    && str_starts_with(strtolower(trim($q->teks_pertanyaan)), 'pesan dan kesan');
                if ($isBoundary) {
                    $category = $this->arrayCustom[$groupKey - 1] ?? ('Kategori ' . $groupKey);
                    foreach ($temp as $item) {
                        if ($item->tipe_jawaban === 'skala_likert') {
                            $mapKategori[$item->id] = $category;
                        }
                    }
                    $temp = [];
                    $groupKey++;
                }
            }
            if (!empty($temp)) {
                $category = $this->arrayCustom[$groupKey - 1] ?? ('Kategori ' . $groupKey);
                foreach ($temp as $item) {
                    if ($item->tipe_jawaban === 'skala_likert') {
                        $mapKategori[$item->id] = $category;
                    }
                }
            }
        }

        // Hanya akumulasi untuk pertanyaan yang memiliki kategori
        $matrix = [];
        foreach ($rows as $r) {
            if (!isset($mapKategori[$r['pertanyaan_id']])) {
                continue; // skip tanpa kategori
            }
            $cat = $mapKategori[$r['pertanyaan_id']];
            $matrix[$cat] = $matrix[$cat] ?? [1 => 0, 2 => 0, 3 => 0, 4 => 0];
            if (!empty($r['skala'])) {
                $matrix[$cat][(int) $r['skala']]++;
            }
        }

        if (empty($matrix)) {
            return ['labels' => [], 'datasets' => []];
        }

        $labels = array_keys($matrix);
        $d1 = $d2 = $d3 = $d4 = [];
        foreach ($labels as $cat) {
            $d1[] = $matrix[$cat][1] ?? 0;
            $d2[] = $matrix[$cat][2] ?? 0;
            $d3[] = $matrix[$cat][3] ?? 0;
            $d4[] = $matrix[$cat][4] ?? 0;
        }

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Tidak Memuaskan',
                    'data'  => $d1,
                    'backgroundColor' => 'rgba(248,113,113,0.7)',
                    'borderColor'     => 'rgb(239,68,68)',
                    'borderWidth'     => 1,
                ],
                [
                    'label' => 'Kurang Memuaskan',
                    'data'  => $d2,
                    'backgroundColor' => 'rgba(251,191,36,0.7)',
                    'borderColor'     => 'rgb(245,158,11)',
                    'borderWidth'     => 1,
                ],
                [
                    'label' => 'Cukup Memuaskan',
                    'data'  => $d3,
                    'backgroundColor' => 'rgba(59,130,246,0.7)',
                    'borderColor'     => 'rgb(59,130,246)',
                    'borderWidth'     => 1,
                ],
                [
                    'label' => 'Sangat Memuaskan',
                    'data'  => $d4,
                    'backgroundColor' => 'rgba(16,185,129,0.7)',
                    'borderColor'     => 'rgb(16,185,129)',
                    'borderWidth'     => 1,
                ],
            ],
            'options' => [
                'scales' => [
                    'x' => ['stacked' => true],
                    'y' => ['stacked' => true, 'beginAtZero' => true],
                ],
                'plugins' => [
                    'legend'  => ['position' => 'top'],
                    'tooltip' => ['mode' => 'index', 'intersect' => false],
                ],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
