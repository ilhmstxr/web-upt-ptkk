<?php

namespace App\Filament\Clusters\KontenWebsite\Resources\KontenProgramPelatihanResource\Pages;

use App\Filament\Clusters\KontenWebsite\Resources\KontenProgramPelatihanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListKontenProgramPelatihans extends ListRecords
{
    protected static string $resource = KontenProgramPelatihanResource::class;

    public function getHeader(): ?\Illuminate\Contracts\View\View
    {
        return view('filament.clusters.konten-website.components.resource-tabs', [
            'activeTab' => 'program_pelatihan'
        ]);
    }

    // ✅ Tombol create cuma dari sini (biar 1 aja)
    protected function getHeaderActions(): array
    {
        return [];
    }
}

