<?php

namespace App\Filament\Clusters;

use Filament\Clusters\Cluster;

class KontenWebsite extends Cluster
{
    protected static ?string $navigationIcon = 'heroicon-o-globe-alt';

    // ✅ DITAMBAHKAN: Memberi label pada grup navigasi di sidebar utama
    protected static ?string $navigationLabel = 'Konten Website'; 
    
    // ✅ DITAMBAHKAN: Mengaktifkan registrasi Cluster di navigasi
    protected static bool $shouldRegisterNavigation = true;

    protected static ?int $navigationSort = 3;

    public static function getNavigationUrl(): string
    {
        return \App\Filament\Clusters\KontenWebsite\Resources\KepalaUptResource::getUrl('index');
    }
}