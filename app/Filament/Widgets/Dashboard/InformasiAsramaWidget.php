<?php

namespace App\Filament\Widgets\Dashboard; // Sesuaikan namespace jika Anda mengubah path folder

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
        // Panggilan ke penghuniAktif() diasumsikan sebagai Query Scope
        $placements = \App\Models\PenempatanAsrama::penghuniAktif()
            ->with(['pendaftaranPelatihan.peserta'])
            ->get();

        $occupied = $placements->count();
        
        // Filter Laki-laki
        $male = $placements->filter(function ($placement) {
            $jk = $placement->pendaftaranPelatihan?->peserta?->jenis_kelamin;
            return in_array($jk, ['Laki-laki', 'L', 'Pria']);
        })->count();
        
        // Filter Perempuan
        $female = $placements->filter(function ($placement) {
            $jk = $placement->pendaftaranPelatihan?->peserta?->jenis_kelamin;
            return in_array($jk, ['Perempuan', 'P', 'Wanita']);
        })->count();
        
        // 3. Kapasitas Total
        $totalCapacity = \App\Models\Kamar::sum('total_beds');
        
        // Hitung Kamar Kosong (pastikan hasilnya tidak negatif)
        $empty = $totalCapacity - $occupied;
        if ($empty < 0) $empty = 0;
        
        // Hitung Persentase
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