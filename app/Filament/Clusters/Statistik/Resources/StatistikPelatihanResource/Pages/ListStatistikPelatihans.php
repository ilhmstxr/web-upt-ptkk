<?php

namespace App\Filament\Clusters\Statistik\Resources\StatistikPelatihanResource\Pages;

use App\Filament\Clusters\Statistik\Resources\StatistikPelatihanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListStatistikPelatihans extends ListRecords
{
    protected static string $resource = StatistikPelatihanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Tambah Foto Statistik'),
        ];
    }
}
