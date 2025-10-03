<?php

namespace App\Filament\Widgets;

use App\Models\Bidang;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class BidangStatsOverview extends BaseWidget
{
    public ?Bidang $bidang = null;

    protected function getStats(): array
    {
        if (!$this->bidang) {
            return [];
        }

        // Fungsi untuk menghitung rata-rata
        $getAverage = function ($jenisTes) {
            return $this->bidang->peserta()
                ->join('tes', 'tes.peserta_id', '=', 'pesertas.id')
                ->join('percobaans', 'percobaans.tes_id', '=', 'tes.id')
                ->where('tes.jenis_tes', $jenisTes)
                ->avg('percobaans.nilai');
        };

        $avgPreTest = $getAverage('pre-test');
        $avgPostTest = $getAverage('post-test');
        $avgPraktek = $getAverage('praktek');
        
        $overallAvg = ($avgPreTest + $avgPostTest + $avgPraktek) / 3;

        return [
            Stat::make('Rata-Rata Pre-Test', number_format($avgPreTest, 2)),
            Stat::make('Rata-Rata Post-Test', number_format($avgPostTest, 2)),
            Stat::make('Rata-Rata Praktek', number_format($avgPraktek, 2)),
            Stat::make('Rata-Rata Keseluruhan', number_format($overallAvg, 2)),
        ];
    }
}
