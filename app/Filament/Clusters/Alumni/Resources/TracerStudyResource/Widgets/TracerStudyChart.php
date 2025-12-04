<?php

namespace App\Filament\Clusters\Alumni\Resources\TracerStudyResource\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\TracerStudy;

class TracerStudyChart extends ChartWidget
{
    protected static ?string $heading = 'Status Pekerjaan Alumni';

    protected function getData(): array
    {
        $data = TracerStudy::select('status')
            ->get()
            ->groupBy('status');

        return [
            'datasets' => [
                [
                    'label' => 'Status Alumni',
                    'data' => [
                        $data->get('Bekerja')?->count() ?? 0,
                        $data->get('Wirausaha')?->count() ?? 0,
                        $data->get('Mencari Kerja')?->count() ?? 0,
                        $data->get('Melanjutkan Studi')?->count() ?? 0,
                    ],
                    'backgroundColor' => [
                        '#22c55e', // green-500
                        '#3b82f6', // blue-500
                        '#eab308', // yellow-500
                        '#a855f7', // purple-500
                    ],
                ],
            ],
            'labels' => ['Bekerja', 'Wirausaha', 'Mencari Kerja', 'Melanjutkan Studi'],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
