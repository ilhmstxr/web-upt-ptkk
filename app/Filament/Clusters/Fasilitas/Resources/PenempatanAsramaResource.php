<?php
// app/Filament/Clusters/Fasilitas/Resources/PenempatanAsramaResource.php

namespace App\Filament\Clusters\Fasilitas\Resources;

use App\Filament\Clusters\Fasilitas;
use App\Filament\Clusters\Fasilitas\Resources\PenempatanAsramaResource\Pages;
use App\Models\PenempatanAsrama;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PenempatanAsramaResource extends Resource
{
    protected static ?string $model = PenempatanAsrama::class;

    protected static ?string $cluster = Fasilitas::class;

    protected static ?string $navigationIcon = 'heroicon-o-archive-box';

    protected static ?string $navigationLabel = 'Riwayat Penempatan';
    protected static ?string $navigationGroup = 'Asrama';

    public static function table(Table $table): Table
    {
        return $table
            ->heading('Riwayat Penempatan Asrama')
            ->description('Data penempatan peserta pada pelatihan-pelatihan yang sudah berlalu.')
            ->columns([
                Tables\Columns\TextColumn::make('peserta.nama')
                    ->label('Nama Peserta')
                    ->searchable(),

                Tables\Columns\TextColumn::make('pelatihan.nama')
                    ->label('Pelatihan')
                    ->searchable(),

                Tables\Columns\TextColumn::make('periode')
                    ->label('Periode')
                    ->searchable(),

                Tables\Columns\TextColumn::make('asrama.nama')
                    ->label('Asrama'),

                Tables\Columns\TextColumn::make('kamar.nomor_kamar')
                    ->label('No. Kamar')
                    ->formatStateUsing(fn($state, $record) =>
                        'Lantai '.$record->kamar->lantai.' / Kamar '.$state
                    ),
            ])
            ->defaultSort('created_at', 'desc')
            ->actions([])
            ->bulkActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPenempatanAsrama::route('/'),
        ];
    }
}
