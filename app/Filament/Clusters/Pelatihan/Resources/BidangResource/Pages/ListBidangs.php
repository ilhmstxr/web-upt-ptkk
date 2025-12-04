<?php

namespace App\Filament\Clusters\Pelatihan\Resources\BidangResource\Pages;

use App\Filament\Clusters\Pelatihan\Resources\BidangResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Contracts\View\View;

class ListBidangs extends ListRecords
{
    protected static string $resource = BidangResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
    
    public function getHeader(): ?View
    {
        return view('filament.clusters.pelatihan.components.resource-tabs', [
            'activeTab' => 'bidangs'
        ]);
    }
}
