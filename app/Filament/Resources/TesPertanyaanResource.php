<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TesPertanyaanResource\Pages;
use App\Models\Pertanyaan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Resources\Resource;

class TesPertanyaanResource extends Resource
{
    protected static ?string $model = Pertanyaan::class;
    protected static ?string $navigationIcon = 'heroicon-o-question-mark-circle';
    protected static ?string $navigationLabel = 'Pertanyaan';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Textarea::make('teks_pertanyaan')->required(),
            Forms\Components\Repeater::make('opsi_jawaban')
                ->relationship('opsiJawaban')
                ->schema([
                    Forms\Components\Textarea::make('teks_opsi')->required(),
                    Forms\Components\Toggle::make('apakah_benar')->label('Benar?')->default(false),
                ])
                ->columns(2)
                ->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('teks_pertanyaan')->limit(50)->searchable(),
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
            'index' => Pages\ListTesPertanyaan::route('/'),
            'create' => Pages\CreateTesPertanyaan::route('/create'),
            'edit' => Pages\EditTesPertanyaan::route('/{record}/edit'),
        ];
    }
}
