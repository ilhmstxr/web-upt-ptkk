<?php

namespace App\Filament\Resources\TesPercobaanResource\Pages;

use App\Filament\Resources\TesPercobaanResource;
use Filament\Resources\Pages\Page;

class DashboardTesPercobaan extends Page
{
    protected static string $resource = TesPercobaanResource::class;
    protected static string $view = 'filament.resources.tes-percobaan-resource.pages.dashboard-tes-percobaan';

    public function mount(): void
    {
        // Pastikan hanya role atasan / kepala / admin boleh melihat
        if (!auth()->user()->hasAnyRole(['atasan','kepala','admin'])) {
            abort(403);
        }
    }
}
