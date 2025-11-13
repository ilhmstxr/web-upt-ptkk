<?php

namespace App\Filament\Resources\JawabanSurveiResource\Widgets;

use App\Filament\Resources\PesertaSurveiResource;
use App\Models\Pelatihan;
use App\Models\Peserta;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class PelatihanDetailStats extends BaseWidget
{
    public ?Pelatihan $pelatihan = null;

    protected function getStats(): array
    {
        if (is_null($this->pelatihan)) {
            return [];
        }

        // --- Perhitungan Efisien Menggunakan Query Database ---

        // Hitung total peserta dari pelatihan ini
        $totalPeserta = Peserta::where('pelatihan_id', $this->pelatihan->id)->count();

        // Hitung peserta yang sudah mengisi menggunakan logika JOIN yang sama
        $pesertaMengisi = Peserta::query()
            ->join('peserta_survei as ps', function ($join) {
                $join
                    // Cocokkan nama (case-insensitive dan tanpa spasi ekstra)
                    ->on(DB::raw('LOWER(TRIM(peserta.nama))'), '=', DB::raw('LOWER(TRIM(ps.nama))'))
                    // ATAU cocokkan email (case-insensitive dan tanpa spasi ekstra)
                    // ->orOn(DB::raw('LOWER(TRIM(peserta.email))'), '=', DB::raw('LOWER(TRIM(ps.email))'))
                    // Pastikan join hanya pada pelatihan yang sama
                    ->where('ps.pelatihan_id', '=', $this->pelatihan->id);
            })
            // Filter utama: Hanya peserta dari pelatihan ini
            ->where('peserta.pelatihan_id', $this->pelatihan->id)
            ->count();

        $pesertaBelumMengisi = $totalPeserta - $pesertaMengisi;
        $persentase = $totalPeserta > 0 ? round(($pesertaMengisi / $totalPeserta) * 100) : 0;

        $baseUrl = PesertaSurveiResource::getUrl('index');

        return [
            Stat::make('Total Peserta Pelatihan', $totalPeserta)
                ->icon('heroicon-m-users')
                ->url($baseUrl),

            Stat::make('Peserta Belum Mengisi', $pesertaBelumMengisi)
                ->icon('heroicon-m-x-circle')
                ->url($baseUrl . '?tableFilters[status][value]=belum'),

            Stat::make('Peserta Sudah Mengisi', $pesertaMengisi)
                ->icon('heroicon-m-check-circle')
                ->url($baseUrl . '?tableFilters[status][value]=sudah'),

            Stat::make('Tingkat Partisipasi', "{$persentase}%")
                ->icon('heroicon-m-chart-pie')
        ];
    }
}
