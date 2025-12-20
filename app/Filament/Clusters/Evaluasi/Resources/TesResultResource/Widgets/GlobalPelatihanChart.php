<?php

namespace App\Filament\Clusters\Evaluasi\Resources\TesResultResource\Widgets;

use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class GlobalPelatihanChart extends ChartWidget
{
    protected static ?string $heading = 'Statistik Nilai per Pelatihan';
    protected int | string | array $columnSpan = 'full';

    protected function getData(): array
    {
        $rows = DB::table('pendaftaran_pelatihan as pp')
            ->join('pelatihan as p', 'p.id', '=', 'pp.pelatihan_id')
            ->select([
                'p.nama_pelatihan',
                DB::raw('COALESCE(ROUND(AVG(NULLIF(pp.nilai_pre_test, 0)), 2), 0) as pre_avg'),
                DB::raw('COALESCE(ROUND(AVG(NULLIF(pp.nilai_post_test, 0)), 2), 0) as post_avg'),
                DB::raw('COALESCE(ROUND(AVG(NULLIF(pp.nilai_praktek, 0)), 2), 0) as praktek_avg'),
            ])
            ->groupBy('p.nama_pelatihan')
            ->orderBy('p.nama_pelatihan')
            ->get();

        $palette = [
            '#1d4ed8',
            '#f97316',
            '#7c2d12',
            '#2563eb',
            '#0ea5e9',
            '#16a34a',
            '#9333ea',
            '#dc2626',
        ];

        $datasets = $rows->values()->map(function ($row, $index) use ($palette) {
            $post = (float) ($row->post_avg ?? 0);
            $prak = (float) ($row->praktek_avg ?? 0);
            $rata = ($post > 0 || $prak > 0)
                ? round(($post + $prak) / 2, 2)
                : 0;

            return [
                'label' => (string) $row->nama_pelatihan,
                'data' => [
                    (float) ($row->pre_avg ?? 0),
                    $post,
                    $prak,
                    $rata,
                ],
                'borderColor' => $palette[$index % count($palette)],
                'backgroundColor' => $palette[$index % count($palette)],
                'tension' => 0.35,
                'fill' => false,
                'pointRadius' => 4,
                'pointHoverRadius' => 6,
            ];
        })->all();

        return [
            'datasets' => $datasets,
            'labels' => ['Pre-Test', 'Post-Test', 'Praktek', 'Rata-Rata'],
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => true,
                    'position' => 'top',
                    'labels' => [
                        'usePointStyle' => true,
                        'boxWidth' => 8,
                    ],
                ],
            ],
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'suggestedMax' => 100,
                ],
            ],
        ];
    }
}
