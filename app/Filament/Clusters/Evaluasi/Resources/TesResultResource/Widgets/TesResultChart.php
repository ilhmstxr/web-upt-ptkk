<?php

namespace App\Filament\Clusters\Evaluasi\Resources\TesResultResource\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Percobaan;
use Illuminate\Support\Facades\DB;

class TesResultChart extends ChartWidget
{
    protected static ?string $heading = 'Distribusi Nilai Tes';

    protected int | string | array $columnSpan = 'full';

    protected function getData(): array
    {
        // Distribusi Nilai: 0-50, 51-70, 71-85, 86-100
        $data = Percobaan::select('skor')
            ->get()
            ->groupBy(function ($item) {
                if ($item->skor <= 50) return '0-50';
                if ($item->skor <= 70) return '51-70';
                if ($item->skor <= 85) return '71-85';
                return '86-100';
            });

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Peserta',
                    'data' => [
                        $data->get('0-50')?->count() ?? 0,
                        $data->get('51-70')?->count() ?? 0,
                        $data->get('71-85')?->count() ?? 0,
                        $data->get('86-100')?->count() ?? 0,
                    ],
                    'backgroundColor' => [
                        '#ef4444', // red-500
                        '#eab308', // yellow-500
                        '#3b82f6', // blue-500
                        '#22c55e', // green-500
                    ],
                ],
            ],
            'labels' => ['0-50 (Kurang)', '51-70 (Cukup)', '71-85 (Baik)', '86-100 (Sangat Baik)'],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
