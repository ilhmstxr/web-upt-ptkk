<?php

namespace App\Filament\Widgets;

use App\Models\Bidang;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class BidangStatsOverview extends BaseWidget
{
    /**
     * Menyimpan data bidang untuk digunakan di widget.
     * Diinisialisasi agar tidak error typed property.
     */
    public array $bidang = [];

    /**
     * Mendapatkan statistik untuk widget Bidang.
     */
    protected function getStats(): array
    {
        try {
            // Ambil semua data bidang
            $this->bidang = Bidang::all()->toArray();
        } catch (\Throwable $e) {
            // Fallback jika terjadi error database
            $this->bidang = [];
        }

        $totalBidang = count($this->bidang);
        $desc = $totalBidang > 0
            ? "Terdapat {$totalBidang} bidang yang terdaftar"
            : "Belum ada bidang yang terdaftar";

        return [
            Stat::make('Total Bidang', $totalBidang)
                ->description($desc)
                ->color($totalBidang > 0 ? 'success' : 'warning')
                ->icon('heroicon-o-briefcase'),
        ];
    }
}
