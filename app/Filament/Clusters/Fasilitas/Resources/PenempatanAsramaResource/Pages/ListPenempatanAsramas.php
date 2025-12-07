<?php

namespace App\Filament\Clusters\Fasilitas\Resources\PenempatanAsramaResource\Pages;

use App\Filament\Clusters\Fasilitas\Resources\PenempatanAsramaResource;
use Filament\Resources\Pages\ListRecords;

class ListPenempatanAsramas extends ListRecords
{
    protected static string $resource = PenempatanAsramaResource::class;

    protected function getHeaderActions(): array
    {
        // header actions sudah didefinisikan di Resource (bagian table->headerActions)
        return [];
    }
}
