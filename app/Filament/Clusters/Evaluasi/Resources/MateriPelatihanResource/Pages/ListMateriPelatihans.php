<?php

namespace App\Filament\Clusters\Evaluasi\Resources\MateriPelatihanResource\Pages;

use App\Filament\Clusters\Evaluasi\Resources\MateriPelatihanResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions;

class ListMateriPelatihans extends ListRecords
{
    protected static string $resource = MateriPelatihanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(), // ✅ tombol create muncul lagi
        ];
    }
}
