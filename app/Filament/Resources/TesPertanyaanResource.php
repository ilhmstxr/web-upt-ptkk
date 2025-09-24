<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TesPertanyaanResource\Pages;
use App\Models\Pertanyaan;
use App\Models\Tes;
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
            // Pilih Tes
            Forms\Components\Select::make('tes_id')
                ->relationship('tes', 'judul')
                ->label('Tes')
                ->required()
                ->reactive(),

            // ==== MODE INDIVIDU ====
            Forms\Components\Card::make()
                ->label('Pertanyaan Tunggal')
                ->schema([
                    Forms\Components\TextInput::make('nomor')
                        ->label('Nomor Pertanyaan')
                        ->default(fn(\Filament\Forms\Get $get) => Pertanyaan::where('tes_id', $get('tes_id'))->max('nomor') + 1 ?? 1)
                        ->numeric()
                        ->required(),

                    Forms\Components\Textarea::make('teks_pertanyaan')
                        ->label('Pertanyaan')
                        ->required(),

                    Forms\Components\FileUpload::make('gambar')
                        ->label('Gambar Pertanyaan')
                        ->image()
                        ->directory('pertanyaan')
                        ->disk('public')
                        ->nullable()
                        ->maxSize(2048),

                    // Opsi Jawaban
                    Forms\Components\Repeater::make('opsiJawaban')
                        ->label('Opsi Jawaban')
                        ->relationship('opsiJawaban') // Pastikan ada relationship di Model
                        ->schema([
                            Forms\Components\Textarea::make('teks_opsi')
                                ->label('Teks Opsi')
                                ->required(),

                            Forms\Components\FileUpload::make('gambar')
                                ->label('Gambar Opsi')
                                ->image()
                                ->directory('opsi-jawaban')
                                ->disk('public')
                                ->nullable()
                                ->maxSize(2048),

                            Forms\Components\Toggle::make('apakah_benar')
                                ->label('Benar?')
                                ->default(false),
                        ])
                        ->columns(2)
                        ->defaultItems(4)
                        ->createItemButtonLabel('Tambah Opsi'),
                ]),

            // ==== MODE MASS INPUT ====
            Forms\Components\Card::make()
                ->label('Tambah Banyak Soal')
                ->schema([
                    Forms\Components\Repeater::make('pertanyaan_list')
                        ->label('Daftar Pertanyaan')
                        ->schema([
                            Forms\Components\TextInput::make('nomor')
                                ->label('Nomor Pertanyaan')
                                ->numeric()
                                ->required(),

                            Forms\Components\Textarea::make('teks_pertanyaan')
                                ->label('Pertanyaan')
                                ->required(),

                            Forms\Components\FileUpload::make('gambar')
                                ->label('Gambar Pertanyaan')
                                ->image()
                                ->directory('pertanyaan')
                                ->disk('public')
                                ->nullable()
                                ->maxSize(2048),

                            Forms\Components\Repeater::make('opsi_jawaban')
                                ->label('Opsi Jawaban')
                                ->schema([
                                    Forms\Components\Textarea::make('teks_opsi')
                                        ->required(),

                                    Forms\Components\FileUpload::make('gambar')
                                        ->image()
                                        ->directory('opsi-jawaban')
                                        ->disk('public')
                                        ->nullable()
                                        ->maxSize(2048),

                                    Forms\Components\Toggle::make('apakah_benar')
                                        ->label('Benar?')
                                        ->default(false),
                                ])
                                ->columns(2)
                                ->defaultItems(4),
                        ])
                        ->createItemButtonLabel('Tambah Soal'),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('tes.judul')
                    ->label('Tes')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('nomor')
                    ->label('No')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('teks_pertanyaan')
                    ->label('Pertanyaan')
                    ->limit(50)
                    ->sortable()
                    ->searchable(),

                Tables\Columns\ImageColumn::make('gambar')
                    ->label('Gambar')
                    ->disk('public')
                    ->circular()
                    ->size(40)
                    ->defaultImageUrl('https://ui-avatars.com/api/?name=Q&background=random'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('tes_id')
                    ->label('Filter Tes')
                    ->options(Tes::pluck('judul', 'id'))
                    ->searchable(),
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
