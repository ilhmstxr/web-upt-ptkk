<?php

namespace App\Filament\Clusters\Alumni\Resources\TracerStudyResource\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\TracerStudy;

class TracerStudySalaryChart extends ChartWidget
{
    protected static ?string $heading = 'Range Gaji Alumni';

    protected function getData(): array
    {
        // Range: < 3jt, 3-5jt, 5-10jt, > 10jt
        $data = TracerStudy::select('salary')
            ->whereNotNull('salary')
            ->get()
            ->groupBy(function ($item) {
                if ($item->salary < 3000000) return '< 3 Juta';
                if ($item->salary <= 5000000) return '3 - 5 Juta';
                if ($item->salary <= 10000000) return '5 - 10 Juta';
                return '> 10 Juta';
            });

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Alumni',
                    'data' => [
                        $data->get('< 3 Juta')?->count() ?? 0,
                        $data->get('3 - 5 Juta')?->count() ?? 0,
                        $data->get('5 - 10 Juta')?->count() ?? 0,
                        $data->get('> 10 Juta')?->count() ?? 0,
                    ],
                    'backgroundColor' => '#3b82f6',
                ],
            ],
            'labels' => ['< 3 Juta', '3 - 5 Juta', '5 - 10 Juta', '> 10 Juta'],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
