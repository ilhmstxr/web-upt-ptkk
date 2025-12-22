<?php

namespace App\Filament\Widgets;

use App\Models\Kompetensi;
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

    public static function canView(): bool
    {
        // Sembunyikan widget ini di dashboard utama (karena sudah ada di StatsOverview),
        // tapi tetap tampilkan jika dipanggil di halaman detail (dimana $kompetensi di-set)
        // Note: Filament widget di dashboard tidak memiliki context record, jadi kita cek route atau logic lain.
        // Cara simpel: Default hidden, hanya show jika dipanggil secara spesifik/manual atau cek request.

        // Namun, jika widget ini otomatis terdaftar di Dashboard, kita bisa return false
        // jika request route-nya adalah dashboard.

        return request()->routeIs('filament.admin.resources.pelatihan.view') || request()->routeIs('filament.clusters.pelatihan.resources.pelatihan-resource.view');
        // Atau cara lebih aman: cek apakah properti kompetensi di-set (tidak bisa statis).
        // Untuk dashboard global, kita return false saja jika ingin menghilangkannya total dari dashboard.
        // return false; 
    }

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
