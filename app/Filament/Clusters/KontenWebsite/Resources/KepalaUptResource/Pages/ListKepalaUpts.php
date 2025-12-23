<?php

namespace App\Filament\Clusters\KontenWebsite\Resources\KepalaUptResource\Pages;

use App\Filament\Clusters\KontenWebsite\Resources\KepalaUptResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListKepalaUpts extends ListRecords
{
    protected static string $resource = KepalaUptResource::class;

    public function getHeader(): ?\Illuminate\Contracts\View\View
    {
        return view('filament.clusters.konten-website.components.resource-tabs', [
            'activeTab' => 'kepala_upt'
        ]);
    }

    protected function getHeaderActions(): array
    {
        return [];
    }
}
