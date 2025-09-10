<?php

namespace App\Filament\Resources\TesOpsiJawabanResource\Pages;

use App\Filament\Resources\TesOpsiJawabanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTesOpsiJawaban extends ListRecords
{
    protected static string $resource = TesOpsiJawabanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
