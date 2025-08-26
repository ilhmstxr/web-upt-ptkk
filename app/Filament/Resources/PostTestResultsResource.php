<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PostTestResultsResource\Pages;
use App\Models\PostTestAnswer;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Form;

class PostTestResultsResource extends Resource
{
    protected static ?string $model = PostTestAnswer::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';
    protected static ?string $navigationGroup = 'Hasil Test';
    protected static ?string $navigationLabel = 'Hasil Post-Test';

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.id')->label('User ID'),
                Tables\Columns\TextColumn::make('user.name')->label('Nama User')->searchable(),
                Tables\Columns\TextColumn::make('skor')
                    ->label('Skor')
                    ->getStateUsing(fn ($record) =>
                        PostTestAnswer::where('user_id', $record->user_id)
                            ->where('is_correct', true)
                            ->count()
                    ),
                Tables\Columns\TextColumn::make('created_at')->dateTime('d M Y H:i'),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPostTestResults::route('/'),
        ];
    }
}
