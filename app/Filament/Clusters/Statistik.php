<?php

namespace App\Filament\Clusters;

use Filament\Clusters\Cluster;

class Statistik extends Cluster
{
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';

    protected static ?string $navigationLabel = 'Statistik';

    protected static bool $shouldRegisterNavigation = true;

    protected static ?int $navigationSort = 7;
}
