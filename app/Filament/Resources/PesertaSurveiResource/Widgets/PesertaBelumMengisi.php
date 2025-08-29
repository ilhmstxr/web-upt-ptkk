<?php

namespace App\Filament\Resources\PesertaSurveiResource\Widgets;

use App\Filament\Resources\PesertaSurveiResource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class PesertaBelumMengisi extends BaseWidget
{
    // Atur judul widget
    protected static ?string $heading = 'Daftar Peserta Belum Mengisi Survei';

    // Atur seberapa banyak data yang ditampilkan per halaman
    protected static ?int $defaultSortColumnDirection = 5;

    public function table(Table $table): Table
    {
        return $table
            // ▼▼▼ UBAH QUERY DI SINI ▼▼▼
            ->query(
                PesertaSurveiResource::getEloquentQuery()->whereDoesntHave('percobaans')
            )
            ->columns([
                Tables\Columns\TextColumn::make('nama')->searchable(),
                Tables\Columns\TextColumn::make('email'),
                Tables\Columns\TextColumn::make('pelatihan.nama_pelatihan')->label('Pelatihan'),
            ]);
    }
}
