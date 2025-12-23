<?php

namespace App\Filament\Clusters;

use Filament\Clusters\Cluster;
use Filament\Pages\SubNavigationPosition;
use Filament\Support\Enums\MaxWidth;

class Kesiswaan extends Cluster
{
    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?int $navigationSort = 5;

    public static function getNavigationUrl(): string
    {
        return \App\Filament\Clusters\Kesiswaan\Resources\InstansiResource::getUrl('index');
    }

    // jadi tampil sebagai 1 menu parent lalu children di dalamnya
    protected static ?string $navigationLabel = 'Kesiswaan';

     // sub-navigation cluster jadi TAB di atas,
    protected static SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;

    //bikin semua halaman dalam cluster ini full width
    public function getMaxContentWidth(): MaxWidth
    {
        return MaxWidth::Full;
    }
}
