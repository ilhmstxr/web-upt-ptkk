<?php

namespace App\Filament\Pages;

use App\Filament\Resources\JawabanSurveiResource\Widgets\BuildsLikertData;
use App\Filament\Widgets\DynamicStatsOverviewWidget;
use App\Filament\Widgets\DynamicTableWidget;
use App\Filament\Widgets\GlobalStatsOverview;
use Filament\Pages\Page;

class TestWidgetPage extends Page
{
    use BuildsLikertData;

    protected static ?string $navigationIcon = 'heroicon-o-breaker';
    // protected static ?string $navigationGroup = 'Hasil Kegiatan';
    // protected static ?string $navigationLabel = 'test widget';

    protected static string $view = 'filament.pages.test-widget-page';

    // 2. Ini adalah route yang Anda tentukan tadi
    protected static ?string $slug = 'test-widget';

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
    public function getHeaderWidgets(): array
    {
        // $pid = 1;
        // $testStats = $this->bidang();
        // $testStats = $this->peserta($pid);
        // $testStats = $this->instruktur($pid);
        // $testStats = $this->getPesertaTableConfig();
        $testStats = $this->getTopNilaiTableConfig();
        return [
            // DynamicStatsOverviewWidget::make([
            //     'stats' => $testStats
            // ]),
            // DynamicTableWidget::make($testStats)
            // DynamicTableWidget::make([
            //     'stats' => $testStats
            // ])
        ];
    }
}
