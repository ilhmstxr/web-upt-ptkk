<?php

namespace App\Filament\Widgets;

use App\Models\Bidang;
use App\Models\Instruktur;
use App\Models\Pelatihan;
use App\Models\Peserta;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class GlobalStatsOverview extends BaseWidget    
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Pelatihan', Pelatihan::count())
                ->description('Jumlah seluruh pelatihan')
                ->color('success')
                ->chart([7, 2, 10, 3, 15, 4, 17]), // Data chart dummy
            Stat::make('Total Peserta', Peserta::count())
                ->description('Jumlah seluruh peserta terdaftar')
                ->color('warning'),
            Stat::make('Total Instruktur', Instruktur::count())
                ->description('Jumlah instruktur ahli')
                ->color('info'),
            Stat::make('Total Bidang', Bidang::count())
                ->description('Jumlah bidang keahlian')
                ->color('primary'),
        ];
    }
}