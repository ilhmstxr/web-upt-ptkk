<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PostTestResultsResource\Pages;
use App\Models\PostTestResult;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables;

class PostTestResultsResource extends Resource
{
    protected static ?string $model = PostTestResult::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';
    protected static ?string $navigationGroup = 'Hasil Test';
    protected static ?string $navigationLabel = 'Hasil Post-Test';

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.id')
                    ->label('User ID'),

                Tables\Columns\TextColumn::make('user.name')
                    ->label('Nama User')
                    ->searchable()
                    ->wrap()
                    ->extraAttributes(['style' => 'min-width: 200px;']),

                Tables\Columns\TextColumn::make('skor')
                    ->label('Skor')
                    ->getStateUsing(fn ($record) =>
                        PostTestResult::where('user_id', $record->user_id)
                            ->where('is_correct', true)
                            ->count()
                    ),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y H:i'),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ])
            ->contentWidth('full'); // tabel lebar penuh
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPostTestResults::route('/'),
        ];
    }
}
