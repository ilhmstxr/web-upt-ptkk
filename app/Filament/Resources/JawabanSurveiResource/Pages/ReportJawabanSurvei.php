<?php
// app/Filament/Resources/JawabanSurveiResource/Pages/ReportJawabanSurvei.php

namespace App\Filament\Resources\JawabanSurveiResource\Pages;

use App\Filament\Resources\JawabanSurveiResource;
use App\Models\Pelatihan;
use Filament\Resources\Pages\Page;
use Livewire\Attributes\Url;
use Filament\Actions;
use Filament\Actions\Action;

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
            Action::make('exportPdf')
                ->label('Export PDF')
                ->icon('heroicon-o-arrow-down-tray')
                ->url(fn() => route('export.report-jawaban-survei', [
                    'pelatihanId' => $this->pelatihanId,
                    // opsional: override judul/subjudul
                    // 'heading'  => $this->getHeading(),
                    // 'subtitle' => $this->subtitle,
                ]))
                ->openUrlInNewTab()
                ->hidden(fn() => $this->print),
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
