<?php

namespace App\Filament\Clusters;

use Filament\Clusters\Cluster;

class Evaluasi extends Cluster
{
    protected static ?string $navigationIcon = 'heroicon-o-squares-2x2';
    protected static ?int $navigationSort = 4;

    public static function getNavigationUrl(): string
    {
        return \App\Filament\Clusters\Evaluasi\Resources\TesResource::getUrl('index');
    }
}
