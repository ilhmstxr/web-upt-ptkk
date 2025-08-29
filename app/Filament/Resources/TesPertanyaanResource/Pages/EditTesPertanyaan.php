<?php

namespace App\Filament\Resources\TesPertanyaanResource\Pages;

use App\Filament\Resources\TesPertanyaanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTesPertanyaan extends EditRecord
{
    protected static string $resource = TesPertanyaanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
