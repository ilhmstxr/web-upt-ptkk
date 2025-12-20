<?php

namespace App\Filament\Clusters\Evaluasi\Resources\TesResultResource\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class GlobalStatsOverview extends StatsOverviewWidget
{
    protected int | string | array $columnSpan = 'full';

    protected function getStats(): array
    {
        $row = DB::table('pendaftaran_pelatihan')
            ->selectRaw('COUNT(*) as total')
            ->selectRaw('ROUND(AVG(nilai_pre_test), 2) as pre_avg')
            ->selectRaw('ROUND(AVG(nilai_post_test), 2) as post_avg')
            ->selectRaw('ROUND(AVG(nilai_praktek), 2) as praktek_avg')
            ->selectRaw('ROUND(AVG((nilai_post_test + nilai_praktek) / 2), 2) as rata_avg')
            ->first();

        $format = fn ($val) => number_format((float) $val, 2, ',', '.');

        return [
            Stat::make('Pre-Test', $format($row->pre_avg ?? 0))
                ->color('info')
                ->icon('heroicon-o-beaker'),
            Stat::make('Post-Test', $format($row->post_avg ?? 0))
                ->color('success')
                ->icon('heroicon-o-check-circle'),
            Stat::make('Praktek', $format($row->praktek_avg ?? 0))
                ->color('warning')
                ->icon('heroicon-o-wrench'),
            Stat::make('Rata-Rata', $format($row->rata_avg ?? 0))
                ->color('primary')
                ->icon('heroicon-o-chart-bar'),
            Stat::make('Jumlah Peserta', number_format((int) ($row->total ?? 0), 0, ',', '.'))
                ->color('danger')
                ->icon('heroicon-o-user-group'),
        ];
    }
}
