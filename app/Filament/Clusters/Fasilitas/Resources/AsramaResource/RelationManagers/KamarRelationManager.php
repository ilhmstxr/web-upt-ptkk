<?php

namespace App\Filament\Clusters\Fasilitas\Resources\AsramaResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class KamarRelationManager extends RelationManager
{
    protected static string $relationship = 'kamars';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nomor_kamar')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('lantai')
                    ->numeric()
                    ->required(),
                Forms\Components\Select::make('status')
                    ->options([
                        'Tersedia' => 'Tersedia',
                        'Penuh' => 'Penuh',
                        'Rusak' => 'Rusak',
                        'Perbaikan' => 'Perbaikan',
                    ])
                    ->required(),
                Forms\Components\TextInput::make('total_beds')
                    ->numeric()
                    ->label('Total Bed')
                    ->required(),
                Forms\Components\TextInput::make('available_beds')
                    ->numeric()
                    ->label('Bed Tersedia')
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('nomor_kamar')
            ->columns([
                Tables\Columns\TextColumn::make('nomor_kamar')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('lantai')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Tersedia' => 'success',
                        'Penuh' => 'danger',
                        'Rusak' => 'danger',
                        'Perbaikan' => 'warning',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('total_beds')
                    ->numeric()
                    ->label('Total Bed'),
                Tables\Columns\TextColumn::make('available_beds')
                    ->numeric()
                    ->label('Tersedia'),
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
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
