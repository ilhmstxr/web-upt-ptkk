<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class InformasiAsramaWidget extends Widget
{
    protected static ?int $sort = 4;
    protected int | string | array $columnSpan = 1;
    protected static string $view = 'filament.widgets.informasi-asrama-widget';

    protected function getViewData(): array
    {
        // Ambil pelatihan yang sedang aktif (status 'Aktif' dan tanggal sekarang dalam rentang)
        $activeTraining = \App\Models\Pelatihan::where('status', 'Aktif')
            ->whereDate('tanggal_mulai', '<=', now())
            ->whereDate('tanggal_selesai', '>=', now())
            ->latest('tanggal_mulai')
            ->first();

        return [
            'activeTrainingName' => $activeTraining ? $activeTraining->nama_pelatihan : 'Tidak ada pelatihan aktif',
        ];
    }
}