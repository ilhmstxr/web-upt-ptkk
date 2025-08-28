<?php

namespace App\Filament\Resources\PretestResource\Pages;

use App\Filament\Resources\PretestResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPretests extends ListRecords
{
    protected static string $resource = PretestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
