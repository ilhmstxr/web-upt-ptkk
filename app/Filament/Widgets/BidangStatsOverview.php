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
        try {
            if (!$this->bidang) {
                $total = Bidang::count();

                return [
                    Stat::make('Total Bidang', $total)
                        ->description($total > 0
                            ? "Terdapat {$total} bidang yang terdaftar"
                            : "Belum ada bidang yang terdaftar")
                        ->color($total > 0 ? 'success' : 'warning')
                        ->icon('heroicon-o-briefcase'),
                ];
            }

            // Ambil rata-rata nilai tiap jenis tes untuk bidang tertentu
            $averages = DB::table('peserta')
                ->join('tes', 'tes.peserta_id', '=', 'peserta.id')
                ->join('percobaan', 'percobaan.tes_id', '=', 'tes.id')
                ->where('peserta.bidang_id', $this->bidang->id)
                ->select('tes.jenis_tes', DB::raw('AVG(percobaan.nilai) as avg_score'))
                ->groupBy('tes.jenis_tes')
                ->pluck('avg_score', 'tes.jenis_tes');

            return [
                Stat::make('Rata-Rata Pre-Test', number_format($averages->get('pre-test', 0), 2)),
                Stat::make('Rata-Rata Post-Test', number_format($averages->get('post-test', 0), 2)),
                Stat::make('Rata-Rata Praktek', number_format($averages->get('praktek', 0), 2)),
            ];
        } catch (\Throwable $e) {
            return [
                Stat::make('Error', 'Gagal memuat data')
                    ->description($e->getMessage())
                    ->color('danger'),
            ];
        }
    }
}
