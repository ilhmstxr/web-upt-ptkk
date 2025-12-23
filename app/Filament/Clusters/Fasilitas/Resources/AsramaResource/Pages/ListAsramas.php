<?php

namespace App\Filament\Clusters\Fasilitas\Resources\AsramaResource\Pages;

use App\Filament\Clusters\Fasilitas\Resources\AsramaResource;
use App\Services\AsramaConfigSyncService;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAsramas extends ListRecords
{
    protected static string $resource = AsramaResource::class;

    public function mount(): void
    {
        parent::mount();

        app(AsramaConfigSyncService::class)->syncFromConfig();
    }

    public function getHeader(): ?\Illuminate\Contracts\View\View
    {
        return view('filament.clusters.fasilitas.components.resource-tabs', [
            'activeTab' => 'asramas'
        ]);
    }
}



