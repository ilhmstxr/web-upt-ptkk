<?php

namespace App\Filament\Clusters\Fasilitas\Resources\AsramaResource\Pages;

use App\Filament\Clusters\Fasilitas\Resources\AsramaResource;
use Filament\Resources\Pages\EditRecord;

class EditAsrama extends EditRecord
{
    protected static string $resource = AsramaResource::class;

    /**
     * Karena relasi Asrama -> Pelatihan itu BELONGS TO,
     * cukup isi pelatihan_id langsung, tidak pakai pelatihan_ids.
     */
    protected function mutateFormDataBeforeFill(array $data): array
    {
        // pastikan field form kamu memang bernama "pelatihan_id"
        $data['pelatihan_id'] = $this->record->pelatihan_id;

        return $data;
    }
}
