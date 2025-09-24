<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TesPercobaanResource\Pages;
use App\Models\Percobaan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Resources\Resource;

class TesPercobaanResource extends Resource
{
    protected static ?string $model = Percobaan::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard';
    protected static ?string $navigationGroup = 'Tes & Percobaan';
    protected static ?string $navigationLabel = 'Tes Percobaan';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('pesertaSurvei_id')
                ->label('Peserta')
                ->relationship('pesertaSurvei', 'nama') // pakai pesertaSurvei, bukan peserta
                ->searchable()
                ->required(),

            Forms\Components\Select::make('tes_id')
                ->label('Tes')
                ->relationship('tes', 'judul')
                ->searchable()
                ->required(),

            Forms\Components\DateTimePicker::make('waktu_mulai')
                ->label('Waktu Mulai')
                ->required(),

            Forms\Components\DateTimePicker::make('waktu_selesai')
                ->label('Waktu Selesai'),

            Forms\Components\TextInput::make('skor')
                ->numeric()
                ->label('Skor'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('pesertaSurvei.nama')
                    ->label('Peserta')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('tes.judul')
                    ->label('Tes')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('waktu_mulai')
                    ->label('Mulai')
                    ->dateTime('d M Y H:i')
                    ->sortable(),

                Tables\Columns\TextColumn::make('waktu_selesai')
                    ->label('Selesai')
                    ->dateTime('d M Y H:i')
                    ->sortable(),

                Tables\Columns\TextColumn::make('skor')
                    ->label('Skor'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTesPercobaan::route('/'),
            'create' => Pages\CreateTesPercobaan::route('/create'),
            'edit' => Pages\EditTesPercobaan::route('/{record}/edit'),
        ];
    }
}
