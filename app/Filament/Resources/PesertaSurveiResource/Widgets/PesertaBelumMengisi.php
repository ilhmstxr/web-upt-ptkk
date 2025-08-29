<?php

namespace App\Filament\Resources\PesertaSurveiResource\Widgets;

use App\Filament\Resources\PesertaSurveiResource;
use App\Models\Pelatihan;
use App\Models\Peserta;
use App\Models\PesertaSurvei;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class PesertaBelumMengisi extends BaseWidget
{
    // Atur judul widget
    protected static ?string $heading = 'Daftar Peserta Belum Mengisi Survei';

    protected static bool $shouldRegisterNavigation = false; // Sembunyikan dari menu

    public ?Pelatihan $pelatihan = null;

    // Atur seberapa banyak data yang ditampilkan per halaman
    protected static ?int $defaultSortColumnDirection = 5;

    public function table(Table $table): Table
    {
        return $table
            ->query(function () {
                // 2. Ambil ID pelatihan saat ini
                $pelatihanId = $this->pelatihan?->id;

                // 3. Dapatkan daftar email peserta yang SUDAH mengisi survei
                $emailPesertaSudahMengisi = PesertaSurvei::where('pelatihan_id', $pelatihanId)
                    ->pluck('email')
                    ->all();

                // 4. Kembalikan query utama:
                // Cari Peserta di pelatihan ini yang emailnya TIDAK ADA di daftar di atas
                return Peserta::query()
                    ->where('pelatihan_id', $pelatihanId)
                    ->whereNotIn('email', $emailPesertaSudahMengisi);
            })
            ->columns([
                Tables\Columns\TextColumn::make('nama')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('email'),
                Tables\Columns\TextColumn::make('bidang.nama_bidang'),
            ]);
    }
}
