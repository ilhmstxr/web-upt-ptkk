<?php

namespace App\Filament\Clusters\Fasilitas\Resources\AsramaResource\Pages;

use App\Filament\Clusters\Fasilitas\Resources\AsramaResource;
use Filament\Resources\Pages\CreateRecord;
use App\Models\Pelatihan;
use Illuminate\Database\Eloquent\Model;

class CreateAsrama extends CreateRecord
{
    protected static string $resource = AsramaResource::class;

    /**
     * Handle creation and then assign selected pelatihan to this asrama.
     *
     * @param  array  $data
     * @return \Illuminate\Database\Eloquent\Model
     */
    protected function handleRecordCreation(array $data): Model
    {
        // Ambil pelatihan_ids (virtual) lalu hapus agar tidak masuk ke fillable model
        $pelatihanIds = $data['pelatihan_ids'] ?? [];
        unset($data['pelatihan_ids']);

        // Buat record Asrama lewat parent
        $record = parent::handleRecordCreation($data);

        // Assign pelatihan yang dipilih: set asrama_id = $record->id
        if (!empty($pelatihanIds)) {
            Pelatihan::whereIn('id', $pelatihanIds)
                ->update(['asrama_id' => $record->id]);
        }

        return $record;
    }
}
