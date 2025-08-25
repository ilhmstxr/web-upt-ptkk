<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PretestResource\Pages;
use App\Models\Pretest;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PretestResource extends Resource
{
    protected static ?string $model = Pretest::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $navigationGroup = 'Manajemen Soal';
    protected static ?string $navigationLabel = 'Pre-Test';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Textarea::make('question')
                    ->label('Pertanyaan')
                    ->required(),

                Forms\Components\TextInput::make('option_a')
                    ->label('Pilihan A')
                    ->required(),

                Forms\Components\TextInput::make('option_b')
                    ->label('Pilihan B')
                    ->required(),

                Forms\Components\TextInput::make('option_c')
                    ->label('Pilihan C')
                    ->required(),

                Forms\Components\TextInput::make('option_d')
                    ->label('Pilihan D')
                    ->required(),

                Forms\Components\Select::make('correct_answer')
                    ->label('Jawaban Benar')
                    ->options([
                        'A' => 'A',
                        'B' => 'B',
                        'C' => 'C',
                        'D' => 'D',
                    ])
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('question')
                    ->label('Pertanyaan')
                    ->limit(50)
                    ->searchable(),

                Tables\Columns\TextColumn::make('correct_answer')
                    ->label('Jawaban Benar'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y H:i'),
            ])
            ->filters([])
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
            'index' => Pages\ListPretests::route('/'),
            'create' => Pages\CreatePretest::route('/create'),
            'edit' => Pages\EditPretest::route('/{record}/edit'),
        ];
    }
}
