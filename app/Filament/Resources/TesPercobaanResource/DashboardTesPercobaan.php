<?php

namespace App\Filament\Resources\TesPercobaanResource\Pages;

use Filament\Resources\Pages\Page;
use Illuminate\Support\Facades\DB;

class DashboardTesPercobaan extends Page
{
    protected static string $resource = 'App\\Filament\\Resources\\TesPercobaanResource';

    protected static string $view = 'filament.resources.tes-percobaan.pages.dashboard';

    protected static ?string $title = 'Dashboard Tes Percobaan';

    public $data;

    public function mount()
    {
        // Ambil data agregat
        $this->data = [
            'total_pelatihan' => DB::table('pelatihan')->count(),
            'total_peserta'   => DB::table('peserta')->count(),
            'total_tes'       => DB::table('tes')->count(),
            'avg_skor'        => DB::table('percobaan')->avg('skor'),
        ];
    }
}
