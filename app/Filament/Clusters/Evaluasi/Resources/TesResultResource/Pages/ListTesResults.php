<?php

namespace App\Filament\Clusters\Evaluasi\Resources\TesResultResource\Pages;

use App\Filament\Clusters\Evaluasi\Resources\TesResultResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTesResults extends ListRecords
{
    protected static string $resource = TesResultResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
