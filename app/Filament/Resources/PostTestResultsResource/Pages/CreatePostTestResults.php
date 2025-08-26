<?php

namespace App\Filament\Resources\PostTestResultsResource\Pages;

use App\Filament\Resources\PostTestResultsResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePostTestResults extends CreateRecord
{
    protected static string $resource = PostTestResultsResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Ambil field Repeater 'soal' jika ada
        $soal = $data['soal'] ?? [];
        unset($data['soal']); // hapus dulu supaya tidak tersimpan di tabel utama

        // Simpan record utama
        $record = parent::create($data);

        // Simpan semua data Repeater ke relasi
        foreach ($soal as $item) {
            $record->soal()->create($item);
        }

        return $data;
    }
}
