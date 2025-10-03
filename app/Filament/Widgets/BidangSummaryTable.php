<?php

namespace App\Filament\Widgets;

use App\Models\Bidang;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class BidangSummaryTable extends BaseWidget
{
    protected static ?int $sort = 1; // Menampilkan widget ini di atas
    protected int | string | array $columnSpan = 'full';

    public function getTableHeading(): string
    {
        return 'Akumulasi Data per Bidang';
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(function () {
                // Query dasar untuk mengambil data bidang beserta rata-rata nilainya
                return Bidang::query()
                    ->select('bidangs.*') // Ambil semua kolom dari tabel bidang
                    // Subquery untuk menghitung rata-rata Pre-Test
                    ->selectSub(function ($query) {
                        $query->from('pesertas')
                            ->join('tes', 'tes.peserta_id', '=', 'pesertas.id')
                            ->join('percobaans', 'percobaans.tes_id', '=', 'tes.id')
                            ->whereColumn('pesertas.bidang_id', 'bidangs.id')
                            ->where('tes.jenis_tes', 'pre-test')
                            ->selectRaw('AVG(percobaans.nilai)');
                    }, 'avg_pre_test')
                    // Subquery untuk menghitung rata-rata Post-Test
                    ->selectSub(function ($query) {
                        $query->from('pesertas')
                            ->join('tes', 'tes.peserta_id', '=', 'pesertas.id')
                            ->join('percobaans', 'percobaans.tes_id', '=', 'tes.id')
                            ->whereColumn('pesertas.bidang_id', 'bidangs.id')
                            ->where('tes.jenis_tes', 'post-test')
                            ->selectRaw('AVG(percobaans.nilai)');
                    }, 'avg_post_test')
                    // Subquery untuk menghitung rata-rata Praktek
                    ->selectSub(function ($query) {
                        $query->from('pesertas')
                            ->join('tes', 'tes.peserta_id', '=', 'pesertas.id')
                            ->join('percobaans', 'percobaans.tes_id', '=', 'tes.id')
                            ->whereColumn('pesertas.bidang_id', 'bidangs.id')
                            ->where('tes.jenis_tes', 'praktek')
                            ->selectRaw('AVG(percobaans.nilai)');
                    }, 'avg_praktek');
            })
            ->columns([
                Tables\Columns\TextColumn::make('nama_bidang')
                    ->label('Nama Program Keahlian'),
                Tables\Columns\TextColumn::make('avg_pre_test')
                    ->label('Rata-Rata PRE')
                    ->numeric(2), // Format 2 angka desimal
                Tables\Columns\TextColumn::make('avg_post_test')
                    ->label('Rata-Rata POST')
                    ->numeric(2),
                Tables\Columns\TextColumn::make('avg_praktek')
                    ->label('Rata-Rata PRAKTEK')
                    ->numeric(2),
            ])
            ->paginated(false);
    }
}
