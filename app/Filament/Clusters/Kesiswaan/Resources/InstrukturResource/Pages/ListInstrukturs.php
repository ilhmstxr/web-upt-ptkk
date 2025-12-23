<?php

namespace App\Filament\Clusters\Kesiswaan\Resources\InstrukturResource\Pages;

use App\Filament\Clusters\Kesiswaan\Resources\InstrukturResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListInstrukturs extends ListRecords
{
    protected static string $resource = InstrukturResource::class;

    public function getHeader(): ?\Illuminate\Contracts\View\View
    {
        return view('filament.clusters.kesiswaan.components.resource-tabs', [
            'activeTab' => 'instruktur'
        ]);
    }

    protected function getHeaderActions(): array
    {
        return [];
    }
}
