<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PreTestResultsResource\Pages;
use App\Models\PreTestResult;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables;

class PreTestResultsResource extends Resource
{
    protected static ?string $model = PreTestResult::class;

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
                    ->label('Keterangan')
                    ->wrap()
                    ->nullable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Nama User')
                    ->searchable()
                    ->sortable()
                    ->wrap()
                    ->extraAttributes(['style' => 'min-width: 200px;']),

                Tables\Columns\TextColumn::make('score')
                    ->label('Skor')
                    ->sortable(),

                Tables\Columns\TextColumn::make('remarks')
                    ->label('Keterangan')
                    ->wrap()
                    ->extraAttributes(['style' => 'min-width: 250px;']),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ])
            ->contentWidth('full'); // tabel lebar penuh
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
