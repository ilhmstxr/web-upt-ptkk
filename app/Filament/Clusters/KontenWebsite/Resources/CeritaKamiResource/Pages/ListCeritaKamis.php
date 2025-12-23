<?php

namespace App\Filament\Clusters\KontenWebsite\Resources\CeritaKamiResource\Pages;

use App\Filament\Clusters\KontenWebsite\Resources\CeritaKamiResource;
use App\Models\CeritaKami;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCeritaKamis extends ListRecords
{
    protected static string $resource = CeritaKamiResource::class;

    public function getHeader(): ?\Illuminate\Contracts\View\View
    {
        return view('filament.clusters.konten-website.components.resource-tabs', [
            'activeTab' => 'cerita_kami'
        ]);
    }

    protected function getHeaderActions(): array
    {
        // Kalau belum ada data sama sekali, tampilkan tombol Create
        if (CeritaKami::count() === 0) {
            // Note: The view handles the button now, but logic for hiding it might be needed there.
            // For now, I'll return empty here to avoid double buttons.
            return [];
        }

        // Kalau sudah ada 1 atau lebih, sembunyikan tombol Create
        return [];
    }
}
