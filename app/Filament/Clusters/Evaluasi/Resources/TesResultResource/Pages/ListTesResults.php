<?php

namespace App\Filament\Clusters\Evaluasi\Resources\TesResultResource\Pages;

use App\Filament\Clusters\Evaluasi\Resources\TesResultResource;
use App\Filament\Clusters\Evaluasi\Resources\TesResultResource\Widgets\GlobalPelatihanChart;
use App\Filament\Clusters\Evaluasi\Resources\TesResultResource\Widgets\GlobalStatsOverview;
use Filament\Resources\Pages\ListRecords;

class ListTesResults extends ListRecords
{
    protected static string $resource = TesResultResource::class;
    protected static ?string $title = 'Statistik Evaluasi';

    public function getHeader(): ?\Illuminate\Contracts\View\View
    {
        return view('filament.clusters.evaluasi.components.resource-tabs', [
            'activeTab' => 'statistik'
        ]);
    }

    protected function getHeaderActions(): array
    {
        return [];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            GlobalStatsOverview::class,
            GlobalPelatihanChart::class,
        ];
    }
}
