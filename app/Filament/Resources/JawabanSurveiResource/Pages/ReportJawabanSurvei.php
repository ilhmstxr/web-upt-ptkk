<?php
// app/Filament/Resources/JawabanSurveiResource/Pages/ReportJawabanSurvei.php

namespace App\Filament\Resources\JawabanSurveiResource\Pages;

use App\Filament\Resources\JawabanSurveiResource;
use App\Models\Pelatihan;
use Filament\Resources\Pages\Page;
use Livewire\Attributes\Url;
use Filament\Actions;

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
            Actions\Action::make('export_pdf')
                ->label('Export PDF')
                ->icon('heroicon-o-arrow-down-tray')
                ->url(fn() => route('reports.jawaban-akumulatif.pdf', [
                    'pelatihanId' => $this->pelatihanId,
                ]))
                ->openUrlInNewTab()
                ->hidden(fn() => $this->print), // sembunyikan saat mode print (Browsershot)
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            // \App\Filament\Resources\JawabanSurveiResource\Widgets\JawabanAkumulatifChart::class,
            // \App\Filament\Resources\JawabanSurveiResource\Widgets\JawabanPerKategoriChart::class,
            // \App\Filament\Resources\JawabanSurveiResource\Widgets\PiePerPertanyaanWidget::class,
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

    public function getHeading(): string
    {
        $id = $this->pelatihanId ?? request()->integer('pelatihanId');
        return (string) (Pelatihan::whereKey($id)->value('nama_pelatihan') ?: static::$title);
    }
}
