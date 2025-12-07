<?php

namespace App\Filament\Clusters\KontenWebsite\Resources\CeritaKamiResource\Pages;

use App\Filament\Clusters\KontenWebsite\Resources\CeritaKamiResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCeritaKami extends EditRecord
{
    protected static string $resource = CeritaKamiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
