<?php

namespace App\Filament\Widgets;

use App\Models\Bidang;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class BidangStatsOverview extends BaseWidget
{
    /**
     * Menyimpan model Bidang yang akan digunakan di widget.
     * Bisa null agar tidak error sebelum diinisialisasi.
     */
    public ?Bidang $bidang = null;

    /**
     * Mendapatkan statistik untuk widget Bidang.
     */
    protected function getStats(): array
    {
        // Jika widget tidak menerima bidang tertentu → tampilkan total semua
        if (!$this->bidang) {
            $totalBidang = Bidang::count();

            return [
                Stat::make('Total Bidang', $totalBidang)
                    ->description($totalBidang > 0
                        ? "Terdapat {$totalBidang} bidang yang terdaftar"
                        : "Belum ada bidang yang terdaftar")
                    ->color($totalBidang > 0 ? 'success' : 'warning')
                    ->icon('heroicon-o-briefcase'),
            ];
        }

        // Kalau menerima 1 bidang dari halaman detail → tampilkan statistik spesifik
        $averages = $this->bidang->peserta()
            ->join('tes', 'tes.peserta_id', '=', 'peserta.id')
            ->join('percobaan', 'percobaan.tes_id', '=', 'tes.id')
            ->select(
                'tes.jenis_tes',
                DB::raw('AVG(percobaan.nilai) as average_score')
            )
            ->groupBy('tes.jenis_tes')
            ->pluck('average_score', 'tes.jenis_tes');

        return [
            Stat::make('Rata-Rata Pre-Test', number_format($averages->get('pre-test', 0), 2)),
            Stat::make('Rata-Rata Post-Test', number_format($averages->get('post-test', 0), 2)),
            Stat::make('Rata-Rata Praktek', number_format($averages->get('praktek', 0), 2)),
        ];
    }
}
