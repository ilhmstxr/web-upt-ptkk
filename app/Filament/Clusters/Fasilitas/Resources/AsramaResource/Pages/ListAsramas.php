<?php

namespace App\Filament\Clusters\Fasilitas\Resources\AsramaResource\Pages;

use App\Filament\Clusters\Fasilitas\Resources\AsramaResource;
use App\Services\AsramaConfigSyncService;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAsramas extends ListRecords
{
    protected static string $resource = AsramaResource::class;

    public function mount(): void
    {
        parent::mount();

        app(AsramaConfigSyncService::class)->syncFromConfig();
    }

    // ✅ Heading muncul di atas list
    public function getHeading(): string
    {
        return 'Fasilitas Asrama';
    }

    // ✅ Deskripsi/subheading muncul walau tabel kosong
    public function getSubheading(): ?string
    {
        return 'Ringkasan kapasitas, jumlah kamar, total bed, serta kondisi kamar tiap asrama. '
            . 'Deskripsi dihitung otomatis dari config kamar.php dan data kamar di database.';
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Tambah Asrama'),
        ];
    }
}



