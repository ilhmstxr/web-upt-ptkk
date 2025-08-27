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
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationLabel = 'Percobaan Tes';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('peserta_id')
                ->relationship('peserta', 'nama')
                ->required(),
            Forms\Components\Select::make('tes_id')
                ->relationship('tes', 'judul')
                ->required(),
            Forms\Components\DateTimePicker::make('waktu_mulai')->required(),
            Forms\Components\DateTimePicker::make('waktu_selesai'),
            Forms\Components\TextInput::make('skor')->numeric(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('peserta.nama')->label('Peserta')->searchable(),
            Tables\Columns\TextColumn::make('tes.judul')->label('Tes')->searchable(),
            Tables\Columns\TextColumn::make('waktu_mulai')->dateTime(),
            Tables\Columns\TextColumn::make('waktu_selesai')->dateTime(),
            Tables\Columns\TextColumn::make('skor')->sortable(),
            Tables\Columns\TextColumn::make('created_at')->dateTime(),
        ])
        ->actions([
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
