<?php
// app/Filament/Resources/JawabanSurveiResource/Pages/ReportJawabanSurvei.php
namespace App\Filament\Resources\JawabanSurveiResource\Pages;

use App\Filament\Resources\JawabanSurveiResource;
use App\Models\Pelatihan;
use Filament\Resources\Pages\Page;
use Livewire\Attributes\Url;


class ReportJawabanSurvei extends Page
{
    protected static string $resource = JawabanSurveiResource::class;
    protected static ?string $title = null;
    protected static string $view = 'filament.resources.jawaban-surveis.pages.report-page';

    #[Url(as: 'pelatihanId')]
    public ?int $pelatihanId = null;

    protected function getHeaderWidgets(): array
    {
        return [
            // \App\Filament\Resources\JawabanSurveiResource\Widgets\JawabanPerPertanyaanChart::class,
            \App\Filament\Resources\JawabanSurveiResource\Widgets\JawabanAkumulatifChart::class,
            \App\Filament\Resources\JawabanSurveiResource\Widgets\JawabanPerKategoriChart::class,
            \App\Filament\Resources\JawabanSurveiResource\Widgets\PiePerPertanyaanWidget::class,

        ];
    }

    public function getHeading(): string
    {
        $id = request()->integer('pelatihanId');
        return (string) Pelatihan::whereKey($id)->value('nama_pelatihan');
    }
}
