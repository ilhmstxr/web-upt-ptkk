<?php

namespace App\Filament\Widgets;

use App\Models\Bidang;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class BidangStatsOverview extends BaseWidget
{
    public Bidang $bidang;

    protected function getStats(): array
    {
        $averages = $this->bidang->peserta()
            ->join('tes', 'tes.peserta_id', '=', 'peserta.id')
            ->join('percobaan', 'percobaan.tes_id', '=', 'tes.id')
            ->select(
                'tes.jenis_tes',
                DB::raw('AVG(percobaan.nilai) as average_score')
            )
            ->groupBy('tes.jenis_tes')
            ->pluck('average_score', 'jenis_tes');

        return [
            Stat::make('Rata-Rata Pre-Test', number_format($averages->get('pre-test', 0), 2)),
            Stat::make('Rata-Rata Post-Test', number_format($averages->get('post-test', 0), 2)),
            Stat::make('Rata-Rata Praktek', number_format($averages->get('praktek', 0), 2)),
        ];
    }
}
