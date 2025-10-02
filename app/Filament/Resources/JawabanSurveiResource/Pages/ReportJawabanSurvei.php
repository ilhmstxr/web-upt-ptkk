<?php

namespace App\Filament\Resources\JawabanSurveiResource\Pages;

use Filament\Resources\Pages\Page;
use App\Filament\Resources\JawabanSurveiResource;
use App\Models\Pelatihan;

class ReportJawabanSurvei extends Page
{
    protected static string $resource = JawabanSurveiResource::class;

    protected static ?string $title = null;               // matikan judul default
    protected static ?string $navigationLabel = 'Laporan Survei';
    protected static string $view = 'filament.resources.jawaban-surveis.pages.report-page';

    protected function getHeaderWidgets(): array
    {
        return [
            \App\Filament\Resources\JawabanSurveiResource\Widgets\JawabanPerPertanyaanChart::class,
            \App\Filament\Resources\JawabanSurveiResource\Widgets\JawabanPerKategoriChart::class,
            \App\Filament\Resources\JawabanSurveiResource\Widgets\JawabanAkumulatifChart::class,
        ];
    }

    public function getHeading(): string
    {
        $id = request()->integer('pelatihanId');
        return (string) Pelatihan::whereKey($id)->value('nama_pelatihan'); // hanya satu judul
    }

    public function getSubheading(): ?string
    {
        return 'Laporan Jawaban Survei'; // opsional: subjudul kecil
    }
}
