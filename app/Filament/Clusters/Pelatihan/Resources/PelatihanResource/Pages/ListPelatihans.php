<?php

namespace App\Filament\Clusters\Pelatihan\Resources\PelatihanResource\Pages;

use App\Filament\Clusters\Pelatihan\Resources\PelatihanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Contracts\View\View;

class ListPelatihans extends ListRecords
{
    protected static string $resource = PelatihanResource::class;

    protected static string $view = 'filament-panels::resources.pages.list-records';

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

    protected function getHeaderWidgets(): array
    {
        return [
            PelatihanResource\Widgets\PelatihanStatsOverview::class,
        ];
    }
    
    public function getHeader(): ?View
    {
        return view('filament.clusters.pelatihan.components.resource-tabs', [
            'activeTab' => 'pelatihans'
        ]);
    }
}
