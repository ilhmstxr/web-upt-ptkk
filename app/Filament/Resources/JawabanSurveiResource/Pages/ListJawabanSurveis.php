<?php

namespace App\Filament\Resources\JawabanSurveiResource\Pages;

use App\Filament\Resources\JawabanSurveiResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListJawabanSurveis extends ListRecords
{
    protected static string $resource = JawabanSurveiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
