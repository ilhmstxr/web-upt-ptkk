<?php

namespace App\Filament\Widgets;

use App\Models\Bidang;
use Filament\Widgets\ChartWidget;

class BidangScoresChart extends ChartWidget
{
    protected static ?string $heading = 'Perbandingan Nilai Rata-Rata per Bidang';
    protected static ?int $sort = 1; // Tampil di urutan pertama

    protected function getData(): array
    {
        $data = Bidang::query()
            ->withCount('peserta')
            ->select('nama_bidang')
            ->selectSub(function ($query) {
                $query->from('peserta')
                    ->join('tes', 'tes.peserta_id', '=', 'peserta.id')
                    ->join('percobaan', 'percobaan.tes_id', '=', 'tes.id')
                    ->whereColumn('peserta.bidang_id', 'bidangs.id')
                    ->where('tes.jenis_tes', 'pre-test')
                    ->selectRaw('AVG(percobaan.nilai)');
            }, 'avg_pre_test')
            ->selectSub(function ($query) {
                $query->from('peserta')
                    ->join('tes', 'tes.peserta_id', '=', 'peserta.id')
                    ->join('percobaan', 'percobaan.tes_id', '=', 'tes.id')
                    ->whereColumn('peserta.bidang_id', 'bidangs.id')
                    ->where('tes.jenis_tes', 'post-test')
                    ->selectRaw('AVG(percobaan.nilai)');
            }, 'avg_post_test')
            ->selectSub(function ($query) {
                $query->from('peserta')
                    ->join('tes', 'tes.peserta_id', '=', 'peserta.id')
                    ->join('percobaan', 'percobaan.tes_id', '=', 'tes.id')
                    ->whereColumn('pesertas.bidang_id', 'bidangs.id')
                    ->where('tes.jenis_tes', 'praktek')
                    ->selectRaw('AVG(percobaan.nilai)');
            }, 'avg_praktek')
            ->get();

        return [
            'datasets' => [
                ['label' => 'Pre-Test', 'data' => $data->pluck('avg_pre_test')->map(fn($val) => round($val, 2)), 'borderColor' => '#FF6384'],
                ['label' => 'Post-Test', 'data' => $data->pluck('avg_post_test')->map(fn($val) => round($val, 2)), 'borderColor' => '#36A2EB'],
                ['label' => 'Praktek', 'data' => $data->pluck('avg_praktek')->map(fn($val) => round($val, 2)), 'borderColor' => '#FFCE56'],
            ],
            'labels' => $data->pluck('nama_bidang'),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
