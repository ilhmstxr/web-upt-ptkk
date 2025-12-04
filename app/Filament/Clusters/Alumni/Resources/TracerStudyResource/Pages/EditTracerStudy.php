<?php

namespace App\Filament\Clusters\Alumni\Resources\TracerStudyResource\Pages;

use App\Filament\Clusters\Alumni\Resources\TracerStudyResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTracerStudy extends EditRecord
{
    protected static string $resource = TracerStudyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
