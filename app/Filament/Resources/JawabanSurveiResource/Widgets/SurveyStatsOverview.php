<?php

namespace App\Filament\Resources\JawabanSurveiResource\Widgets;

use App\Models\JawabanUser;
use App\Models\PesertaSurvei;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class SurveyStatsOverview extends BaseWidget
{
    protected static ?string $pollingInterval = '30s';

    protected function getStats(): array
    {
        $totalResponden = PesertaSurvei::count();
        $totalJawaban = JawabanUser::count();
        $rataRataJawaban = $totalResponden > 0 ? number_format($totalJawaban / $totalResponden, 1) : 0;

        return [
            Stat::make('Total Responden', $totalResponden)
                ->description('Jumlah peserta yang mengisi survei')
                ->descriptionIcon('heroicon-m-users')
                ->color('success'), // <-- Hapus .loadingIndicator() dari sini

            Stat::make('Total Jawaban Diterima', $totalJawaban)
                ->description('Jumlah seluruh jawaban dari semua responden')
                ->descriptionIcon('heroicon-m-chat-bubble-left-right')
                ->color('info'), // <-- Hapus .loadingIndicator() dari sini

            Stat::make('Rata-rata Jawaban per Responden', $rataRataJawaban)
                ->description('Rata-rata jumlah pertanyaan yang dijawab')
                ->descriptionIcon('heroicon-m-calculator')
                ->color('warning'), // <-- Hapus .loadingIndicator() dari sini
        ];
    }
}
