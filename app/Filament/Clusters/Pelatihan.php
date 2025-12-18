<?php

namespace App\Filament\Clusters;

use Filament\Clusters\Cluster;

class Pelatihan extends Cluster
{
    protected static ?string $navigationIcon = 'heroicon-o-squares-2x2';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationLabel = 'Akademik & Pelatihan';

    // Enable sub-navigation to show resources as tabs
    protected static bool $shouldRegisterNavigation = true;

    // Redirect to Kompetensi resource when cluster is clicked
    public static function getNavigationUrl(): string
    {
        return \App\Filament\Clusters\Pelatihan\Resources\PelatihanResource::getUrl('index');
    }
}
