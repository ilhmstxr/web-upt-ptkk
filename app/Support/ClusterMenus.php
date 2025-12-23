<?php

namespace App\Support;

use App\Filament\Clusters\Fasilitas\Resources\AsramaResource;
use App\Filament\Clusters\Fasilitas\Resources\PenempatanAsramaResource;
use App\Filament\Clusters\KontenWebsite\Resources\KepalaUptResource;
use App\Filament\Clusters\KontenWebsite\Resources\KontenProgramPelatihanResource;
use App\Filament\Clusters\KontenWebsite\Resources\SorotanPelatihanResource;
use App\Filament\Clusters\KontenWebsite\Resources\BannerResource;
use App\Filament\Clusters\KontenWebsite\Resources\BeritaResource;
use App\Filament\Clusters\KontenWebsite\Resources\CeritaKamiResource;
use App\Filament\Clusters\Kesiswaan\Resources\InstansiResource;
use App\Filament\Clusters\Kesiswaan\Resources\InstrukturResource;
use App\Filament\Clusters\Kesiswaan\Resources\PendaftaranResource;
use App\Filament\Clusters\Evaluasi\Resources\TesResource;
use App\Filament\Clusters\Evaluasi\Resources\TesResultResource;
use App\Filament\Clusters\Evaluasi\Resources\MateriPelatihanResource;

class ClusterMenus
{
    public static function getFasilitasMenu(): array
    {
        return [
            [
                'label' => 'Asramas',
                'url' => AsramaResource::getUrl(),
                'icon' => 'heroicon-o-home-modern',
                'active' => request()->routeIs(AsramaResource::getRouteBaseName() . '.*'),
            ],
            [
                'label' => 'Penempatan Asrama',
                'url' => PenempatanAsramaResource::getUrl(),
                'icon' => 'heroicon-o-home',
                'active' => request()->routeIs(PenempatanAsramaResource::getRouteBaseName() . '.*'),
            ],
        ];
    }

    public static function getKontenWebsiteMenu(): array
    {
        return [
            [
                'label' => 'Kepala UPT',
                'url' => KepalaUptResource::getUrl(),
                'icon' => 'heroicon-o-user',
                'active' => request()->routeIs(KepalaUptResource::getRouteBaseName() . '.*'),
            ],
            [
                'label' => 'Program Pelatihan',
                'url' => KontenProgramPelatihanResource::getUrl(),
                'icon' => 'heroicon-o-photo',
                'active' => request()->routeIs(KontenProgramPelatihanResource::getRouteBaseName() . '.*'),
            ],
            [
                'label' => 'Sorotan Pelatihan (4-8 Foto)',
                'url' => SorotanPelatihanResource::getUrl(),
                'icon' => 'heroicon-o-camera',
                'active' => request()->routeIs(SorotanPelatihanResource::getRouteBaseName() . '.*'),
            ],
            [
                'label' => 'Banner',
                'url' => BannerResource::getUrl(),
                'icon' => 'heroicon-o-photo',
                'active' => request()->routeIs(BannerResource::getRouteBaseName() . '.*'),
            ],
            [
                'label' => 'Berita',
                'url' => BeritaResource::getUrl(),
                'icon' => 'heroicon-o-document-text',
                'active' => request()->routeIs(BeritaResource::getRouteBaseName() . '.*'),
            ],
            [
                'label' => 'Cerita Kami',
                'url' => CeritaKamiResource::getUrl(),
                'icon' => 'heroicon-o-book-open',
                'active' => request()->routeIs(CeritaKamiResource::getRouteBaseName() . '.*'),
            ],
        ];
    }

    public static function getKesiswaanMenu(): array
    {
        return [
            [
                'label' => 'Data Instansi',
                'url' => InstansiResource::getUrl(),
                'icon' => 'heroicon-o-building-office',
                'active' => request()->routeIs(InstansiResource::getRouteBaseName() . '.*'),
            ],
            [
                'label' => 'Instrukturs',
                'url' => InstrukturResource::getUrl(),
                'icon' => 'heroicon-o-academic-cap',
                'active' => request()->routeIs(InstrukturResource::getRouteBaseName() . '.*'),
            ],
            [
                'label' => 'Peserta',
                'url' => PendaftaranResource::getUrl(),
                'icon' => 'heroicon-o-users',
                'active' => request()->routeIs(PendaftaranResource::getRouteBaseName() . '.*'),
            ],
        ];
    }

    public static function getEvaluasiMenu(): array
    {
        return [
            [
                'label' => 'Tes',
                'url' => TesResource::getUrl(),
                'icon' => 'heroicon-o-clipboard-document-list',
                'active' => request()->routeIs(TesResource::getRouteBaseName() . '.*'),
            ],
            [
                'label' => 'Statistik',
                'url' => TesResultResource::getUrl(),
                'icon' => 'heroicon-o-chart-bar',
                'active' => request()->routeIs(TesResultResource::getRouteBaseName() . '.*'),
            ],
            [
                'label' => 'Materi Pelatihan',
                'url' => MateriPelatihanResource::getUrl(),
                'icon' => 'heroicon-o-book-open',
                'active' => request()->routeIs(MateriPelatihanResource::getRouteBaseName() . '.*'),
            ],
        ];
    }
}
