<?php

namespace App\Filament\Clusters\Fasilitas\Resources\AsramaResource\Widgets;

use App\Models\Asrama;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AsramaStatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        // Hitung statistik ringkas untuk ditampilkan di atas tabel ListAsramas
        $totalAsrama = Asrama::count();
        $asramaAktif = Asrama::where('is_active', true)->count();
        $totalKapasitas = Asrama::sum('kapasitas');

        return [
            Stat::make('Total Asrama', $totalAsrama)
                ->description('Total unit asrama yang terdaftar.')
                ->color('primary'),

            Stat::make('Asrama Aktif', $asramaAktif)
                ->description('Jumlah asrama yang siap digunakan.')
                ->color('success'),

            Stat::make('Total Kapasitas', $totalKapasitas . ' Orang')
                ->description('Kapasitas maksimal seluruh asrama.')
                ->color('info'),
        ];
    }
}