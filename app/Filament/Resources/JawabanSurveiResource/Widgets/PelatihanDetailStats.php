<?php

namespace App\Filament\Resources\JawabanSurveiResource\Widgets;

use App\Models\Pelatihan;
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

        $totalPeserta = PesertaSurvei::where('pelatihan_id', $this->pelatihan->id)->count();

        // Logika yang benar: Menghitung peserta yang memiliki data di tabel percobaans
        $pesertaMengisi = PesertaSurvei::where('pelatihan_id', $this->pelatihan->id)
            ->whereHas('percobaans')
            ->count();

        $persentase = $totalPeserta > 0 ? round(($pesertaMengisi / $totalPeserta) * 100) : 0;

        return [
            Stat::make('Total Peserta Pelatihan', $totalPeserta)->icon('heroicon-m-users'),
            Stat::make('Peserta Sudah Mengisi', $pesertaMengisi)->icon('heroicon-m-check-circle'),
            Stat::make('Tingkat Partisipasi', "{$persentase}%")->icon('heroicon-m-chart-pie'),
        ];
    }
}
