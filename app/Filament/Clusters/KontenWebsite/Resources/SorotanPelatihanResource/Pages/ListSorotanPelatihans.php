<?php

namespace App\Filament\Clusters\KontenWebsite\Resources\SorotanPelatihanResource\Pages;

use App\Filament\Clusters\KontenWebsite\Resources\SorotanPelatihanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSorotanPelatihans extends ListRecords
{
    protected static string $resource = SorotanPelatihanResource::class;

    public function getHeader(): ?\Illuminate\Contracts\View\View
    {
        return view('filament.clusters.konten-website.components.resource-tabs', [
            'activeTab' => 'sorotan'
        ]);
    }

    protected function getHeaderActions(): array
    {
        return [];
    }
}
