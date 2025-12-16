<?php

namespace App\Filament\Widgets\Dashboard;

use Filament\Widgets\Widget;
use App\Models\Pelatihan;
use App\Models\PenempatanAsrama;
use App\Models\Kamar;
use Illuminate\Support\Facades\DB;

class InformasiAsramaWidget extends Widget
{
    protected static ?int $sort = 4;
    protected int|string|array $columnSpan = 1;
    protected static string $view = 'filament.widgets.informasi-asrama-widget';
    protected static bool $isLazy = false;

    // Livewire public properties
    public int $male = 0;
    public int $female = 0;
    public int $empty = 0;
    public int $percent = 0;

    // ✅ WAJIB public biar selalu tersedia di Blade saat Livewire update
    public string $currentTrainingName = 'Memuat Data...';

    public function mount(): void
    {
        $this->loadData();
    }

    // ✅ opsional tapi bagus: memastikan data konsisten saat re-render
    public function hydrate(): void
    {
        // kalau kamu merasa berat, bisa dihapus. Tapi ini bikin aman saat livewire/update.
        // $this->loadData();
    }

    private function loadData(): void
    {
        // 1) Pelatihan aktif
        $activeTraining = Pelatihan::query()
            ->where('status', 'aktif')
            ->latest('tanggal_mulai')
            ->first();

        // 2) Penghuni aktif
        $placements = PenempatanAsrama::query()
            ->penghuniAktif()
            ->with(['peserta'])
            ->get();

        $occupied = $placements->count();

        $this->male = $placements->filter(function ($placement) {
            $jk = $placement->peserta?->jenis_kelamin;
            return in_array($jk, ['Laki-laki', 'L', 'Pria'], true);
        })->count();

        $this->female = $placements->filter(function ($placement) {
            $jk = $placement->peserta?->jenis_kelamin;
            return in_array($jk, ['Perempuan', 'P', 'Wanita'], true);
        })->count();

        // 3) Kapasitas total
        $totalCapacity = (int) Kamar::sum('total_beds');

        $this->empty = $totalCapacity - $occupied;
        if ($this->empty < 0) {
            $this->empty = 0;
        }

        $this->percent = $totalCapacity > 0
            ? (int) round(($occupied / $totalCapacity) * 100)
            : 0;

        // ✅ judul selalu ada
        $this->currentTrainingName = $activeTraining
            ? (string) $activeTraining->nama_pelatihan
            : 'Tidak ada pelatihan aktif';
    }
}
