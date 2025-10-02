<?php

namespace App\Filament\Resources\JawabanSurveiResource\Widgets;

use Filament\Widgets\ChartWidget;

class JawabanAkumulatifChart extends ChartWidget
{
    use BuildsLikertData;

    protected static ?string $heading = 'Akumulatif Skala Likert (1–4)';
    protected int|string|array $columnSpan = 'full';

    protected function getData(): array
    {
        $pelatihanId   = request()->integer('pelatihanId');
        $pertanyaanIds = $this->collectPertanyaanIds($pelatihanId);

        $labels = ['Tidak Memuaskan', 'Kurang Memuaskan', 'Cukup Memuaskan', 'Sangat Memuaskan'];
        $colorsBg = [
            'rgba(248,113,113,0.7)', // red-400
            'rgba(251,191,36,0.7)',  // amber-400
            'rgba(59,130,246,0.7)',  // blue-500
            'rgba(16,185,129,0.7)',  // emerald-500
        ];
        $colorsBorder = [
            'rgb(239,68,68)',
            'rgb(245,158,11)',
            'rgb(59,130,246)',
            'rgb(16,185,129)',
        ];

        if ($pertanyaanIds->isEmpty()) {
            return [
                'labels' => $labels,
                'datasets' => [[
                    'label' => 'Jumlah Jawaban',
                    'data' => [0, 0, 0, 0],
                    'backgroundColor' => $colorsBg,
                    'borderColor' => $colorsBorder,
                    'borderWidth' => 1,
                ]],
            ];
        }

        [$pivot, $opsiIdToSkala, $opsiTextToId] = $this->buildLikertMaps($pertanyaanIds);
        $rows   = $this->normalizedAnswers($pelatihanId, $pertanyaanIds, $pivot, $opsiIdToSkala, $opsiTextToId);

        $counts = [1 => 0, 2 => 0, 3 => 0, 4 => 0];
        foreach ($rows as $r) {
            if (!empty($r['skala'])) $counts[$r['skala']]++;
        }

        return [
            'labels' => $labels,
            'datasets' => [[
                'label' => 'Jumlah Jawaban',
                'data' => [$counts[1], $counts[2], $counts[3], $counts[4]],
                'backgroundColor' => $colorsBg,
                'borderColor' => $colorsBorder,
                'borderWidth' => 1,
            ]],
            'options' => [
                'plugins' => [
                    'legend'  => ['position' => 'top'],
                    'tooltip' => ['mode' => 'index', 'intersect' => false],
                ],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }
}
