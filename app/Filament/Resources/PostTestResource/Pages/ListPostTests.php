<?php

namespace App\Filament\Resources\PostTestResource\Pages;

use App\Filament\Resources\PostTestResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPostTests extends ListRecords
{
    protected static string $resource = PostTestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
