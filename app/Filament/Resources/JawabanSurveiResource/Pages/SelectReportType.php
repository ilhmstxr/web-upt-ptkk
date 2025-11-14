<?php

namespace App\Filament\Resources\JawabanSurveiResource\Pages;

use App\Filament\Resources\JawabanSurveiResource;
use App\Models\Pelatihan;
use Filament\Resources\Pages\Page;
use Livewire\Attributes\Url;

class SelectReportType extends Page
{
    protected static string $resource = JawabanSurveiResource::class;

    protected static string $view = 'filament.resources.jawaban-survei-resource.pages.select-report-type';

    protected static ?string $title = 'Pilih Tipe Laporan';

    #[Url(as: 'pelatihanId')]
    public ?int $pelatihanId = null;

    public ?string $subtitle = null;
    public ?Pelatihan $pelatihan = null;

    public function mount(): void
    {
        $this->pelatihanId ??= request()->integer('pelatihanId');

        if ($this->pelatihanId) {
            $this->pelatihan = Pelatihan::find($this->pelatihanId);
            $this->subtitle = $this->pelatihan
                ? "Pelatihan: {$this->pelatihan->nama_pelatihan}"
                : 'Pelatihan tidak ditemukan';
        } else {
            $this->subtitle = 'Pelatihan tidak spesifik';
        }
    }

    protected function getViewData(): array
    {
        return [
            'pelatihan' => $this->pelatihan,
            'subtitle' => $this->subtitle,
        ];
    }
}
