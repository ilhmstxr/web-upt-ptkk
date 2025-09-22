<?php

namespace App\Filament\Resources\PesertaSurveiResource\Widgets;

use App\Models\Pelatihan;
use App\Models\Peserta;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\Facades\DB;

class PesertaBelumMengisi extends BaseWidget
{
    protected static ?string $heading = 'Daftar Peserta Belum Mengisi Survei';
    protected static bool $shouldRegisterNavigation = false;
    public ?Pelatihan $pelatihan = null;
    protected static ?int $defaultSortColumnDirection = 5;

    public function table(Table $table): Table
    {
        return $table
            ->query(function () {
                $pelatihanId = $this->pelatihan?->id;

                // --- Query yang Efisien dengan TRIM() dan LOWER() ---
                return Peserta::query()
                    ->select('peserta.*')
                    ->leftJoin('peserta_survei as ps', function ($join) use ($pelatihanId) {
                        $join
                            // Cocokkan nama (case-insensitive dan tanpa spasi ekstra)
                            ->on(DB::raw('LOWER(TRIM(peserta.nama))'), '=', DB::raw('LOWER(TRIM(ps.nama))'))
                            // ATAU cocokkan email (case-insensitive dan tanpa spasi ekstra)
                            ->orOn(DB::raw('LOWER(TRIM(peserta.email))'), '=', DB::raw('LOWER(TRIM(ps.email))'))
                            // Pastikan join hanya pada pelatihan yang sama
                            ->where('ps.pelatihan_id', '=', $pelatihanId);
                    })
                    // Filter utama: Hanya peserta dari pelatihan ini
                    ->where('peserta.pelatihan_id', $pelatihanId)
                    // Kunci Logika: Ambil hanya yang GAGAL join
                    ->whereNull('ps.id');
            })
            ->columns([
                Tables\Columns\TextColumn::make('nama')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('email'),
                Tables\Columns\TextColumn::make('bidang.nama_bidang'),
            ]);
    }
}
