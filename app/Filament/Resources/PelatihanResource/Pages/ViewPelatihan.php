<?php

namespace App\Filament\Resources\PelatihanResource\Pages;

use App\Filament\Resources\PelatihanResource;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components;

// --- Impor Widget yang AKAN ANDA BUAT ---
// (Saat ini kita hanya mendaftarkan contoh PengaturanTesWidget)
use App\Filament\Widgets\Custom\PengaturanTesWidget;
// use App\Filament\Widgets\Custom\BidangVenueWidget; // (Anda buat nanti)
// use App\Filament\Widgets\Custom\InformasiAsramaWidget; // (Anda buat nanti)
// use App\Filament\Widgets\Stats\StatistikPesertaWidget; // (Anda buat nanti)
// use App\Filament\Widgets\Stats\StatistikInstrukturWidget; // (Anda buat nanti)
// use App\Filament\Widgets\Charts\LaporanSurveiChartWidget; // (Anda buat nanti)
// use App\Filament\Widgets\Tables\LaporanNilaiTableWidget; // (Anda buat nanti)


class ViewPelatihan extends ViewRecord
{
    protected static string $resource = PelatihanResource::class;
    protected static string $view = 'filament.resources.pelatihan-resource.pages.view-pelatihan';

    // Setel judul halaman sesuai nama pelatihan
    public function getTitle(): string
    {
        return $this->record->nama . ' (' . $this->record->status . ')';
    }

    /**
     * Override fungsi ini untuk menggunakan getHeaderWidgets() dan getFooterWidgets()
     * sebagai "slot" untuk menempatkan widget di dalam tab.
     * * Ini adalah cara untuk menggabungkan Infolist (Tabs) dengan Widget.
     */
    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->record($this->record)
            ->schema([
                Components\Tabs::make('Tabs')
                    ->columnSpan('full')
                    ->tabs([
                        
                        // TAB 1: RINGKASAN & TINDAKAN
                        Components\Tabs\Tab::make('Ringkasan & Tindakan')
                            ->icon('heroicon-o-sparkles')
                            ->schema([
                                // Slot untuk widget
                                Components\Grid::make(3)->schema([
                                    // Daftarkan widget untuk tab ini
                                    // Kita gunakan getHeaderWidgets() sebagai slot
                                    Components\Livewire::make(static::class, ['record' => $this->record])
                                        ->lazy()
                                        ->columnSpanFull(),
                                ]),
                            ]),
                        
                        // TAB 2: PESERTA & INSTRUKTUR
                        Components\Tabs\Tab::make('Peserta & Instruktur')
                            ->icon('heroicon-o-users')
                            ->schema([
                                // Daftarkan widget untuk tab ini
                                // Kita gunakan getFooterWidgets() sebagai slot
                                Components\Livewire::make(static::class, ['record' => $this->record])
                                    ->lazy()
                                    ->columnSpanFull(),
                            ]),

                        // TAB 3: LAPORAN NILAI
                        Components\Tabs\Tab::make('Laporan Nilai')
                            ->icon('heroicon-o-academic-cap')
                            ->schema([
                                // Daftarkan widget untuk tab ini
                                // Anda bisa tambahkan slot lain atau widget langsung
                                // Components\Livewire::make(LaporanNilaiTableWidget::class, ['record' => $this->record])->lazy(),
                            ]),

                        // TAB 4: LAPORAN SURVEI
                        Components\Tabs\Tab::make('Laporan Survei')
                            ->icon('heroicon-o-chart-pie')
                            ->schema([
                                // Daftarkan widget untuk tab ini
                                // Components\Livewire::make(LaporanSurveiChartWidget::class, ['record' => $this->record])->lazy(),
                            ]),
                    ]),
            ]);
    }

    /**
     * Widget untuk TAB 1: "Ringkasan & Tindakan"
     */
    protected function getHeaderWidgets(): array
    {
        // Hanya tampilkan widget ini jika tab aktif adalah 'Ringkasan & Tindakan'
        if ($this->activeTab !== 'Ringkasan & Tindakan') {
            return [];
        }

        return [
            PengaturanTesWidget::class,
            // BidangVenueWidget::class, // (Buat ini selanjutnya)
            // InformasiAsramaWidget::class, // (Buat ini selanjutnya)
        ];
    }

    /**
     * Widget untuk TAB 2: "Peserta & Instruktur"
     */
    protected function getFooterWidgets(): array
    {
        // Hanya tampilkan widget ini jika tab aktif adalah 'Peserta & Instruktur'
        if ($this->activeTab !== 'Peserta & Instruktur') {
            return [];
        }

        return [
            // StatistikPesertaWidget::class, // (Buat ini selanjutnya)
            // StatistikInstrukturWidget::class, // (Buat ini selanjutnya)
        ];
    }
}
