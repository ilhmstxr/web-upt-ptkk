<?php

namespace App\Filament\Clusters\Kesiswaan\Resources\InstrukturResource\Pages;

use App\Filament\Clusters\Kesiswaan\Resources\InstrukturResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditInstruktur extends EditRecord
{
    protected static string $resource = InstrukturResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
