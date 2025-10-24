<?php

// Lokasi: app/Filament/Pages/DetailPelatihan.php

namespace App\Filament\Pages;

// use App\Filament\Widgets\Custom\BidangVenueWidget;
// use App\Filament\Widgets\Custom\InformasiAsramaWidget;

use App\Filament\Widgets\BidangVenueWidget;
use App\Filament\Widgets\Custom\PengaturanTesWidget;
use App\Filament\Widgets\InformasiAsramaWidget;
use App\Models\Pelatihan;
use Filament\Pages\Page;
use Filament\Resources\Concerns\HasTabs; // <-- Import HasTabs
use Filament\Infolists\Components\Tabs\Tab; // <-- IMPORT YANG BENAR (SESUAI SCREENSHOT ANDA)

class DetailPelatihan extends Page
{
    use HasTabs; // <-- Gunakan Trait HasTabs

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    
    // Sembunyikan dari navigasi utama
    protected static bool $shouldRegisterNavigation = false; 
    protected static ?string $navigationLabel = 'Detail Dashboard Pelatihan';
    protected static string $view = 'filament.pages.detail-pelatihan';

    // Properti untuk menyimpan data pelatihan yang sedang dibuka
    public ?Pelatihan $pelatihan;

    /**
     * mount() akan mengambil ID dari URL, mencari data,
     * dan menyiapkannya untuk widget.
     * * @param int|string $recordId ID Pelatihan dari URL
     */
    public function mount($recordId): void
    {
        // Cari data Pelatihan berdasarkan ID dari URL
        // Pastikan Anda mengimpor App\Models\Pelatihan
        $this->pelatihan = Pelatihan::findOrFail($recordId);
        
        // Set judul halaman secara dinamis
        $this->title = $this->pelatihan->nama;
    }

    /**
     * Tentukan route untuk halaman ini.
     * URL-nya akan menjadi /detail-pelatihan/{recordId}
     */
    protected static string $routePath = 'detail-pelatihan/{recordId}';

    /**
     * Definisikan Tabs Anda di sini.
     */
     public function getTabs(): array
    {
        return [
            'ringkasan' => Tab::make('Ringkasan & Tindakan') 
                ->icon('heroicon-o-sparkles')
                ->widgets([
                    // HAPUS ->columnSpanFull() dari sini
                    PengaturanTesWidget::make(['pelatihan' => $this->pelatihan]),
                    
                    // HAPUS ->columnSpan(1) dari sini
                    BidangVenueWidget::make(['pelatihan' => $this->pelatihan]),
                    
                    // HAPUS ->columnSpan(1) dari sini
                    InformasiAsramaWidget::make(['pelatihan' => $this->pelatihan]),
                ]),

            'peserta' => Tab::make('Peserta & Instruktur') 
                ->icon('heroicon-o-users')
                ->widgets([
                    // (Widget Fase 3 Anda akan didaftarkan di sini)
                ]),

            'laporan_nilai' => Tab::make('Laporan Nilai') 
                ->icon('heroicon-o-academic-cap')
                ->widgets([
                    // (Widget Fase 4 Anda akan didaftarkan di sini)
                ]),

            'laporan_survei' => Tab::make('Laporan Survei') 
                ->icon('heroicon-o-chart-pie')
                ->widgets([
                    // (Widget Fase 5 Anda akan didaftarkan di sini)
                ]),
        ];
    }
    
    // Fungsi ini PENTING untuk membuat grid 2 kolom
    public function getColumns(): int | array
    {
        return 2;
    }
}

