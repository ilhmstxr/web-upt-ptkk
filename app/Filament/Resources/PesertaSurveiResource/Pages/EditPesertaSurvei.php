<?php

namespace App\Filament\Resources\PesertaSurveiResource\Pages;

use App\Filament\Resources\PesertaSurveiResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPesertaSurvei extends EditRecord
{
    protected static string $resource = PesertaSurveiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
