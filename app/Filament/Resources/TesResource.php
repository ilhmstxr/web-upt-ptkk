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
    protected static ?string $navigationIcon = 'heroicon-o-archive-box';
    protected static ?string $navigationLabel = 'Tes';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('judul')->required(),
            
            Forms\Components\Select::make('tipe')
                ->options(['tes' => 'Tes', 'survei' => 'Survei'])
                ->required(),

            Forms\Components\Select::make('sub_tipe')
                ->options(['pre-test' => 'Pre-Test', 'post-test' => 'Post-Test'])
                ->required(),

            Forms\Components\Select::make('bidang_id')
                ->relationship('bidang', 'nama_bidang')
                ->required(),

            Forms\Components\Select::make('pelatihan_id')
                ->relationship('pelatihan', 'nama_pelatihan')
                ->required(),

            Forms\Components\TextInput::make('durasi_menit')->numeric()->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('judul')->sortable()->searchable(),
            Tables\Columns\TextColumn::make('tipe')->sortable(),
            Tables\Columns\TextColumn::make('sub_tipe')->sortable(),
            Tables\Columns\TextColumn::make('bidang.nama_bidang')->label('Bidang')->sortable(),
            Tables\Columns\TextColumn::make('pelatihan.nama_pelatihan')->label('Pelatihan')->sortable(),
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
