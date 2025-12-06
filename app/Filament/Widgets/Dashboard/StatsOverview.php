<?php

namespace App\Filament\Widgets\Dashboard;

use App\Models\Kompetensi;
use App\Models\Instruktur;
use App\Models\Pelatihan;
use App\Models\Peserta;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        return [
            Stat::make('Pelatihan Aktif', Pelatihan::where('tanggal_selesai', '>=', Carbon::now())->count())
                ->description('Pelatihan yang sedang atau akan berjalan')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),

            Stat::make('Total Peserta', Peserta::count())
                ->description('Jumlah seluruh peserta terdaftar')
                ->descriptionIcon('heroicon-m-users')
                ->color('info'),

            Stat::make('Total Instruktur', Instruktur::count())
                ->description('Jumlah seluruh instruktur')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('warning'),

            Stat::make('Total Kompetensi', Kompetensi::count())
                ->description('Jumlah kompetensi keahlian')
                ->descriptionIcon('heroicon-m-academic-cap')
                ->color('primary'),
        ];
    }
}

