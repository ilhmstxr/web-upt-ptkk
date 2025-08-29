<?php

namespace App\Filament\Resources\TesOpsiJawabanResource\Pages;

use App\Filament\Resources\TesOpsiJawabanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTesOpsiJawaban extends EditRecord
{
    protected static string $resource = TesOpsiJawabanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
