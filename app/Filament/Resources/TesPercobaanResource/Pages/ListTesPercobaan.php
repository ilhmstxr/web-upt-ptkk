<?php

namespace App\Filament\Resources\TesPercobaanResource\Pages;

use App\Filament\Resources\TesPercobaanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTesPercobaan extends ListRecords
{
    protected static string $resource = TesPercobaanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
