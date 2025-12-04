<?php

namespace App\Filament\Clusters\KontenWebsite\Resources\ProfilUPTResource\Pages;

use App\Filament\Clusters\KontenWebsite\Resources\ProfilUPTResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProfilUPT extends EditRecord
{
    protected static string $resource = ProfilUPTResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
