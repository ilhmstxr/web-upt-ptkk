<?php

namespace App\Filament\Clusters\Fasilitas\Resources\AsramaResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class KamarRelationManager extends RelationManager
{
    protected static string $relationship = 'kamars';
    protected static ?string $recordTitleAttribute = 'nomor_kamar';

    /**
     * Form untuk create / edit Kamar via RelationManager.
     *
     * Note: signature mengikuti parent RelationManager::form(...)
     */
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nomor_kamar')
                    ->label('Nomor Kamar')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('lantai')
                    ->label('Lantai')
                    ->numeric()
                    ->required(),

                Forms\Components\TextInput::make('total_beds')
                    ->label('Total Bed')
                    ->numeric()
                    ->required(),

                Forms\Components\TextInput::make('available_beds')
                    ->label('Bed Tersedia')
                    ->numeric()
                    ->required()
                    ->helperText('Update angka ini otomatis via observer/event saat penempatan/pengeluaran penghuni.'),

                Forms\Components\Select::make('status')
                    ->label('Status')
                    ->options([
                        'Tersedia'  => 'Tersedia',
                        'Penuh'     => 'Penuh',
                        'Rusak'     => 'Rusak',
                        'Perbaikan' => 'Perbaikan',
                    ])
                    ->required(),
            ]);
    }

    /**
     * Table columns & actions untuk menampilkan daftar kamar di tab relasi.
     *
     * Pastikan menggunakan Filament\Tables\Table untuk signature (sesuai parent).
     */
    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('nomor_kamar')
            ->columns([
                Tables\Columns\TextColumn::make('nomor_kamar')
                    ->label('Nomor Kamar')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('lantai')
                    ->label('Lantai')
                    ->numeric()
                    ->sortable(),

                // Penghuni Aktif (mengandalkan method penghuniAktif() di model Kamar)
                Tables\Columns\TextColumn::make('penghuni_count')
                    ->label('Penghuni Aktif')
                    ->getStateUsing(fn (Model $record) => method_exists($record, 'penghuniAktif') ? $record->penghuniAktif()->count() : 0)
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('total_beds')
                    ->label('Total Bed')
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('available_beds')
                    ->label('Tersedia')
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (?string $state): string => match ($state) {
                        'Tersedia'  => 'success',
                        'Penuh'     => 'danger',
                        'Rusak'     => 'danger',
                        'Perbaikan' => 'warning',
                        default     => 'gray',
                    }),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
}
