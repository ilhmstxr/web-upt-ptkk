<?php

namespace App\Filament\Widgets;

use App\Models\Bidang;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class KompetensiStatsOverview extends BaseWidget
{
    /**
     * Menyimpan model Kompetensi yang akan digunakan di widget.
     * Bisa null agar tidak error sebelum diinisialisasi.
     */
    public ?\App\Models\Kompetensi $kompetensi = null;

    /**
     * Mendapatkan statistik untuk widget Kompetensi.
     */
    protected function getStats(): array
    {
        // Jika widget tidak menerima kompetensi tertentu → tampilkan total semua
        if (!$this->kompetensi) {
            $totalKompetensi = \App\Models\Kompetensi::count();

            return [
                Stat::make('Total Kompetensi', $totalKompetensi)
                    ->description($totalKompetensi > 0
                        ? "Terdapat {$totalKompetensi} kompetensi yang terdaftar"
                        : "Belum ada kompetensi yang terdaftar")
                    ->color($totalKompetensi > 0 ? 'success' : 'warning')
                    ->icon('heroicon-o-briefcase'),
            ];
        }

        // Kalau menerima 1 kompetensi dari halaman detail → tampilkan statistik spesifik
        $averages = $this->kompetensi->peserta()
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
