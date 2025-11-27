<?php

namespace App\Filament\Clusters\Evaluasi\Resources;

use App\Filament\Clusters\Evaluasi;
use App\Filament\Clusters\Evaluasi\Resources\TesResource\Pages;
use App\Filament\Clusters\Evaluasi\Resources\TesResource\RelationManagers;
use App\Models\Tes;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TesResource extends Resource
{
    protected static ?string $model = Tes::class;

    protected static ?string $cluster = Evaluasi::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Tes')
                    ->schema([
                        Forms\Components\TextInput::make('judul')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Select::make('tipe')
                            ->options([
                                'pre-test' => 'Pre-Test',
                                'post-test' => 'Post-Test',
                                'survey' => 'Survey',
                            ])
                            ->required(),
                        Forms\Components\Select::make('pelatihan_id')
                            ->relationship('pelatihan', 'nama_pelatihan')
                            ->searchable()
                            ->required(),
                        Forms\Components\TextInput::make('durasi_menit')
                            ->numeric()
                            ->label('Durasi (Menit)')
                            ->required(),
                        Forms\Components\RichEditor::make('deskripsi')
                            ->columnSpanFull(),
                    ])->columns(2),

                Forms\Components\Section::make('Bank Soal')
                    ->schema([
                        Forms\Components\Repeater::make('pertanyaan')
                            ->relationship()
                            ->schema([
                                Forms\Components\RichEditor::make('teks_pertanyaan')
                                    ->required()
                                    ->label('Pertanyaan'),
                                Forms\Components\FileUpload::make('gambar')
                                    ->image()
                                    ->directory('soal-images')
                                    ->label('Gambar Soal (Opsional)'),
                                Forms\Components\Select::make('tipe_jawaban')
                                    ->options([
                                        'pilihan_ganda' => 'Pilihan Ganda',
                                        'essay' => 'Essay',
                                    ])
                                    ->default('pilihan_ganda')
                                    ->reactive(),
                                
                                Forms\Components\Repeater::make('opsiJawabans')
                                    ->relationship()
                                    ->schema([
                                        Forms\Components\TextInput::make('teks_opsi')
                                            ->required()
                                            ->label('Teks Opsi'),
                                        Forms\Components\Toggle::make('apakah_benar')
                                            ->label('Jawaban Benar'),
                                        Forms\Components\FileUpload::make('gambar')
                                            ->image()
                                            ->directory('opsi-images')
                                            ->label('Gambar Opsi (Opsional)'),
                                    ])
                                    ->columns(2)
                                    ->label('Opsi Jawaban')
                                    ->visible(fn (Forms\Get $get) => $get('tipe_jawaban') === 'pilihan_ganda')
                                    ->defaultItems(4),
                            ])
                            ->itemLabel(fn (array $state): ?string => strip_tags($state['teks_pertanyaan'] ?? null))
                            ->collapsible()
                            ->collapsed(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('judul')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tipe')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pre-test' => 'info',
                        'post-test' => 'success',
                        'survey' => 'warning',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('pelatihan.nama_pelatihan')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('durasi_menit')
                    ->numeric()
                    ->label('Durasi'),
                Tables\Columns\TextColumn::make('pertanyaan_count')
                    ->counts('pertanyaan')
                    ->label('Jml Soal'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('tipe')
                    ->options([
                        'pre-test' => 'Pre-Test',
                        'post-test' => 'Post-Test',
                        'survey' => 'Survey',
                    ]),
                Tables\Filters\SelectFilter::make('pelatihan')
                    ->relationship('pelatihan', 'nama_pelatihan'),
            ])
            ->actions([
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTes::route('/'),
            'create' => Pages\CreateTes::route('/create'),
            'edit' => Pages\EditTes::route('/{record}/edit'),
        ];
    }
}
