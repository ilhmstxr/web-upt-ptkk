<?php

namespace App\Filament\Clusters\Pelatihan\Resources\KompetensiResource\Pages;

use App\Filament\Clusters\Pelatihan\Resources\KompetensiResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditKompetensi extends EditRecord
{
    protected static string $resource = KompetensiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    public function getBreadcrumbs(): array
    {
        return [
            KompetensiResource::getUrl('index') => 'Kompetensi',
            '#' => $this->record->nama_kompetensi,
            '' => 'Edit',
        ];
    }
}
