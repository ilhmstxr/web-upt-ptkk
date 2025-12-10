<?php

namespace App\Filament\Clusters\Fasilitas\Resources\AsramaResource\Widgets;

use App\Models\Asrama;
use App\Models\Kamar;
use App\Models\PenempatanAsrama;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AsramaStatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        // 1. Total Kapasitas Seluruh Asrama
        $totalKapasitas = Kamar::sum('kapasitas');

        // 2. Jumlah Terisi Saat Ini (Peserta Check-in)
        $terisi = PenempatanAsrama::where('status', 'check_in')->count();

        // 3. Sisa Slot
        $sisa = $totalKapasitas - $terisi;

        // 4. Hitung Persentase Hunian
        $persentase = $totalKapasitas > 0 ? round(($terisi / $totalKapasitas) * 100) : 0;
        $color = $persentase > 90 ? 'danger' : ($persentase > 70 ? 'warning' : 'success');

        return [
            Stat::make('Total Kapasitas', $totalKapasitas . ' Orang')
                ->description('Akumulasi dari semua kamar aktif')
                ->icon('heroicon-m-user-group')
                ->color('primary'),

            Stat::make('Kamar Terisi', $terisi . ' Orang')
                ->description("Okupansi: {$persentase}%")
                ->icon('heroicon-m-home-modern')
                ->chart([$persentase, 100]) // Simple chart bar
                ->color($color),

            Stat::make('Sisa Slot', $sisa . ' Bed')
                ->description('Ketersediaan saat ini')
                ->icon('heroicon-m-check-circle')
                ->color('success'),
        ];
    }
}