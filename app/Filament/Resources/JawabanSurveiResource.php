<?php

namespace App\Filament\Resources;

use App\Filament\Resources\JawabanSurveiResource\Pages;
use App\Models\JawabanSurvei;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class JawabanSurveiResource extends Resource
{
    protected static ?string $model = JawabanSurvei::class;

    protected static ?string $navigationGroup = 'Survei Monev';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Jawaban Survei';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nama')
                    ->label('Nama')
                    ->required()
                    ->maxLength(100),

                Forms\Components\TextInput::make('email')
                    ->label('Email')
                    ->email()
                    ->required()
                    ->maxLength(150),

                Forms\Components\Textarea::make('jawaban')
                    ->label('Jawaban')
                    ->rows(4)
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama')
                    ->label('Nama')
                    ->searchable()
                    ->sortable()
                    ->limit(50),

                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->sortable()
                    ->limit(50),

                Tables\Columns\TextColumn::make('jawaban')
                    ->label('Jawaban')
                    ->limit(100),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dijawab Pada')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->filters([
                // Bisa ditambah filter berdasarkan tanggal atau email
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
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
            // tambahkan RelationManager jika ada relasi lain
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListJawabanSurveis::route('/'),
            'create' => Pages\CreateJawabanSurvei::route('/create'),
            'edit' => Pages\EditJawabanSurvei::route('/{record}/edit'),
            'report' => Pages\PelatihanReport::route('/{pelatihanId}/report'),
            'belum-mengisi' => Pages\DaftarPesertaBelumMengisi::route('/belum-mengisi'),
        ];
    }
}
