<?php

namespace App\Filament\Clusters\Pelatihan\Resources\PelatihanResource\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Pelatihan;
use App\Models\PendaftaranPelatihan;
use App\Models\Percobaan;

class PelatihanStatsOverview extends StatsOverviewWidget
{
    public ?Pelatihan $record = null;

    protected function getStats(): array
    {
        $pelatihanId = $this->record->id;

        // Peserta pelatihan ini
        $totalPeserta = PendaftaranPelatihan::where('pelatihan_id', $pelatihanId)->count();

        // Pre & Post test
        $avgPre = Percobaan::where('pelatihan_id', $pelatihanId)
            ->where('tipe', 'pretest')
            ->avg('skor') ?? 0;

        $avgPost = Percobaan::where('pelatihan_id', $pelatihanId)
            ->where('tipe', 'posttest')
            ->avg('skor') ?? 0;

        // Kelulusan
        $lulus = Percobaan::where('pelatihan_id', $pelatihanId)
            ->where('tipe', 'posttest')
            ->where('lulus', true)
            ->count();

        return [
            Stat::make('Total Peserta', $totalPeserta)
                ->icon('heroicon-o-users'),

            Stat::make('Rata-rata Pre-Test', number_format($avgPre, 1))
                ->icon('heroicon-o-academic-cap')
                ->color('warning'),

            Stat::make('Rata-rata Post-Test', number_format($avgPost, 1))
                ->icon('heroicon-o-check-badge')
                ->color('success'),

            Stat::make('Peserta Lulus', $lulus)
                ->icon('heroicon-o-shield-check')
                ->color('success'),
        ];
    }
}
