<?php

namespace App\Filament\Clusters\KontenWebsite\Resources\BeritaResource\Pages;

use App\Filament\Clusters\KontenWebsite\Resources\BeritaResource; // ✅ Sudah ada
use Filament\Actions;// ❌ DUPLIKASI!
use Filament\Resources\Pages\ViewRecord; // ✅ Sudah benar

class ViewBerita extends ViewRecord
{
    protected static string $resource = BeritaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Tambahkan tombol untuk mengedit langsung dari halaman View
            Actions\EditAction::make(), 
        ];
    }
}