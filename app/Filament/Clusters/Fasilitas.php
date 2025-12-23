<?php

namespace App\Filament\Clusters;

use Filament\Clusters\Cluster;

class Fasilitas extends Cluster
{
    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';

    protected static ?int $navigationSort = 2;

    public static function getNavigationUrl(): string
    {
        return \App\Filament\Clusters\Fasilitas\Resources\AsramaResource::getUrl('index');
    }
}
