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


        // 1. Ambil SEMUA peserta dari pelatihan ini sebagai Collection
        $semuaPeserta = Peserta::where('pelatihan_id', $this->pelatihan->id)->get();

        // 2. Ambil SEMUA peserta yang sudah mengisi survei sebagai Collection
        $pesertaSudahMengisiCollection = PesertaSurvei::where('pelatihan_id', $this->pelatihan->id)->get();

        // 3. Lakukan filter untuk menemukan peserta yang belum mengisi, sama seperti di widget tabel
        $pesertaBelumMengisiCollection = $semuaPeserta->reject(function ($peserta) use ($pesertaSudahMengisiCollection) {
            // Cek apakah ada data di $pesertaSudahMengisiCollection yang cocok dengan $peserta saat ini
            return $pesertaSudahMengisiCollection->contains(function ($survei) use ($peserta) {
                // Kondisi pencocokan: nama ATAU email sama (case-insensitive)
                return strtolower($survei->nama) === strtolower($peserta->nama)
                    || strtolower($survei->email) === strtolower($peserta->email);
            });
        });

        // 4. Hitung angka-angka statistik berdasarkan hasil filter
        $totalPeserta = $semuaPeserta->count();
        $pesertaBelumMengisi = $pesertaBelumMengisiCollection->count();
        $pesertaMengisi = $totalPeserta - $pesertaBelumMengisi; // Dihitung dari selisih
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
