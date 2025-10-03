<?php

namespace App\Filament\Widgets;

use App\Models\Bidang;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class BidangSummaryTable extends BaseWidget
{
    protected static ?int $sort = 2; // Tampil di urutan kedua

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Bidang::query()
                    ->select('nama_bidang')
                    ->selectSub(function ($query) {
                        $query->from('pesertas')
                            ->join('tes', 'tes.peserta_id', '=', 'pesertas.id')
                            ->join('percobaans', 'percobaans.tes_id', '=', 'tes.id')
                            ->whereColumn('pesertas.bidang_id', 'bidangs.id')
                            ->where('tes.jenis_tes', 'pre-test')
                            ->selectRaw('AVG(percobaans.nilai)');
                    }, 'avg_pre_test')
                    ->selectSub(function ($query) {
                        $query->from('pesertas')
                            ->join('tes', 'tes.peserta_id', '=', 'pesertas.id')
                            ->join('percobaans', 'percobaans.tes_id', '=', 'tes.id')
                            ->whereColumn('pesertas.bidang_id', 'bidangs.id')
                            ->where('tes.jenis_tes', 'post-test')
                            ->selectRaw('AVG(percobaans.nilai)');
                    }, 'avg_post_test')
                    ->selectSub(function ($query) {
                        $query->from('pesertas')
                            ->join('tes', 'tes.peserta_id', '=', 'pesertas.id')
                            ->join('percobaans', 'percobaans.tes_id', '=', 'tes.id')
                            ->whereColumn('pesertas.bidang_id', 'bidangs.id')
                            ->where('tes.jenis_tes', 'praktek')
                            ->selectRaw('AVG(percobaans.nilai)');
                    }, 'avg_praktek')
            )
            ->heading('Akumulasi Data per Bidang')
            ->columns([
                TextColumn::make('nama_bidang')->label('Nama Program Keahlian'),
                TextColumn::make('avg_pre_test')->label('Rata-Rata PRE')->numeric(2),
                TextColumn::make('avg_post_test')->label('Rata-Rata POST')->numeric(2),
                TextColumn::make('avg_praktek')->label('Rata-Rata PRAKTEK')->numeric(2),
            ])
            ->paginated(false);
    }
}
