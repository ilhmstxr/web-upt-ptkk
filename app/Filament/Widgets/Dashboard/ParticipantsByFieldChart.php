<?php

namespace App\Filament\Widgets\Dashboard;

use App\Models\Peserta;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class ParticipantsByFieldChart extends ChartWidget
{
    protected static ?string $heading = 'Distribusi Peserta per Bidang';
    
    protected static ?int $sort = 3;

    protected function getData(): array
    {
        // Query ini menggabungkan tabel peserta dan bidang, lalu menghitung
        // jumlah peserta untuk setiap nama_bidang.
        $data = Peserta::join('bidang', 'peserta.bidang_id', '=', 'bidang.id')
            ->select('bidang.nama_bidang', DB::raw('count(*) as total'))
            ->groupBy('bidang.nama_bidang')
            ->pluck('total', 'nama_bidang');

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Peserta',
                    'data' => $data->values()->toArray(),
                    'backgroundColor' => '#36A2EB',
                    'borderColor' => '#36A2EB',
                ],
            ],
            'labels' => $data->keys()->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}