<?php

namespace App\Filament\Clusters\Fasilitas\Resources;

use App\Filament\Clusters\Fasilitas;
use App\Filament\Clusters\Fasilitas\Resources\AsramaResource\Pages;
use App\Filament\Clusters\Fasilitas\Resources\AsramaResource\RelationManagers;
use App\Models\Asrama;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AsramaResource extends Resource
{
    protected static ?string $model = Asrama::class;

    protected static ?string $cluster = Fasilitas::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nama Asrama')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('gender')
                    ->options([
                        'Laki-laki' => 'Laki-laki',
                        'Perempuan' => 'Perempuan',
                        'Campur' => 'Campur',
                    ])
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nomor_kamar')
                    ->label('Kamar')
                    ->weight('bold')
                    ->icon('heroicon-o-home')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('lantai')
                    ->label('Lantai')
                    ->sortable(),
                Tables\Columns\TextColumn::make('kapasitas')
                    ->label('Kapasitas')
                    ->numeric()
                    ->suffix(' Bed'),
                Tables\Columns\TextColumn::make('penghuni_count')
                    ->counts('penghuni')
                    ->label('Terisi')
                    ->badge()
                    ->color(fn ($state, $record) => $state >= $record->kapasitas ? 'danger' : ($state > 0 ? 'warning' : 'success')),
                Tables\Columns\TextColumn::make('status_ketersediaan')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Tersedia' => 'success',
                        'Penuh' => 'danger',
                        'Perbaikan' => 'warning',
                        default => 'gray',
                    }),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\KamarRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAsramas::route('/'),
            'create' => Pages\CreateAsrama::route('/create'),
            'edit' => Pages\EditAsrama::route('/{record}/edit'),
        ];
    }
}
