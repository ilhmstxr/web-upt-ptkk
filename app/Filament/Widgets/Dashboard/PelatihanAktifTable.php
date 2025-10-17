<?php

namespace App\Filament\Widgets;

use App\Models\Pelatihan;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\Facades\DB;

class PelatihanAktifTable extends BaseWidget
{
    protected static ?string $heading = 'Daftar Semua Pelatihan';
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Pelatihan::query()
                    ->selectRaw("*,
                        CASE
                            WHEN NOW() BETWEEN tanggal_mulai AND tanggal_selesai THEN 1
                            WHEN tanggal_mulai > NOW() THEN 2
                            ELSE 3
                        END AS status_order
                    ")
                    ->orderBy('status_order')
                    ->orderByRaw("CASE WHEN tanggal_mulai > NOW() THEN tanggal_mulai END ASC")
                    ->orderByRaw("CASE WHEN tanggal_selesai < NOW() THEN tanggal_selesai END DESC")
            )
            ->columns([
                Tables\Columns\TextColumn::make('nama_pelatihan')->label('Nama Pelatihan'),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->label('Status')
                    ->getStateUsing(function (Pelatihan $record): string {
                        $now = now();
                        if ($now->between($record->tanggal_mulai, $record->tanggal_selesai)) {
                            return 'Aktif';
                        }
                        if ($now->isBefore($record->tanggal_mulai)) {
                            return 'Belum Mulai';
                        }
                        return 'Selesai';
                    })
                    ->color(fn(string $state): string => match ($state) {
                        'Aktif' => 'success',
                        'Belum Mulai' => 'warning',
                        'Selesai' => 'danger',
                    }),
                Tables\Columns\TextColumn::make('program')->badge(),
                Tables\Columns\TextColumn::make('tanggal_mulai')->date('d M Y'),
                Tables\Columns\TextColumn::make('tanggal_selesai')->date('d M Y'),
            ])
            ->actions([
                // Action untuk melihat detail pelatihan
                // Tables\Actions\Action::make('lihat')
                //     ->url(fn(Pelatihan $record): string => route('filament.admin.resources.pelatihans.view', $record))
                //     ->icon('heroicon-o-eye'),
            ]);
    }
}
