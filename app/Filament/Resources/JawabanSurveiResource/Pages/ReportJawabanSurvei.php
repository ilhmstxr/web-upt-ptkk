<?php

namespace App\Filament\Resources\JawabanSurveiResource\Pages;

use App\Filament\Resources\JawabanSurveiResource;
use App\Filament\Resources\JawabanSurveiResource\Widgets\JawabanPerPertanyaanChart;
use Filament\Resources\Pages\Page;

class ReportJawabanSurvei extends Page
{
    protected static string $resource = JawabanSurveiResource::class;

    protected static ?string $title = 'Report Jawaban Survei';

    protected static ?string $navigationIcon = 'heroicon-o-chart-pie';

    protected static string $view = 'filament.resources.jawaban-surveis.pages.report-jawaban-survei';

    public function getHeaderWidgets(): array
    {
        return [
            JawabanPerPertanyaanChart::class,
        ];
    }
}
