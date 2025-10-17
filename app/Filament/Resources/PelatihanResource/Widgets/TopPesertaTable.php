<?php

namespace App\Filament\Widgets;

use App\Models\Peserta;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TopPesertaTable extends BaseWidget
{
    protected static ?string $heading = 'Top Nilai Terbaik Per Peserta';
    public ?Model $record = null;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                // Query kompleks untuk join dan kalkulasi rata-rata
                Peserta::query()
                    ->where('pelatihan_id', $this->record->id)
                    ->with(['penilaianTes', 'bidang'])
                    ->select('pesertas.*') // Pastikan select kolom dari tabel utama
                    ->selectSub(function ($query) {
                        $query->from('penilaian_tes')
                            ->whereColumn('peserta_id', 'pesertas.id')
                            ->selectRaw('AVG(nilai)');
                    }, 'rata_rata_nilai')
                    ->orderBy('rata_rata_nilai', 'desc')
                    ->limit(10)
            )
            ->columns([
                Tables\Columns\TextColumn::make('kode_peserta'),
                Tables\Columns\TextColumn::make('nama_peserta'),
                Tables\Columns\TextColumn::make('bidang.nama_bidang')->label('Bidang'),
                Tables\Columns\TextColumn::make('rata_rata_nilai')->numeric(2),
                // Kolom untuk pre-test dan post-test bisa dibuat dengan custom logic
                Tables\Columns\TextColumn::make('pre_test_score')->label('Nilai Pre-Test')->getStateUsing(fn(Peserta $record) => $record->penilaianTes->where('jenis_tes', 'pre-test')->first()?->nilai ?? '-'),
                Tables\Columns\TextColumn::make('post_test_score')->label('Nilai Post-Test')->getStateUsing(fn(Peserta $record) => $record->penilaianTes->where('jenis_tes', 'post-test')->first()?->nilai ?? '-'),
            ]);
    }
}