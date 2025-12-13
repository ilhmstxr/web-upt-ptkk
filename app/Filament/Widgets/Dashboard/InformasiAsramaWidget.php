<?php

namespace App\Filament\Widgets\Dashboard;

use Filament\Widgets\Widget;
use App\Models\Pelatihan;
use App\Models\PenempatanAsrama;
use App\Models\Kamar;
use Illuminate\Support\Collection;

class InformasiAsramaWidget extends Widget
{
    protected static ?int $sort = 4;
    protected int | string | array $columnSpan = 1;
    protected static string $view = 'filament.widgets.informasi-asrama-widget';

    // 🔥 KOREKSI 1: Definisikan data sebagai Properti Livewire Publik
    // Ini adalah solusi paling robust untuk menghindari 'Undefined variable' di view.
    public int $male = 0;
    public int $female = 0;
    public int $empty = 0;
    public int $percent = 0;
    public string $activeTrainingName = 'Memuat Data...';
    
    // Tetap statis untuk properti kelas induk
    protected static bool $isLazy = false; 

    public function mount(): void
    {
        $activeTraining = Pelatihan::where('status', 'aktif')
             ->latest('tanggal_mulai')
             ->first();
        $placements = PenempatanAsrama::query()
            ->penghuniAktif()
            ->with(['pendaftaranPelatihan.peserta'])
            ->get();

        $occupied = $placements->count();
        
        // Filter Laki-laki
        $this->male = $placements->filter(function ($placement) {
            $jk = $placement->pendaftaranPelatihan?->peserta?->jenis_kelamin;
            return in_array($jk, ['Laki-laki', 'L', 'Pria']);
        })->count();
        
        // Filter Perempuan
        $this->female = $placements->filter(function ($placement) {
            $jk = $placement->pendaftaranPelatihan?->peserta?->jenis_kelamin;
            return in_array($jk, ['Perempuan', 'P', 'Wanita']);
        })->count();
        
        // 3. Kapasitas Total
        $totalCapacity = Kamar::sum('total_beds');
        
        // Hitung Kamar Kosong
        $this->empty = $totalCapacity - $occupied;
        if ($this->empty < 0) $this->empty = 0;
        
        // Hitung Persentase
        $this->percent = $totalCapacity > 0 ? round(($occupied / $totalCapacity) * 100) : 0;

        $this->activeTrainingName = $activeTraining ? $activeTraining->nama_pelatihan : 'Tidak ada pelatihan aktif';
    }

    protected function getViewData(): array
    {
        return [];
    }
}