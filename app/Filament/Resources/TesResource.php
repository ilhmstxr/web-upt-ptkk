<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TesResource\Pages;
use App\Models\Tes;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Resources\Resource;

class TesResource extends Resource
{
    protected static ?string $model = Tes::class;
    // Ganti ikon dengan yang pasti ada
    protected static ?string $navigationIcon = 'heroicon-o-archive';
    protected static ?string $navigationLabel = 'Tes';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('judul')->required(),
            Forms\Components\Select::make('tipe')
                ->options(['pre-test' => 'Pre-Test', 'post-test' => 'Post-Test'])
                ->required(),
            Forms\Components\TextInput::make('bidang')->required(),
            Forms\Components\TextInput::make('pelatihan')->required(),
            Forms\Components\TextInput::make('durasi_menit')->numeric()->required(),
            Forms\Components\MultiSelect::make('pertanyaan')
                ->relationship('pertanyaan', 'teks_pertanyaan'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('judul')->sortable()->searchable(),
            Tables\Columns\TextColumn::make('tipe')->sortable(),
            Tables\Columns\TextColumn::make('bidang')->sortable(),
            Tables\Columns\TextColumn::make('pelatihan')->sortable(),
            Tables\Columns\TextColumn::make('durasi_menit')->sortable(),
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
            'index' => Pages\ListTes::route('/'),
            'create' => Pages\CreateTes::route('/create'),
            'edit' => Pages\EditTes::route('/{record}/edit'),
        ];
    }
}
