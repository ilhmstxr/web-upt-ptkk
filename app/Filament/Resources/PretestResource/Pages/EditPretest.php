<?php

namespace App\Filament\Resources\PretestResource\Pages;

use App\Filament\Resources\PretestResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPretest extends EditRecord
{
    protected static string $resource = PretestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
