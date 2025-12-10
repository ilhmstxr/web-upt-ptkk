<?php

namespace App\Filament\Clusters\KontenWebsite\Resources\SorotanPelatihanResource\Pages;

use App\Filament\Clusters\KontenWebsite\Resources\SorotanPelatihanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSorotanPelatihans extends ListRecords
{
    protected static string $resource = SorotanPelatihanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
