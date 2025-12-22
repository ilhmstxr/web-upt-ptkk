<?php

namespace App\Filament\Widgets;


use Filament\Tables;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\Facades\DB;

class PerformaKompetensiTableWidget extends BaseWidget
{
    protected static ?string $heading = 'Analisis Detail Per Kompetensi Pelatihan';
    protected static ?int $sort = 7;

    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                \App\Models\Kompetensi::query()
                    ->from('kompetensi as k')
                    ->join('kompetensi_pelatihan as kp', 'kp.kompetensi_id', '=', 'k.id')
                    ->join('pendaftaran_pelatihan as pp', 'pp.kompetensi_pelatihan_id', '=', 'kp.id')
                    ->select([
                        'k.id',
                        'k.nama_kompetensi',
                        DB::raw('COUNT(pp.id) as jumlah_peserta'),
                        DB::raw('ROUND(AVG(pp.nilai_pre_test), 1) as pre_avg'),
                        DB::raw('ROUND(AVG(pp.nilai_post_test), 1) as post_avg'),
                    ])
                    ->groupBy('k.id', 'k.nama_kompetensi')
                    ->orderBy('k.nama_kompetensi')
                    ->limit(5)
            )
            ->headerActions([
                Tables\Actions\Action::make('Lihat Semua')
                    ->url('#') // Ganti dengan URL resource yang sesuai, misal: KompetensiResource::getUrl('index')
                    ->button()
                    ->color('gray')
                    ->size('xs'),
            ])
            ->paginated(false)
            ->columns([
                TextColumn::make('nama_kompetensi')
                    ->label('KOMPETENSI PELATIHAN'),

                TextColumn::make('jumlah_peserta')
                    ->label('JUMLAH PESERTA')
                    ->numeric()
                    ->badge(), // Tampilkan sebagai badge biru



                TextColumn::make('pre_avg')
                    ->label('PRE-TEST (RATA2)')
                    ->numeric()->alignRight()->color('warning')
                    ->formatStateUsing(fn($state): string => number_format((float) $state, 1)), // Format 1 angka desimal



                TextColumn::make('post_avg')
                    ->label('POST-TEST (RATA2)')
                    ->numeric()->alignRight()->color('success')
                    ->formatStateUsing(fn($state): string => number_format((float) $state, 1)),

                // --- KOLOM PENINGKATAN ---
                TextColumn::make('peningkatan')
                    ->label('% PENINGKATAN')
                    ->getStateUsing(function (\App\Models\Kompetensi $record): ?float {
                        $avgPre = (float) ($record->pre_avg ?? 0);
                        $avgPost = (float) ($record->post_avg ?? 0);

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
                    ->getStateUsing(function (\App\Models\Kompetensi $record): string {
                        // Kalkulasi ulang % peningkatan
                        $avgPre = (float) ($record->pre_avg ?? 0);
                        $avgPost = (float) ($record->post_avg ?? 0);
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
