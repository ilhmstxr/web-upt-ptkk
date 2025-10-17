<?php

namespace App\Filament\Widgets;

use App\Models\JawabanUser;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class AkumulasiSurveiChart extends ChartWidget
{
    protected static ?string $heading = 'Akumulasi Survei Peserta';
    protected static ?string $pollingInterval = '30s';

    protected function getData(): array
    {
        // Query untuk agregasi data survei dari JawabanUser
        $data = JawabanUser::query()
            ->whereHas('percobaan.tes', function ($query) {
                // Asumsi tipe 'Survei' digunakan untuk menandai tes sebagai survei
                $query->where('tipe', 'Survei');
            })
            ->whereNotNull('jawaban_teks')
            ->select('jawaban_teks', DB::raw('count(*) as total'))
            ->groupBy('jawaban_teks')
            ->pluck('total', 'jawaban_teks')
            ->toArray();

        return [
            'datasets' => [
                [
                    'label' => 'Hasil Survei',
                    'data' => array_values($data),
                    'backgroundColor' => ['#34D399', '#FBBF24', '#60A5FA', '#F87171'],
                ],
            ],
            'labels' => array_keys($data),
        ];
    }

    protected function getType(): string
    {
        return 'pie'; // Atau 'bar' jika ingin stacked bar chart
    }
}
