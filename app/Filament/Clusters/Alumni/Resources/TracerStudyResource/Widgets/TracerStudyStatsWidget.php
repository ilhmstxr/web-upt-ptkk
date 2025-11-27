<?php

namespace App\Filament\Clusters\Alumni\Resources\TracerStudyResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\TracerStudy;

class TracerStudyStatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $totalAlumni = TracerStudy::count();
        $avgWaitingPeriod = TracerStudy::avg('waiting_period');
        $employedCount = TracerStudy::whereIn('status', ['Bekerja', 'Wirausaha'])->count();
        $employedRate = $totalAlumni > 0 ? round(($employedCount / $totalAlumni) * 100, 1) : 0;

        return [
            Stat::make('Total Alumni Terdata', $totalAlumni),
            Stat::make('Rata-rata Masa Tunggu', number_format($avgWaitingPeriod, 1) . ' Bulan')
                ->description('Waktu tunggu mendapatkan pekerjaan'),
            Stat::make('Tingkat Kebekerjaan', $employedRate . '%')
                ->description('Bekerja & Wirausaha')
                ->color('success'),
        ];
    }
}
