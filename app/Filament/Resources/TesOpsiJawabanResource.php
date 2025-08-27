<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TesOpsiJawabanResource\Pages;
use App\Models\Tes_OpsiJawaban;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Resources\Resource;

class TesOpsiJawabanResource extends Resource
{
    protected static ?string $model = Tes_OpsiJawaban::class;
    protected static ?string $navigationIcon = 'heroicon-o-check-circle';
    protected static ?string $navigationLabel = 'Opsi Jawaban';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('pertanyaan_id')
                ->relationship('pertanyaan', 'teks_pertanyaan')
                ->required(),
            Forms\Components\Textarea::make('teks_opsi')->required(),
            Forms\Components\Toggle::make('apakah_benar')->default(false),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('pertanyaan.teks_pertanyaan')->limit(50),
            Tables\Columns\TextColumn::make('teks_opsi'),
            Tables\Columns\IconColumn::make('apakah_benar')->boolean(),
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
            'index' => Pages\ListTesOpsiJawaban::route('/'),
            'create' => Pages\CreateTesOpsiJawaban::route('/create'),
            'edit' => Pages\EditTesOpsiJawaban::route('/{record}/edit'),
        ];
    }
}
