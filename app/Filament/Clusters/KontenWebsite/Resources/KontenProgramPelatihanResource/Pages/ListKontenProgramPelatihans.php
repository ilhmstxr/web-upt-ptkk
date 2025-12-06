<?php

namespace App\Filament\Clusters\KontenWebsite\Resources\KontenProgramPelatihanResource\Pages;

use App\Filament\Clusters\KontenWebsite\Resources\KontenProgramPelatihanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListKontenProgramPelatihans extends ListRecords
{
    protected static string $resource = KontenProgramPelatihanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
