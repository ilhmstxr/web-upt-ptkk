<?php

use Filament\Widgets\ChartWidget;
use App\Models\StatistikKompetensi;

class RekapKompetensiChart extends ChartWidget
{
    protected static ?string $heading = 'Rekap Nilai Kompetensi Keahlian';

    protected function getData(): array
    {
        $batch = 'MJC_GURU_I_2025';

        $data = StatistikKompetensi::where('batch', $batch)->get();

        return [
            'datasets' => [
                [
                    'label' => 'Pre Test',
                    'data' => $data->pluck('pre_avg'),
                ],
                [
                    'label' => 'Post Test',
                    'data' => $data->pluck('post_avg'),
                ],
                [
                    'label' => 'Praktek',
                    'data' => $data->pluck('praktek_avg'),
                ],
            ],
            'labels' => $data->pluck('kompetensi_keahlian'),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
