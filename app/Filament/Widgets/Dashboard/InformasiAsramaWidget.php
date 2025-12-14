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

    // Properti private untuk nilai non-reaktif (Dikirim via getViewData untuk menghindari error Livewire)
    private string $currentTrainingNameValue = 'Memuat Data...';

    protected static bool $isLazy = false;

    public function mount(): void
    {
        // 1. Pelatihan Aktif (untuk judul widget)
        $activeTraining = Pelatihan::where('status', 'aktif')
            ->latest('tanggal_mulai')
            ->first();

        if (! $activeTraining) {
            $this->currentTrainingNameValue = 'Tidak ada pelatihan aktif';
            $this->empty = \App\Models\Kamar::sum('total_beds');
            return;
        }

        // 2. Penghuni Aktif (Occupancy)
        $placements = PenempatanAsrama::query()
            ->penghuniAktif()
            ->with(['pendaftaranPelatihan.peserta'])
            ->get();

        $occupied = $placements->count();

        // Filter Laki-laki
        $this->male = $placements->filter(function ($placement) {
            $jk = $placement->pendaftaranPelatihan?->peserta?->jenis_kelamin;
            return in_array($jk, ['Laki-laki', 'L', 'Pria'], true);
        })->count();

        // Filter Perempuan
        $this->female = $placements->filter(function ($placement) {
            $jk = $placement->pendaftaranPelatihan?->peserta?->jenis_kelamin;
            return in_array($jk, ['Perempuan', 'P', 'Wanita'], true);
        })->count();

        // 3. Kapasitas Total
        $totalCapacity = Kamar::sum('total_beds');

        // Hitung Kamar Kosong
        $this->empty = $totalCapacity - $occupied;
        if ($this->empty < 0) $this->empty = 0;

        // Hitung Persentase
        $this->percent = $totalCapacity > 0 ? round(($occupied / $totalCapacity) * 100) : 0;

        // ✅ judul selalu ada
        $this->currentTrainingName = $activeTraining
            ? (string) $activeTraining->nama_pelatihan
            : 'Tidak ada pelatihan aktif';
    }
}
