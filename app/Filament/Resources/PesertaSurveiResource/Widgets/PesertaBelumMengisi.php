<?php

namespace App\Filament\Resources\PesertaSurveiResource\Widgets;

use App\Filament\Resources\PesertaSurveiResource;
use App\Models\Pelatihan;
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
            // 2. Ubah query agar dinamis berdasarkan pelatihan yang dipilih
            ->query(
                PesertaSurvei::query()
                    ->where('pelatihan_id', $this->pelatihan?->id)
                    ->whereDoesntHave('percobaans')
            )
            ->columns([
                Tables\Columns\TextColumn::make('nama')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('email'), 
                Tables\Columns\TextColumn::make('bidang.nama_bidang'), 
                // Kolom pelatihan tidak perlu lagi karena sudah spesifik
                // Tables\Columns\TextColumn::make('pelatihan.nama_pelatihan')->label('Pelatihan'),
            ]);
    }
}
