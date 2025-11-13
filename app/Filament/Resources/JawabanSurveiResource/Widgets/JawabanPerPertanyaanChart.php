<?php

namespace App\Filament\Resources\JawabanSurveiResource\Widgets;

use Filament\Widgets\ChartWidget;

class JawabanPerPertanyaanChart extends ChartWidget
{
    use BuildsLikertData;

    protected static ?string $heading = 'Distribusi Skala per Pertanyaan';
    protected int|string|array $columnSpan = 'full';

    protected function getData(): array
    {
        $pelatihanId   = request()->integer('pelatihanId');
        $pertanyaanIds = $this->collectPertanyaanIds($pelatihanId);
        if ($pertanyaanIds->isEmpty()) return ['labels' => [], 'datasets' => []];

        [$pivot, $opsiIdToSkala, $opsiTextToId] = $this->buildLikertMaps($pertanyaanIds);
        $rows = $this->normalizedAnswers($pelatihanId, $pertanyaanIds, $pivot, $opsiIdToSkala, $opsiTextToId);

        $matrix = [];
        foreach ($pertanyaanIds as $pid) $matrix[$pid] = [1 => 0, 2 => 0, 3 => 0, 4 => 0];
        foreach ($rows as $r) if (!empty($r['skala'])) $matrix[(int)$r['pertanyaan_id']][(int)$r['skala']]++;

        $labels = $pertanyaanIds->map(fn($id) => "Q$id")->values()->all();
        $d1 = $d2 = $d3 = $d4 = [];
        foreach ($pertanyaanIds as $pid) {
            $d1[] = $matrix[$pid][1] ?? 0;
            $d2[] = $matrix[$pid][2] ?? 0;
            $d3[] = $matrix[$pid][3] ?? 0;
            $d4[] = $matrix[$pid][4] ?? 0;
        }

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Tidak Memuaskan',
                    'data'  => $d1,
                    'backgroundColor' => 'rgba(248,113,113,0.7)', // red-400/500
                    'borderColor'     => 'rgb(239,68,68)',

                    'borderWidth'     => 1,
                ],
                [
                    'label' => 'Kurang Memuaskan',
                    'data'  => $d2,
                    'backgroundColor' => 'rgba(251,191,36,0.7)',  // amber-400/500
                    'borderColor'     => 'rgb(245,158,11)',
                    'borderWidth'     => 1,
                ],
                [
                    'label' => 'Cukup Memuaskan',
                    'data'  => $d3,
                    'backgroundColor' => 'rgba(59,130,246,0.7)',  // blue-500
                    'borderColor'     => 'rgb(59,130,246)',
                    'borderWidth'     => 1,
                ],
                [
                    'label' => 'Sangat Memuaskan',
                    'data'  => $d4,
                    'backgroundColor' => 'rgba(16,185,129,0.7)',  // emerald-500
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
                    'legend' => ['position' => 'top'],
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
