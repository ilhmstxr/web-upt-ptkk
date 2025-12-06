<?php

namespace App\Filament\Clusters\Evaluasi\Resources\TesResource\Pages;

use App\Filament\Clusters\Evaluasi\Resources\TesResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTes extends EditRecord
{
    protected static string $resource = TesResource::class;

    protected static string $view = 'filament.clusters.evaluasi.resources.tes-resource.pages.edit-tes';

    public function getMaxContentWidth(): ?string
    {
        return '7xl';
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
