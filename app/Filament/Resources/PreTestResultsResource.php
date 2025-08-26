<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PreTestResultsResource\Pages;
use App\Models\PreTestResults;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PreTestResultsResource extends Resource
{
    protected static ?string $model = PreTestResults::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';
    protected static ?string $navigationGroup = 'Pre Test';
    protected static ?string $navigationLabel = 'Pre-Test Results';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->required(),

                Forms\Components\TextInput::make('score')
                    ->numeric()
                    ->required(),

                Forms\Components\Textarea::make('remarks')
                    ->label('Remarks')
                    ->nullable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('User')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('score')
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('d M Y H:i')
                    ->label('Taken At')
                    ->sortable(),
            ])
            ->filters([]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListPreTestResults::route('/'),
            'create' => Pages\CreatePreTestResults::route('/create'),
            'edit'   => Pages\EditPreTestResults::route('/{record}/edit'),
        ];
    }
}
