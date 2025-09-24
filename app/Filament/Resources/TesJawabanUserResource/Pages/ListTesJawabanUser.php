<?php

namespace App\Filament\Resources\TesJawabanUserResource\Pages;

use App\Filament\Resources\TesJawabanUserResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTesJawabanUser extends ListRecords
{
    protected static string $resource = TesJawabanUserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
