<?php

namespace App\Filament\Resources;

use App\Filament\Resources\JawabanSurveiResource\Pages;
use App\Models\JawabanUser; // <-- pakai JawabanUser
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use App\Filament\Resources\Widgets\JawabanPerPertanyaanChart;

class JawabanSurveiResource extends Resource
{
    // ganti model ke JawabanUser
    protected static ?string $model = JawabanUser::class;

    protected static ?string $navigationGroup = 'Survei Monev';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Jawaban Survei';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('pertanyaan_id')
                    ->relationship('pertanyaan', 'teks_pertanyaan')
                    ->label('Pertanyaan')
                    ->required(),

                Forms\Components\Select::make('opsi_jawaban_id')
                    ->relationship('opsiJawaban', 'teks_opsi')
                    ->label('Opsi Jawaban'),

                Forms\Components\Select::make('percobaan_id')
                    ->relationship('percobaan', 'id')
                    ->label('Percobaan'),

                Forms\Components\TextInput::make('nilai_jawaban')
                    ->numeric()
                    ->label('Nilai'),

                Forms\Components\Textarea::make('jawaban_teks')
                    ->label('Jawaban Teks')
                    ->rows(3)
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('pertanyaan.teks_pertanyaan')
                    ->label('Pertanyaan')
                    ->limit(50)
                    ->searchable(),

                Tables\Columns\TextColumn::make('opsiJawaban.teks_opsi')
                    ->label('Opsi Jawaban')
                    ->limit(50),

                Tables\Columns\TextColumn::make('jawaban_teks')
                    ->label('Jawaban Teks')
                    ->limit(80),

                Tables\Columns\TextColumn::make('nilai_jawaban')
                    ->label('Nilai')
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dijawab Pada')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->filters([])
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
        return [];
    }

    public static function getPages(): array
{
    return [
        'index' => Pages\ListJawabanSurveis::route('/'),
        'create' => Pages\CreateJawabanSurvei::route('/create'),
        'edit' => Pages\EditJawabanSurvei::route('/{record}/edit'),
        'report' => Pages\ReportJawabanSurvei::route('/report'),
        'export' => Pages\ExportJawabanSurvei::route('/export'),
    ];
}

    public static function getWidgets(): array
    {
        return [
            \App\Filament\Resources\JawabanSurveiResource\Widgets\JawabanPerPertanyaanChart::class,
        ];
    }
}
