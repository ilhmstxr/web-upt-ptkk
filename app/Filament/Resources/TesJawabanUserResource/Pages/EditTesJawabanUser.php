<?php

namespace App\Filament\Resources\TesJawabanUserResource\Pages;

use App\Filament\Resources\TesJawabanUserResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTesJawabanUser extends EditRecord
{
    protected static string $resource = TesJawabanUserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
