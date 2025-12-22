<?php

namespace App\Filament\Widgets;

use App\Models\Pelatihan;
use Filament\Widgets\Widget;
use Illuminate\Database\Eloquent\Collection;

class DaftarPelatihanWidget extends Widget
{
    protected static ?int $sort = 1;

    // 1. Tentukan file view Blade yang akan digunakan
    protected static string $view = 'filament.widgets.daftar-pelatihan-widget';

    // 2. Set agar widget mengambil lebar penuh (opsional)
    protected int | string | array $columnSpan = 'full';

    // 3. Buat properti untuk menyimpan data
    public ?Collection $pelatihan;

    // 4. Ambil data saat widget di-load
    public function mount(): void
    {
        $this->pelatihan = Pelatihan::whereIn('status', ['aktif', 'mendatang'])
            ->orderBy('tanggal_mulai', 'asc')
            ->take(3) // Ambil 3 data teratas seperti di gambar
            ->get();
    }
}
