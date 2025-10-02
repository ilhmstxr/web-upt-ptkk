<?php

namespace App\Filament\Resources\JawabanSurveiResource\Pages;

use Filament\Resources\Pages\Page; // penting: Resources\Pages\Page
use App\Filament\Resources\JawabanSurveiResource;
use App\Filament\Resources\JawabanSurveiResource\Widgets\{
    JawabanPerPertanyaanChart,
    JawabanPerKategoriChart,
    JawabanAkumulatifChart
};

class ReportJawabanSurvei extends Page
{
    protected static string $resource = JawabanSurveiResource::class;

    protected static ?string $title = 'Laporan Jawaban Survei';
    protected static ?string $navigationLabel = 'Laporan Survei';
    
    // gunakan blade khusus agar bisa punya filter (query string: pelatihanId)
    protected static string $view = 'filament.resources.jawaban-surveis.pages.report-page';

    // C:\Users\Ilhamstxr\Documents\laragon\www\htdocs\web-upt-ptkk\resources\views\filament\resources\jawaban-surveis\pages\report-page
    // tiga chart ditampilkan sebagai header widgets
    protected function getHeaderWidgets(): array
    {
        return [
            JawabanPerPertanyaanChart::class,
            JawabanPerKategoriChart::class,
            JawabanAkumulatifChart::class,
        ];
    }
}
