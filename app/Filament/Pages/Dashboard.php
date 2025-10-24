<?php

namespace App\Filament\Pages;

// Import widget yang akan digunakan
use App\Filament\Widgets\GlobalStatsOverview;
use App\Filament\Widgets\PelatihanAktifTable;
use App\Filament\Widgets\AkumulasiSurveiChart;
use App\Filament\Widgets\DaftarPelatihanWidget;
use App\Filament\Widgets\Dashboard\StatsOverview;
use App\Filament\Widgets\InformasiAsramaWidget;
use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Support\Facades\Filament;

class Dashboard extends BaseDashboard
{
    /**
     * Mengatur widget yang akan ditampilkan di dashboard.
     *
     * @return array<class-string<\Filament\Widgets\Widget>>
     */
    public function getWidgets(): array
    {
        return [
            // GlobalStatsOverview::class,
            // StatsOverview::class,
            // DaftarPelatihanWidget::class, 
            // PelatihanAktifTable::class,
            // AkumulasiSurveiChart::class,
            // InformasiAsramaWidget::class,
        ];
    }

    /**
     * Mengatur layout kolom untuk widget.
     *
     * @return int | string | array
     */
    public function getColumns(): int | string | array
    {
        return 2; // Mengatur layout menjadi 2 kolom
    }

    /**
     * Mengatur judul halaman dashboard.
     *
     * @return string
     */
    public function getTitle(): string
    {
        return 'Dashboard Utama';
    }
}
