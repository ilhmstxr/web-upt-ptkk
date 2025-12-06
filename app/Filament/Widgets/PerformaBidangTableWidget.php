<?php

namespace App\Filament\Widgets;

use App\Models\Bidang;
use Filament\Tables;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class PerformaBidangTableWidget extends BaseWidget
{
    protected static ?string $heading = 'Analisis Detail Per Bidang Pelatihan';
    protected static ?int $sort = 6;

    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                // Kita query model Bidang dan melakukan kalkulasi agregat
                // Ini membutuhkan relasi `pendaftaranPelatihan()` di model `Bidang`
                Bidang::query()
                    ->with('bidangPelatihan.pendaftaranPelatihan') // Menghitung 'JUMLAH PESERTA'
                    // ->withAvg('bidangPelatihan.pendaftaranPelatihan', 'nilai_pre_test')  // Menghitung 'PRE-TEST (RATA²)'
                    // ->withMin('bidangPelatihan.pendaftaranPelatihan', 'nilai_pre_test')  // Menghitung 'PRE-TEST (MIN)'
                    // ->withMax('bidangPelatihan.pendaftaranPelatihan', 'nilai_pre_test')  // Menghitung 'PRE-TEST (MAX)'
                    // ->withAvg('bidangPelatihan.pendaftaranPelatihan', 'nilai_post_test') // Menghitung 'POST-TEST (RATA²)'
                    // ->withMin('bidangPelatihan.pendaftaranPelatihan', 'nilai_post_test') // Menghitung 'POST-TEST (MIN)'
                    // ->withMin('bidangPelatihan.pendaftaranPelatihan', 'nilai_post_test') // Menghitung 'POST-TEST (MIN)'
                    // ->withMax('bidangPelatihan.pendaftaranPelatihan', 'nilai_post_test') // Menghitung 'POST-TEST (MAX)'
                    ->limit(5)
            )
            ->headerActions([
                Tables\Actions\Action::make('Lihat Semua')
                    ->url('#') // Ganti dengan URL resource yang sesuai, misal: BidangResource::getUrl('index')
                    ->button()
                    ->color('gray')
                    ->size('xs'),
            ])
            ->paginated(false)
            ->columns([
                TextColumn::make('nama_bidang')
                    ->label('BIDANG PELATIHAN'),

                TextColumn::make('pendaftaran_pelatihan_count')
                    ->label('JUMLAH PESERTA')
                    ->numeric()
                    ->badge(), // Tampilkan sebagai badge biru



                TextColumn::make('pendaftaran_pelatihan_avg_nilai_pre_test')
                    ->label('PRE-TEST (RATA²)')
                    ->numeric()->alignRight()->color('warning')
                    ->formatStateUsing(fn(float $state): string => number_format($state, 1)), // Format 1 angka desimal



                TextColumn::make('pendaftaran_pelatihan_avg_nilai_post_test')
                    ->label('POST-TEST (RATA²)')
                    ->numeric()->alignRight()->color('success')
                    ->formatStateUsing(fn(float $state): string => number_format($state, 1)),

                // --- KOLOM PENINGKATAN ---
                TextColumn::make('peningkatan')
                    ->label('% PENINGKATAN')
                    ->getStateUsing(function (Bidang $record): ?float {
                        $avgPre = $record->pendaftaran_pelatihan_avg_nilai_pre_test;
                        $avgPost = $record->pendaftaran_pelatihan_avg_nilai_post_test;

                        if ($avgPre > 0) {
                            return (($avgPost - $avgPre) / $avgPre) * 100;
                        }
                        return 0; // Hindari pembagian dengan nol
                    })
                    ->formatStateUsing(fn(?float $state): string => number_format($state ?? 0, 1) . '%')
                    ->icon(fn(?float $state): string => $state >= 0 ? 'heroicon-s-arrow-trending-up' : 'heroicon-s-arrow-trending-down')
                    ->color(fn(?float $state): string => $state >= 0 ? 'success' : 'danger')
                    ->alignRight(),

                // --- KOLOM STATUS ---
                TextColumn::make('status')
                    ->label('STATUS')
                    ->getStateUsing(function (Bidang $record): string {
                        // Kalkulasi ulang % peningkatan
                        $avgPre = $record->pendaftaran_pelatihan_avg_nilai_pre_test;
                        $avgPost = $record->pendaftaran_pelatihan_avg_nilai_post_test;
                        $peningkatan = 0;
                        if ($avgPre > 0) {
                            $peningkatan = (($avgPost - $avgPre) / $avgPre) * 100;
                        }

                        if ($peningkatan > 30) return 'Sangat Efektif';
                        if ($peningkatan >= 0) return 'Efektif';
                        return 'Perlu Evaluasi';
                    })
                    ->colors([
                        'success' => 'Sangat Efektif',
                        'primary' => 'Efektif',
                        'danger' => 'Perlu Evaluasi',
                    ])->badge(),
            ]);
    }
}
