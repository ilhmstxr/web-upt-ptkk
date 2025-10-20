<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\GlobalStatsOverview;
use Filament\Pages\Page;

class TestWidgetPage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-breaker';
    // protected static ?string $navigationGroup = 'Hasil Kegiatan';
    // protected static ?string $navigationLabel = 'test widget';

    protected static string $view = 'filament.pages.test-widget-page';

    // 2. Ini adalah route yang Anda tentukan tadi
    protected static ?string $slug = 'test-widget-1';

    // 3. Sembunyikan dari navigasi (opsional, tapi bagus untuk tes)
    protected static bool $shouldRegisterNavigation = false;

    // 4. Atur judul halaman
    public function getTitle(): string
    {
        return 'Halaman Tes - Global Stats';
    }

    /**
     * Daftarkan HANYA widget yang ingin Anda tes di sini.
     *
     * @return array<class-string<\Filament\Widgets\Widget>>
     */
    public function getWidgets(): array
    {
        return [
            GlobalStatsOverview::class,
            // true
        ];
    }
}
