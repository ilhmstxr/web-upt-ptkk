<?php

namespace App\Filament\Clusters\Evaluasi\Resources\MateriPelatihanResource\Pages;

use App\Filament\Clusters\Evaluasi\Resources\MateriPelatihanResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions;

class ListMateriPelatihans extends ListRecords
{
    protected static string $resource = MateriPelatihanResource::class;

    public function getHeader(): ?\Illuminate\Contracts\View\View
    {
        return view('filament.clusters.evaluasi.components.resource-tabs', [
            'activeTab' => 'materi'
        ]);
    }

    protected function getHeaderActions(): array
    {
        return [];
    }
}
