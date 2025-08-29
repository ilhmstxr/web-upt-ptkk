<?php

namespace App\Filament\Resources;

use App\Filament\Resources\JawabanSurveiResource\Pages;
use App\Filament\Resources\JawabanSurveiResource\RelationManagers;
use App\Models\JawabanSurvei;
use App\Models\JawabanUser;
use App\Models\PesertaSurvei;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class JawabanSurveiResource extends Resource
{
    protected static ?string $model = PesertaSurvei::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama')->limit(50),
                Tables\Columns\TextColumn::make('email')->limit(50),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListJawabanSurveis::route('/'),
            'create' => Pages\CreateJawabanSurvei::route('/create'),
            'edit' => Pages\EditJawabanSurvei::route('/{record}/edit'),
            'report' => Pages\PelatihanReport::route('/{pelatihanId}/report'),

        ];
    }
}
