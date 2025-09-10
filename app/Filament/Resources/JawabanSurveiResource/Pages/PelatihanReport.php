<?php

namespace App\Filament\Resources\JawabanSurveiResource\Pages;

use App\Filament\Resources\JawabanSurveiResource;
use App\Filament\Resources\JawabanSurveiResource\Widgets\JawabanChart;
use App\Filament\Resources\JawabanSurveiResource\Widgets\PelatihanDetailStats;
use App\Filament\Resources\PesertaSurveiResource\Widgets\PesertaBelumMengisi;
use App\Models\Pelatihan;
use Filament\Resources\Pages\Page;

class PelatihanReport extends Page
{
    protected static string $resource = JawabanSurveiResource::class;
    protected static string $view = 'filament.resources.jawaban-survei-resource.pages.pelatihan-report';
    protected static bool $shouldRegisterNavigation = false;

    public ?Pelatihan $pelatihan;

    public function mount(int $pelatihanId): void
    {
        $this->pelatihan = Pelatihan::findOrFail($pelatihanId);
    }

    public function getTitle(): string
    {
        return 'Laporan Survei: ' . $this->pelatihan->nama_pelatihan;
    }

    // Cukup panggil satu JawabanChart dan widget lainnya
    protected function getHeaderWidgets(): array
    {
        return [
            PelatihanDetailStats::make(['pelatihan' => $this->pelatihan]),
            JawabanChart::make(['pelatihan' => $this->pelatihan]), // Panggil satu kali saja
            PesertaBelumMengisi::make(['pelatihan' => $this->pelatihan]),
        ];
    }
}
