<?php

namespace App\Filament\Resources\JawabanSurveiResource\Widgets;

use App\Models\JawabanUser;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class JawabanPerPertanyaanChart extends ChartWidget
{
    protected static ?string $heading = 'Sebaran Jawaban Survei per Pertanyaan';
    protected int|string|array $columnSpan = 'full';

    protected function getData(): array
    {
        $pelatihanId = request()->get('pelatihanId'); // ambil dari query string

        $data = JawabanUser::query()
            ->when($pelatihanId, function ($query) use ($pelatihanId) {
                return $query->where('pelatihan_id', $pelatihanId);
            })
            ->select('pertanyaan_id', DB::raw('COUNT(*) as total'))
            ->groupBy('pertanyaan_id')
            ->get();

        return [
            'labels' => $data->pluck('pertanyaan_id')->map(fn($id) => "Pertanyaan $id"),
            'datasets' => [
                [
                    'label' => 'Jumlah Jawaban',
                    'data' => $data->pluck('total'),
                    'backgroundColor' => [
                        '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40',
                    ],
                ],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'pie'; // wajib pie chart
    }
}
