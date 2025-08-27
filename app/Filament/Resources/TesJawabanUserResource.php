<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TesJawabanUserResource\Pages;
use App\Models\Tes_JawabanUser;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Resources\Resource;

class TesJawabanUserResource extends Resource
{
    protected static ?string $model = Tes_JawabanUser::class;

    // Ganti ikon supaya tidak error
    protected static ?string $navigationIcon = 'heroicon-o-clipboard';
    protected static ?string $navigationLabel = 'Jawaban Peserta';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('percobaan_tes_id')
                ->relationship('percobaanTes', 'id')
                ->required(),
            Forms\Components\Select::make('pertanyaan_id')
                ->relationship('pertanyaan', 'teks_pertanyaan')
                ->required(),
            Forms\Components\Select::make('opsi_jawaban_id')
                ->relationship('opsiJawaban', 'teks_opsi')
                ->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('percobaanTes.id')->label('Percobaan ID'),
            Tables\Columns\TextColumn::make('pertanyaan.teks_pertanyaan')->limit(50),
            Tables\Columns\TextColumn::make('opsiJawaban.teks_opsi')->limit(50),
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
            'index' => Pages\ListTesJawabanUser::route('/'),
            'create' => Pages\CreateTesJawabanUser::route('/create'),
            'edit' => Pages\EditTesJawabanUser::route('/{record}/edit'),
        ];
    }
}
