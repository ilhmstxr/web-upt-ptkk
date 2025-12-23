<?php

namespace App\Filament\Clusters\Evaluasi\Resources\TesResource\Pages;

use App\Filament\Clusters\Evaluasi\Resources\TesResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTes extends ListRecords
{
    protected static string $resource = TesResource::class;

    public function getHeader(): ?\Illuminate\Contracts\View\View
    {
        return view('filament.clusters.evaluasi.components.resource-tabs', [
            'activeTab' => 'tes'
        ]);
    }

    protected function getHeaderActions(): array
    {
        return [];
    }
}
