<?php

namespace App\Filament\Resources\JawabanSurveiResource\Pages;

use App\Filament\Resources\JawabanSurveiResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditJawabanSurvei extends EditRecord
{
    protected static string $resource = JawabanSurveiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
