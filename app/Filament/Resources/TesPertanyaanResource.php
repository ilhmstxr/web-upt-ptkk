<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TesPertanyaanResource\Pages;
use App\Models\Pertanyaan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Resources\Resource;

class TesPertanyaanResource extends Resource
{
    protected static ?string $model = Pertanyaan::class;
    protected static ?string $navigationIcon = 'heroicon-o-question-mark-circle';
    protected static ?string $navigationLabel = 'Pertanyaan';

    public static function form(Form $form): Form
    {
        return $form->schema([
            // Field Tes di atas container pertanyaan
            Forms\Components\Select::make('tes_id')
                ->relationship('tes', 'judul')
                ->label('Tes')
                ->required()
                ->reactive(),

            // Container Daftar Pertanyaan
            Forms\Components\Card::make()
                ->schema([
                    Forms\Components\Repeater::make('pertanyaan_list')
                        ->label('Daftar Pertanyaan')
                        ->schema([
                            // Nomor pertanyaan otomatis
                            Forms\Components\TextInput::make('nomor')
                                ->label('Nomor Pertanyaan')
                                ->default(function (\Filament\Forms\Get $get) {
                                    $tesId = $get('tes_id');
                                    if (!$tesId) {
                                        return 1;
                                    }
                                    $nomorTerakhir = Pertanyaan::where('tes_id', $tesId)->max('nomor') ?? 0;
                                    return $nomorTerakhir + 1;
                                })
                                ->numeric()
                                ->required(),

                            // Teks pertanyaan
                            Forms\Components\Textarea::make('teks_pertanyaan')
                                ->label('Pertanyaan')
                                ->required(),

                            // Upload gambar opsional
                            Forms\Components\FileUpload::make('gambar')
                                ->label('Gambar Pertanyaan')
                                ->image(),

                            // Nested Repeater untuk opsi jawaban
                            Forms\Components\Repeater::make('opsi_jawabans')
                                ->relationship('opsiJawabans')
                                ->schema([
                                    Forms\Components\Textarea::make('teks_opsi')->required(),
                                    Forms\Components\FileUpload::make('gambar')->image(),
                                    Forms\Components\Toggle::make('apakah_benar')->label('Benar?')->default(false),
                                ])
                                ->columns(2)
                                ->defaultItems(4)
                                ->label('Opsi Jawaban'),
                        ])
                        ->createItemButtonLabel('Tambah Soal')
                        ->columns(1),
                ])
                ->columns(1),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('tes.judul')->label('Tes')->sortable(),
            Tables\Columns\TextColumn::make('nomor')->sortable(),
            Tables\Columns\TextColumn::make('teks_pertanyaan')->limit(50)->searchable(),
            Tables\Columns\TextColumn::make('created_at')->dateTime(),
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
            'index' => Pages\ListTesPertanyaan::route('/'),
            'create' => Pages\CreateTesPertanyaan::route('/create'),
            'edit' => Pages\EditTesPertanyaan::route('/{record}/edit'),
        ];
    }
}
