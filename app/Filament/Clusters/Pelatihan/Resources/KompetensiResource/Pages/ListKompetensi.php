<?php

namespace App\Filament\Clusters\Pelatihan\Resources\KompetensiResource\Pages;

use App\Filament\Clusters\Pelatihan\Resources\KompetensiResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Contracts\View\View;

class ListKompetensi extends ListRecords
{
    protected static string $resource = KompetensiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
    
    public function getHeader(): ?View
    {
        return view('filament.clusters.pelatihan.components.resource-tabs', [
            'activeTab' => 'kompetensi'
        ]);
    }
}
