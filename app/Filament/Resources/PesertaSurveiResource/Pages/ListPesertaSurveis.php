<?php

namespace App\Filament\Resources\PesertaSurveiResource\Pages;

use App\Filament\Resources\PesertaSurveiResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPesertaSurveis extends ListRecords
{
    protected static string $resource = PesertaSurveiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
