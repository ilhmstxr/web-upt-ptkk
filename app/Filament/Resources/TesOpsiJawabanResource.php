<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TesOpsiJawabanResource\Pages;
use App\Models\OpsiJawaban;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Resources\Resource;

class TesOpsiJawabanResource extends Resource
{
    protected static ?string $model = OpsiJawaban::class;
    protected static ?string $navigationIcon = 'heroicon-o-check-circle';
    protected static ?string $navigationLabel = 'Opsi Jawaban';

    public static function form(Form $form): Form
    {
        return $form->schema([
            // Relasi ke Pertanyaan
            Forms\Components\Select::make('pertanyaan_id')
                ->relationship('pertanyaan', 'teks_pertanyaan')
                ->label('Pertanyaan')
                ->required(),

            // Teks opsi jawaban
            Forms\Components\Textarea::make('teks_opsi')
                ->label('Teks Opsi')
                ->required(),

            // Upload gambar opsional
            Forms\Components\FileUpload::make('gambar')
                ->label('Gambar Opsi')
                ->image()
                ->directory('opsi-jawaban')
                ->nullable()
                ->maxSize(2048),

            // Toggle jawaban benar
            Forms\Components\Toggle::make('apakah_benar')
                ->label('Jawaban Benar')
                ->default(false),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('pertanyaan.teks_pertanyaan')
                ->label('Pertanyaan')
                ->limit(50)
                ->wrap()
                ->sortable()
                ->searchable(),

            Tables\Columns\TextColumn::make('teks_opsi')
                ->label('Teks Opsi')
                ->limit(50)
                ->wrap()
                ->sortable()
                ->searchable(),

            Tables\Columns\ImageColumn::make('gambar')
                ->label('Gambar')
                ->disk('public')
                ->circular()
                ->size(40)
                ->defaultImageUrl('https://ui-avatars.com/api/?name=O&background=random'),

            Tables\Columns\IconColumn::make('apakah_benar')
                ->label('Benar?')
                ->boolean()
                ->sortable(),

            Tables\Columns\TextColumn::make('created_at')
                ->label('Dibuat')
                ->dateTime('d M Y H:i')
                ->sortable(),
        ])
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
            'index' => Pages\ListTesOpsiJawaban::route('/'),
            'create' => Pages\CreateTesOpsiJawaban::route('/create'),
            'edit' => Pages\EditTesOpsiJawaban::route('/{record}/edit'),
        ];
    }
}
