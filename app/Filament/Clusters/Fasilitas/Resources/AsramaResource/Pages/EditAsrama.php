<?php

namespace App\Filament\Clusters\Fasilitas\Resources\AsramaResource\Pages;

use App\Filament\Clusters\Fasilitas\Resources\AsramaResource;
use Filament\Resources\Pages\EditRecord;
use App\Models\Pelatihan;
use Illuminate\Database\Eloquent\Model;

class EditAsrama extends EditRecord
{
    protected static string $resource = AsramaResource::class;

    /**
     * Signature harus sesuai parent: (Model $record, array $data)
     *
     * @param  \Illuminate\Database\Eloquent\Model  $record
     * @param  array  $data
     * @return \Illuminate\Database\Eloquent\Model
     */
    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        // Ambil pelatihan_ids (virtual) lalu hapus agar tidak masuk ke fillable model
        $pelatihanIds = $data['pelatihan_ids'] ?? null;
        unset($data['pelatihan_ids']);

        // Lakukan update record asrama via parent
        $updatedRecord = parent::handleRecordUpdate($record, $data);

        if (is_array($pelatihanIds)) {
            // 1) Lepaskan pelatihan yang sebelumnya terassign ke asrama ini tapi sekarang tidak dipilih
            Pelatihan::where('asrama_id', $updatedRecord->id)
                ->whereNotIn('id', $pelatihanIds)
                ->update(['asrama_id' => null]);

            // 2) Assign pelatihan yang dipilih (bisa sebelumnya NULL atau sudah terassign ke asrama ini)
            Pelatihan::whereIn('id', $pelatihanIds)
                ->update(['asrama_id' => $updatedRecord->id]);
        }

        return $updatedRecord;
    }

    /**
     * Isi nilai form sebelum tampil di Edit form supaya checkbox yang sudah terassign tampil checked.
     *
     * @param  array  $data
     * @return array
     */
    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['pelatihan_ids'] = $this->record->pelatihans()->pluck('id')->toArray();

        return $data;
    }
}
