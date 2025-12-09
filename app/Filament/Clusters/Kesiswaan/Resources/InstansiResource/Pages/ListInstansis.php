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

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->slideOver(),
        ];
    }
}
