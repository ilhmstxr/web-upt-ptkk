<?php

namespace App\Filament\Clusters\KontenWebsite\Resources\CeritaKamiResource\Pages;

use App\Filament\Clusters\KontenWebsite\Resources\CeritaKamiResource;
use App\Models\CeritaKami;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCeritaKamis extends ListRecords
{
    protected static string $resource = CeritaKamiResource::class;

    protected function getHeaderActions(): array
    {
        // Kalau belum ada data sama sekali, tampilkan tombol Create
        if (CeritaKami::count() === 0) {
            return [
                Actions\CreateAction::make(),
            ];
        }

        // Kalau sudah ada 1 atau lebih, sembunyikan tombol Create
        return [];
    }
}
