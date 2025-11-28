<?php

namespace App\Filament\Clusters\Pelatihan\Resources\PelatihanResource\Pages;

use App\Filament\Clusters\Pelatihan\Resources\PelatihanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Contracts\View\View;

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
    
    public function getHeader(): ?View
    {
        return view('filament.clusters.pelatihan.components.resource-tabs', [
            'activeTab' => 'pelatihans'
        ]);
    }
}
