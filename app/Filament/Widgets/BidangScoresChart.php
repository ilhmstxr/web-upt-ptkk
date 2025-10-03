<?php

namespace App\Filament\Widgets;

use App\Models\Bidang;
use Filament\Widgets\ChartWidget;

class BidangScoresChart extends ChartWidget
{
    protected static ?string $heading = 'Grafik Akumulasi Nilai Rata-Rata per Bidang';
    protected static ?int $sort = 2; // Menampilkan widget ini di bawah tabel

    protected function getData(): array
    {
        // Menggunakan query yang sama dengan tabel akumulasi untuk konsistensi data
        $data = Bidang::query()
            ->select('bidangs.nama_bidang')
            ->selectSub(function ($query) {
                $query->from('pesertas')->join('tes', 'tes.peserta_id', '=', 'pesertas.id')->join('percobaans', 'percobaans.tes_id', '=', 'tes.id')->whereColumn('pesertas.bidang_id', 'bidangs.id')->where('tes.jenis_tes', 'pre-test')->selectRaw('AVG(percobaans.nilai)');
            }, 'avg_pre_test')
            ->selectSub(function ($query) {
                $query->from('pesertas')->join('tes', 'tes.peserta_id', '=', 'pesertas.id')->join('percobaans', 'percobaans.tes_id', '=', 'tes.id')->whereColumn('pesertas.bidang_id', 'bidangs.id')->where('tes.jenis_tes', 'post-test')->selectRaw('AVG(percobaans.nilai)');
            }, 'avg_post_test')
            ->selectSub(function ($query) {
                $query->from('pesertas')->join('tes', 'tes.peserta_id', '=', 'pesertas.id')->join('percobaans', 'percobaans.tes_id', '=', 'tes.id')->whereColumn('pesertas.bidang_id', 'bidangs.id')->where('tes.jenis_tes', 'praktek')->selectRaw('AVG(percobaans.nilai)');
            }, 'avg_praktek')
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Rata-Rata Pre-Test',
                    'data' => $data->pluck('avg_pre_test')->map(fn ($value) => round($value, 2)),
                    'borderColor' => '#FF6384',
                ],
                [
                    'label' => 'Rata-Rata Post-Test',
                    'data' => $data->pluck('avg_post_test')->map(fn ($value) => round($value, 2)),
                    'borderColor' => '#36A2EB',
                ],
                [
                    'label' => 'Rata-Rata Praktek',
                    'data' => $data->pluck('avg_praktek')->map(fn ($value) => round($value, 2)),
                    'borderColor' => '#FFCE56',
                ],
            ],
            'labels' => $data->pluck('nama_bidang'),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
