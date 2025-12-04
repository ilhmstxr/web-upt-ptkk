<?php

namespace App\Filament\Widgets;

use App\Models\PendaftaranPelatihan;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class PerformaPesertaTableWidget extends BaseWidget
{
    
    // Judul Widget
    protected static ?string $heading = 'Top Nilai Terbaik Per Peserta';
    
    // Set kolom agar widget tidak terlalu tinggi
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                // Mengambil data dari PendaftaranPelatihan, diurutkan berdasarkan rata-rata nilai
                PendaftaranPelatihan::query()
                    ->with('peserta') // Load relasi
                    ->orderBy('rata_rata', 'desc')
                    ->limit(5) // Tampilkan 5 terbaik
            )
            ->columns([
                TextColumn::make('peserta.kode_peserta') // Asumsi 'kode_peserta' ada di model Peserta
                    ->label('KODE PESERTA')
                    ->searchable(),

                TextColumn::make('peserta.nama')
                    ->label('NAMA PESERTA')
                    ->searchable(),
                
                TextColumn::make('bidang.nama_bidang') // Asumsi 'nama_bidang' ada di model Bidang
                    ->label('BIDANG'),

                TextColumn::make('rata_rata')
                    ->label('RATA-RATA NILAI')
                    ->numeric()
                    ->alignRight()
                    ->sortable(),

                TextColumn::make('nilai_pre_test')
                    ->label('NILAI PRE-TEST')
                    ->numeric()
                    ->alignRight()
                    ->color('warning') // Sesuai gambar
                    ->sortable(),

                TextColumn::make('nilai_post_test')
                    ->label('NILAI POST-TEST')
                    ->numeric()
                    ->alignRight()
                    ->color('success') // Sesuai gambar
                    ->sortable(),

                TextColumn::make('nilai_praktek')
                    ->label('NILAI PRAKTEK (Opsional)')
                    ->numeric()
                    ->alignRight()
                    ->placeholder('(jika ada)'), // Tampilkan placeholder jika null
            ])
            ->paginated(false); // Sembunyikan paginasi
    }
}
