<?php

namespace App\Filament\Widgets;

use App\Models\Pelatihan;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class PelatihanAktifTable extends BaseWidget
{
    // Mengatur widget ini agar menempati 2 kolom (lebar penuh)
    protected int | string | array $columnSpan = 2;

    protected static ?int $sort = 2; // Urutan widget di dashboard

    public function table(Table $table): Table
    {
        return $table
            ->heading('Daftar Pelatihan Aktif & Mendatang')
            ->query(
                // Query disederhanakan, fokus pada pengurutan
                Pelatihan::query()
                    // Urutan: 1. Aktif, 2. Belum Mulai, 3. Selesai
                    ->orderByRaw("
                        CASE
                            WHEN NOW() BETWEEN tanggal_mulai AND tanggal_selesai THEN 1
                            WHEN tanggal_mulai > NOW() THEN 2
                            ELSE 3
                        END
                    ")
                    // Untuk pelatihan mendatang, urutkan berdasarkan yang paling dekat
                    ->orderBy('tanggal_mulai', 'asc')
            )
            ->columns([
                Tables\Columns\TextColumn::make('nama_pelatihan')
                    ->label('Nama Pelatihan')
                    ->searchable()
                    ->limit(30),

                Tables\Columns\TextColumn::make('program')
                    ->badge(),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->label('Status')
                    // Memanggil accessor 'status' dari model Pelatihan
                    ->color(fn(string $state): string => match ($state) {
                        'Aktif' => 'success',
                        'Mendatang' => 'warning',
                        'Selesai' => 'gray',
                    }),
                
                Tables\Columns\TextColumn::make('tanggal_mulai')
                    ->date('d M Y')
                    ->label('Jadwal'),

            ])
            ->paginated(false) // Menghilangkan paginasi agar terlihat seperti daftar
            ->actions([
                // Tambahkan action jika perlu, misal lihat detail
            ]);
    }
}
