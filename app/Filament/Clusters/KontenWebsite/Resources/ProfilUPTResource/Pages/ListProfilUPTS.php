<?php

namespace App\Filament\Clusters\KontenWebsite\Resources\ProfilUPTResource\Pages;

use App\Filament\Clusters\KontenWebsite\Resources\ProfilUPTResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListProfilUPTS extends ListRecords
{
    protected static string $resource = ProfilUPTResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
