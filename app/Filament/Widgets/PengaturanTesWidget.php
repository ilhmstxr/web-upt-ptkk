<?php

// Lokasi: app/Filament/Widgets/Custom/PengaturanTesWidget.php

namespace App\Filament\Widgets\Custom;

use Filament\Widgets\Widget;
use Filament\Contracts\HasRecord; // Penting untuk mendapatkan data pelatihan

class PengaturanTesWidget extends Widget 
{
    // Gunakan trait ini untuk mengaktifkan $this->record
    // use \Filament\Widgets\Concerns\InteractsWithRecord; 

    // Tentukan file view blade-nya
    protected static string $view = 'filament.widgets.custom.pengaturan-tes-widget';

    // Properti untuk menyimpan data yang akan dikirim ke view
    public $preTestData = [];
    public $postTestData = [];
    public $surveiData = [];

    // Agar widget ini mengambil lebar penuh
    protected int | string | array $columnSpan = 'full';

    public function mount(): void
    {
        // $this->record sekarang berisi data Pelatihan yang sedang dibuka
        // GANTI INI DENGAN LOGIKA ANDA untuk mengambil data tes dari database
        // =================================================================
        
        // Contoh data statis (sesuai screenshot)
        $this->preTestData = [
            'status' => 'BELUM', 
            'soal' => 0, 
            'url' => '#', 
            'label' => 'Atur Pertanyaan'
        ];

        $this->postTestData = [
            'status' => 'DIBUKA', 
            'soal' => 50, 
            'url' => '#', 
            'label' => 'Kelola Soal'
        ];

        $this->surveiData = [
            'status' => 'DITUTUP', 
            'pertanyaan' => 12, 
            'url' => '#', 
            'label' => 'Lihat Hasil'
        ];
        // =================================================================
    }
}

