<?php

namespace App\Filament\Clusters\Pelatihan\Resources\PelatihanResource\Pages;

use App\Filament\Clusters\Pelatihan\Resources\PelatihanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPelatihans extends ListRecords
{
    protected static string $resource = PelatihanResource::class;

    protected static string $view = 'filament.clusters.pelatihan.resources.pelatihan-resource.pages.list-pelatihans';

    public function getPaginator()
    {
        return $this->paginateTableQuery($this->getFilteredTableQuery());
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
