<?php

namespace App\Filament\Clusters\Pelatihan\Resources\PelatihanResource\Widgets;

use Filament\Widgets\Widget;
use App\Models\Pelatihan;
use App\Models\PendaftaranPelatihan; // Correct model

class PelatihanStatsOverview extends Widget
{
    protected static string $view = 'filament.clusters.pelatihan.resources.pelatihan-resource.widgets.pelatihan-stats-overview';

    protected function getViewData(): array
    {
        return [
            'totalPelatihan' => Pelatihan::count(),
            'sedangBerjalan' => Pelatihan::where('status', 'Sedang Berjalan')->count(), // Adjust status value as needed
            'totalPeserta' => PendaftaranPelatihan::count(), // Correct model usage
            'rataKelulusan' => 94, // Placeholder or calculate from data
        ];
    }
}
