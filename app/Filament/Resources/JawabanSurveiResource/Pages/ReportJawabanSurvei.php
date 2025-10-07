<?php

namespace App\Filament\Resources\JawabanSurveiResource\Pages;

use App\Filament\Resources\JawabanSurveiResource;
use App\Models\Pelatihan;
use Filament\Actions\Action;
use Filament\Resources\Pages\Page;
use Livewire\Attributes\Url;

class ReportJawabanSurvei extends Page
{
    protected static string $resource = JawabanSurveiResource::class;
    protected static string $view = 'filament.resources.jawaban-surveis.pages.report-page';
    protected static ?string $title = 'Report Jawaban Survei';

    #[Url(as: 'pelatihanId')]
    public ?int $pelatihanId = null;

    #[Url(as: 'print')]
    public bool $print = false;

    public ?string $subtitle = null;

    public function mount(): void
    {
        $this->pelatihanId ??= request()->integer('pelatihanId');
        $this->print = request()->boolean('print');

        $nama = $this->pelatihanId
            ? Pelatihan::whereKey($this->pelatihanId)->value('nama_pelatihan')
            : null;

        $this->subtitle = $nama ? "Pelatihan: {$nama}" : null;
    }

    protected function getHeaderActions(): array
    {
        return [
            // Action::make('exportPdf')
            //     ->label('Export PDF')
            //     ->color('success')
            //     ->icon('heroicon-o-document-arrow-down')
            //     ->url(fn() => route('export.report.pelatihan', ['pelatihanId' => $this->pelatihanId]), shouldOpenInNewTab: true),
            Action::make('PDF-View')
                ->label('Pdf View')
                ->color('success')
                ->icon('heroicon-o-document-arrow-down')
                ->url(fn() => route('reports.jawaban-survei.pdf', ['pelatihanId' => $this->pelatihanId]), shouldOpenInNewTab: true),
        ];
    }

    protected function getViewData(): array
    {
        return [
            'pelatihanId' => $this->pelatihanId,
            'title'       => static::$title,
            'subtitle'    => $this->subtitle,
            'print'       => $this->print,
        ];
    }
}
