<?php

namespace App\Filament\Clusters\Fasilitas\Resources\PenempatanAsramaResource\Pages;

use App\Filament\Clusters\Fasilitas\Resources\PenempatanAsramaResource;
use Filament\Resources\Pages\ListRecords;

class ListPenempatanAsramas extends ListRecords
{
    protected static string $resource = PenempatanAsramaResource::class;

    public function getHeader(): ?\Illuminate\Contracts\View\View
    {
        return view('filament.clusters.fasilitas.components.resource-tabs', [
            'activeTab' => 'penempatan'
        ]);
    }

    // kalau gak butuh header action tambahan, biarin kosong
    protected function getHeaderActions(): array
    {
        return [];
    }
}
