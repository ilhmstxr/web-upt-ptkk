<?php

namespace App\Filament\Clusters\KontenWebsite\Resources\KepalaUptResource\Pages;

use App\Filament\Clusters\KontenWebsite\Resources\KepalaUptResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditKepalaUpt extends EditRecord
{
    protected static string $resource = KepalaUptResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
