<?php

namespace App\Filament\Clusters\Fasilitas\Resources\PenempatanAsramaResource\Pages;

use App\Filament\Clusters\Fasilitas\Resources\PenempatanAsramaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPenempatanAsrama extends EditRecord
{
    protected static string $resource = PenempatanAsramaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
