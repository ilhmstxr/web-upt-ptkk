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
        // 1. Pelatihan Aktif (Cari yang status aktif dan tanggal masuk range)
        $activeTraining = \App\Models\Pelatihan::where('status', 'aktif')
             ->latest('tanggal_mulai')
             ->first();

        // 2. Penghuni Aktif (Occupancy)
        // Gunakan scopePenghuniAktif dari model PenempatanAsrama
        $placements = \App\Models\PenempatanAsrama::penghuniAktif()
            ->with(['pendaftaranPelatihan.peserta'])
            ->get();

        $occupied = $placements->count();
        $male = $placements->filter(function ($placement) {
            $jk = $placement->pendaftaranPelatihan?->peserta?->jenis_kelamin;
            return in_array($jk, ['Laki-laki', 'L', 'Pria']);
        })->count();
        
        $female = $placements->filter(function ($placement) {
            $jk = $placement->pendaftaranPelatihan?->peserta?->jenis_kelamin;
            return in_array($jk, ['Perempuan', 'P', 'Wanita']);
        })->count();
        
        // 3. Kapasitas
        $totalCapacity = \App\Models\Kamar::sum('total_beds');
        $empty = $totalCapacity - $occupied;
        if ($empty < 0) $empty = 0;
        
        // Percent
        $percent = $totalCapacity > 0 ? round(($occupied / $totalCapacity) * 100) : 0;

        return [
            'activeTrainingName' => $activeTraining ? $activeTraining->nama_pelatihan : 'Tidak ada pelatihan aktif',
            'occupied' => $occupied,
            'male' => $male,
            'female' => $female,
            'empty' => $empty,
            'percent' => $percent,
        ];
    }
}