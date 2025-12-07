<?php

namespace App\Filament\Clusters\Pelatihan\Resources\MateriPelatihanResource\Pages;

use App\Filament\Clusters\Pelatihan\Resources\MateriPelatihanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMateriPelatihans extends ListRecords
{
    protected static string $resource = MateriPelatihanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
