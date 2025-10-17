<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class InformasiAsramaWidget extends BaseWidget
{
    // INI ADALAH DEKLARASI YANG BENAR (NON-STATIC)
    protected ?string $heading = 'Informasi Asrama';

    // Mengatur lebar widget agar menempati 1 kolom
    protected int | string | array $columnSpan = 1;

    protected function getStats(): array
    {
        // --- GANTI DENGAN LOGIKA ANDA ---
        $totalKamar = 60; // Contoh
        $kamarTerisi = 45; // Contoh dari query
        $ketersediaan = $totalKamar - $kamarTerisi;

        return [
            Stat::make('Kamar Terisi', "{$kamarTerisi} / {$totalKamar}")
                ->description('Total kamar yang sedang digunakan')
                ->color('warning'),
            Stat::make('Ketersediaan', $ketersediaan)
                ->description('Jumlah kamar yang masih kosong')
                ->color('success'),
        ];
    }
}