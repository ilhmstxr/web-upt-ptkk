<?php

namespace App\Filament\Clusters\Evaluasi\Resources\MateriResource\Pages;

use App\Filament\Clusters\Evaluasi\Resources\MateriResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMateri extends EditRecord
{
    protected static string $resource = MateriResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
