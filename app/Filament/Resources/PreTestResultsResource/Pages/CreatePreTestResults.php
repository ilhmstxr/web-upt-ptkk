<?php

namespace App\Filament\Resources\PreTestResultsResource\Pages;

use App\Filament\Resources\PreTestResultsResource;
use App\Models\PreTestResult;
use Filament\Resources\Pages\CreateRecord;

class CreatePreTestResults extends CreateRecord
{
    protected static string $resource = PreTestResultsResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Ambil field Repeater 'soal' jika ada
        $soal = $data['soal'] ?? [];
        unset($data['soal']); // hapus dulu supaya tidak tersimpan di tabel utama

        // Simpan record utama
        $record = parent::create($data);

        // Simpan semua data Repeater ke relasi (PreTestResultSoal)
        foreach ($soal as $item) {
            $record->soal()->create($item);
        }

        return $data;
    }
}
