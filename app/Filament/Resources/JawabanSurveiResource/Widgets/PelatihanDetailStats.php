<?php

namespace App\Filament\Resources\JawabanSurveiResource\Widgets;

use App\Filament\Resources\PesertaSurveiResource;
use App\Models\Pelatihan;
use App\Models\Percobaan;
use App\Models\Peserta;
use App\Models\PesertaSurvei;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class PelatihanDetailStats extends BaseWidget
{
    public ?Pelatihan $pelatihan = null; // Terima data Pelatihan dari Halaman

    protected function getStats(): array
    {
        if (is_null($this->pelatihan)) {
            return [];
        }

        // ... (kode perhitungan Anda)
        $totalPeserta = Peserta::where('pelatihan_id', $this->pelatihan->id)->count();
        $pesertaMengisi = PesertaSurvei::where('pelatihan_id', $this->pelatihan->id)
            ->whereHas('percobaans')
            ->count();
        $pesertaBelumMengisi = $totalPeserta - $pesertaMengisi;
        $persentase = $totalPeserta > 0 ? round(($pesertaMengisi / $totalPeserta) * 100) : 0;

        // Buat URL dasar ke halaman daftar peserta
        $baseUrl = PesertaSurveiResource::getUrl('index');

        return [
            Stat::make('Total Peserta Pelatihan', $totalPeserta)
                ->icon('heroicon-m-users')
                // URL ini akan menampilkan semua peserta dari pelatihan ini (memerlukan filter tambahan jika mau)
                ->url($baseUrl),

            Stat::make('Peserta Belum Mengisi', $pesertaBelumMengisi)
                ->icon('heroicon-m-x-circle')
                // URL ini akan menampilkan daftar peserta yang belum mengisi
                ->url($baseUrl . '?tableFilters[status][value]=belum'),
                
            Stat::make('Peserta Sudah Mengisi', $pesertaMengisi)
                ->icon('heroicon-m-check-circle')
                // URL ini akan menampilkan daftar peserta yang sudah mengisi
                ->url($baseUrl . '?tableFilters[status][value]=sudah'),

            Stat::make('Tingkat Partisipasi', "{$persentase}%")
                ->icon('heroicon-m-chart-pie')
            // Kartu ini tidak perlu diklik, jadi tidak diberi URL
        ];
    }
}
