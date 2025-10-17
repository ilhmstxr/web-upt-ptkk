<?php

namespace App\Filament\Pages;

// Import semua widget yang sudah Anda buat
use App\Filament\Widgets\Dashboard\CalendarWidget;
use App\Filament\Widgets\Dashboard\ParticipantsByFieldChart;
use App\Filament\Widgets\Dashboard\StatsOverview;
use App\Filament\Widgets\Dashboard\SurveyChart;
use App\Filament\Widgets\GlobalStatsOverview;
use App\Filament\Widgets\AkumulasiSurveiChart;
use App\Filament\Widgets\PelatihanAktifTable;
// Gunakan kelas Dashboard bawaan sebagai dasar
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    // Metode ini akan mendaftarkan widget Anda secara spesifik
    public function getWidgets(): array
    {
        // return [
        //     StatsOverview::class,
        //     SurveyChart::class,
        //     ParticipantsByFieldChart::class,
        //     CalendarWidget::class,
        // ];
        return [
            GlobalStatsOverview::class,
            AkumulasiSurveiChart::class,
            PelatihanAktifTable::class,
            // CalendarWidget::class, // Sesuaikan namespace jika berbeda
            // ParticipantsByFieldChart::class, // Sesuaikan namespace jika berbeda
            // SurveyChart::class, // Sesuaikan namespace jika berbeda
        ];
    }

    // Metode ini mengatur layout kolom di dashboard
    // public function getColumns(): int | string | array
    // {
    //     return [
    //         'sm' => 1, // 1 kolom di layar kecil
    //         'lg' => 2, // 2 kolom di layar besar
    //     ];
    // }
}
