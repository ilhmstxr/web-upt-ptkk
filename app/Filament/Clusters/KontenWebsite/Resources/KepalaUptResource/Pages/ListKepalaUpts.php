<?php

namespace App\Filament\Clusters\KontenWebsite\Resources\KepalaUptResource\Pages;

use App\Filament\Clusters\KontenWebsite\Resources\KepalaUptResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListKepalaUpts extends ListRecords
{
    protected static string $resource = KepalaUptResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
