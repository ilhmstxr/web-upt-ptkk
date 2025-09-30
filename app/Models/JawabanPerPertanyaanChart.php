<?php

namespace App\Filament\Widgets;

use App\Models\JawabanUser;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;

class JawabanPerPertanyaanChart extends ApexChartWidget
{
    protected static string $chartId = 'jawabanPerPertanyaan';
    protected static ?string $heading = 'Distribusi Jawaban (Tes/Survei)';

    protected function getOptions(): array
    {
        // sementara hardcode ID pertanyaan = 1
        $pertanyaanId = 1;

        $data = JawabanUser::where('pertanyaan_id', $pertanyaanId)
            ->join('opsi_jawaban', 'jawaban_user.opsi_jawaban_id', '=', 'opsi_jawaban.id')
            ->selectRaw('opsi_jawaban.teks as label, COUNT(*) as total')
            ->groupBy('opsi_jawaban.teks')
            ->pluck('total', 'label');

        return [
            'chart' => [
                'type' => 'pie',
            ],
            'series' => $data->values()->toArray(),
            'labels' => $data->keys()->toArray(),
        ];
    }
}
