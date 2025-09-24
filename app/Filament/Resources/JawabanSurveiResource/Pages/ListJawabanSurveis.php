<?php

namespace App\Filament\Resources\JawabanSurveiResource\Pages;

use App\Filament\Resources\JawabanSurveiResource;
use App\Filament\Resources\JawabanSurveiResource\Widgets\JawabanChart;
use App\Filament\Resources\JawabanSurveiResource\Widgets\PelatihanDetailStats;
use App\Filament\Resources\JawabanSurveiResource\Widgets\PelatihanList;
use App\Filament\Resources\JawabanSurveiResource\Widgets\SurveyStatsOverview;
use App\Filament\Resources\PesertaSurveiResource\Widgets\PesertaBelumMengisi;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListJawabanSurveis extends ListRecords
{
    protected static string $resource = JawabanSurveiResource::class;

    // Daftarkan widget di sini
    protected function getHeaderWidgets(): array
    {
        return [
            SurveyStatsOverview::class,
            PelatihanList::class,
            PesertaBelumMengisi::class,
            JawabanChart::class,
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
