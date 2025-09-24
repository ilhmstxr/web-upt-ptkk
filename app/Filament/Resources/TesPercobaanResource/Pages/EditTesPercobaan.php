<?php

namespace App\Filament\Resources\TesPercobaanResource\Pages;

use App\Filament\Resources\TesPercobaanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTesPercobaan extends EditRecord
{
    protected static string $resource = TesPercobaanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
