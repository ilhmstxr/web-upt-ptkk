<?php

namespace App\Filament\Clusters\KontenWebsite\Resources\BannerResource\Pages;

use App\Filament\Clusters\KontenWebsite\Resources\BannerResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBanners extends ListRecords
{
    protected static string $resource = BannerResource::class;

    public function getHeader(): ?\Illuminate\Contracts\View\View
    {
        return view('filament.clusters.konten-website.components.resource-tabs', [
            'activeTab' => 'banner'
        ]);
    }

    protected function getHeaderActions(): array
    {
        return [];
    }
}
