<?php

namespace App\Filament\Widgets;

use App\Filament\Pages\DetailPelatihan;
use App\Filament\Clusters\Pelatihan\Resources\PelatihanResource;
use App\Models\Pelatihan;
use Carbon\Carbon;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class PelatihanAktifTable extends BaseWidget
{
    protected static ?int $sort = 2; // Urutan widget

    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                // Query untuk mengambil data
                Pelatihan::query()->orderBy('status', 'asc')
                    ->orderBy('tanggal_selesai', 'desc')
            )
            ->columns([
                Tables\Columns\TextColumn::make('nama_pelatihan')
                    ->label('Nama Pelatihan')
                    ->searchable()
                    ->sortable()
                    ->limit(40),
                Tables\Columns\TextColumn::make('tanggal_mulai')
                    ->label('Tanggal Pelaksanaan')
                    ->sortable()
                    ->formatStateUsing(
                        fn($record) =>
                        Carbon::parse($record->tanggal_mulai)->format('d M Y') . ' - ' .
                            Carbon::parse($record->tanggal_selesai)->format('d M Y')
                    ),
                Tables\Columns\TextColumn::make('jenis_program')
                    ->label('Jenis')
                    ->badge()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'Aktif' => 'success',
                        'Draf' => 'gray',
                        'Selesai' => 'warning',
                        default => 'gray',
                    }),
            ])
            ->actions([
                // INI ADALAH TOMBOL PENGHUBUNGNYA
                Tables\Actions\Action::make('Lihat Dasbor')
                    ->label('Lihat Dasbor')
                    ->icon('heroicon-o-arrow-right')
                    ->color('primary')
                    ->url(
                        fn(Pelatihan $record): string =>
                        // Arahkan ke Halaman Kustom "DetailPelatihan"
                        DetailPelatihan::getUrl(['recordId' => $record->id])
                    ),
            ]);
    }
}
