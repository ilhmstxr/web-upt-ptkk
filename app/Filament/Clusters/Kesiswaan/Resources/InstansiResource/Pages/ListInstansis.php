<?php

namespace App\Filament\Clusters\Kesiswaan\Resources\InstansiResource\Pages;

use App\Filament\Clusters\Kesiswaan\Resources\InstansiResource;
use App\Models\Instansi;
use Filament\Resources\Pages\ListRecords;
use Livewire\Attributes\Url;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Filament\Actions;

class ListInstansis extends ListRecords
{
    protected static string $resource = InstansiResource::class;

    public function getHeader(): ?\Illuminate\Contracts\View\View
    {
        return view('filament.clusters.kesiswaan.components.resource-tabs', [
            'activeTab' => 'instansi'
        ]);
    }

    protected function getHeaderActions(): array
    {
        return [];
    }
}
