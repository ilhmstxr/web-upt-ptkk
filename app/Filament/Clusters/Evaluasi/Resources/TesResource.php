<?php

namespace App\Filament\Clusters\Evaluasi\Resources;

use App\Filament\Clusters\Evaluasi;
use App\Filament\Clusters\Evaluasi\Resources\TesResource\Pages;
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

    /* =========================================================
     * FORM
     * ========================================================= */
    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Grid::make(3)->schema([

                /* =====================
                 * LEFT COLUMN
                 * ===================== */
                Forms\Components\Group::make()->schema([

                    Forms\Components\Section::make('Pengaturan Tes')->schema([
                        Forms\Components\Select::make('tipe')
                            ->options([
                                'pre-test'  => 'Pre-Test',
                                'post-test' => 'Post-Test',
                                'survei'    => 'Survei',
                            ])
                            ->required(),

                        Forms\Components\Select::make('pelatihan_id')
                            ->relationship('pelatihan', 'nama_pelatihan')
                            ->searchable()
                            ->required()
                            ->default(request()->query('pelatihan_id'))
                            ->disabled(fn (?string $operation) =>
                                $operation === 'edit' || request()->has('pelatihan_id')
                            ),

                        Forms\Components\Select::make('kompetensi_id')
                            ->relationship('kompetensi', 'nama_kompetensi')
                            ->searchable()
                            ->required()
                            ->default(request()->query('kompetensi_id'))
                            ->disabled(fn (?string $operation) =>
                                $operation === 'edit' || request()->has('kompetensi_id')
                            ),

                        Forms\Components\TextInput::make('durasi_menit')
                            ->numeric()
                            ->label('Durasi (Menit)')
                            ->required(),

                        Forms\Components\DateTimePicker::make('tanggal_mulai')
                            ->label('Tanggal Mulai')
                            ->required(),

                        Forms\Components\DateTimePicker::make('tanggal_selesai')
                            ->label('Tanggal Selesai')
                            ->required()
                            ->after('tanggal_mulai'),
                    ]),

                    Forms\Components\View::make(
                        'filament.clusters.evaluasi.resources.tes-resource.partials.tips-card'
                    ),

                ])->columnSpan(1),

                /* =====================
                 * RIGHT COLUMN
                 * ===================== */
                Forms\Components\Group::make()->schema([

                    Forms\Components\Section::make('Detail Tes')->schema([
                        Forms\Components\TextInput::make('judul')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),

                        Forms\Components\RichEditor::make('deskripsi')
                            ->columnSpanFull(),
                    ]),

                    /* =====================
                     * BANK SOAL
                     * ===================== */
                    Forms\Components\Section::make('Bank Soal')->schema([
                        Forms\Components\Repeater::make('pertanyaan')
                            ->relationship()
                            ->schema([

                                /* ===== KATEGORI / SUB SECTION ===== */
                                Forms\Components\TextInput::make('kategori')
                                    ->label('Kategori / Bagian (Sub Section)')
                                    ->placeholder('Contoh: PERSEPSI TERHADAP PROGRAM PELATIHAN')
                                    ->maxLength(255),

                                Forms\Components\RichEditor::make('teks_pertanyaan')
                                    ->label('Teks Pertanyaan')
                                    ->required(),

                                Forms\Components\FileUpload::make('gambar')
                                    ->image()
                                    ->directory('soal-images')
                                    ->label('Gambar Soal (Opsional)'),

                                Forms\Components\Select::make('tipe_jawaban')
                                    ->options([
                                        'pilihan_ganda' => 'Pilihan Ganda',
                                        'skala_likert'  => 'Skala Likert (Survei)',
                                        'teks_bebas'    => 'Essay',
                                    ])
                                    ->default('pilihan_ganda')
                                    ->reactive(),

                                /* ===== OPSI JAWABAN (KHUSUS PG) ===== */
                                Forms\Components\Repeater::make('opsiJawabans')
                                    ->relationship()
                                    ->schema([
                                        Forms\Components\TextInput::make('teks_opsi')
                                            ->required()
                                            ->label('Teks Opsi'),

                                        Forms\Components\Toggle::make('apakah_benar')
                                            ->label('Jawaban Benar')
                                            ->default(false)
                                            ->live()
                                            ->afterStateUpdated(function (
                                                $state,
                                                Forms\Get $get,
                                                Forms\Set $set,
                                                Forms\Components\Toggle $component
                                            ) {
                                                if (!$state) return;

                                                $path = $component->getStatePath();
                                                $segments = explode('.', $path);
                                                $currentKey = $segments[count($segments) - 2];
                                                $items = $get('../../opsiJawabans');

                                                foreach ($items as $key => $value) {
                                                    if ($key !== $currentKey && ($value['apakah_benar'] ?? false)) {
                                                        $targetPath = str_replace(
                                                            ".{$currentKey}.",
                                                            ".{$key}.",
                                                            $path
                                                        );
                                                        $set($targetPath, false);
                                                    }
                                                }
                                            }),

                                        Forms\Components\FileUpload::make('gambar')
                                            ->image()
                                            ->directory('opsi-images')
                                            ->label('Gambar Opsi (Opsional)'),
                                    ])
                                    ->columns(2)
                                    ->label('Opsi Jawaban')
                                    ->visible(fn (Forms\Get $get) =>
                                        $get('tipe_jawaban') === 'pilihan_ganda'
                                    )
                                    ->defaultItems(4)
                                    ->rules([
                                        fn (): \Closure => function (
                                            string $attribute,
                                            $value,
                                            \Closure $fail
                                        ) {
                                            $correctCount = collect($value)
                                                ->where('apakah_benar', true)
                                                ->count();
                                            if ($correctCount > 1) {
                                                $fail('Hanya satu jawaban yang boleh ditandai sebagai benar.');
                                            }
                                        },
                                    ]),
                            ])
                            ->itemLabel(fn (array $state): ?string =>
                                strip_tags($state['teks_pertanyaan'] ?? null)
                            )
                            ->collapsible()
                            ->collapsed(),
                    ]),
                ])->columnSpan(2),
            ]),
        ]);
    }

    /* =========================================================
     * TABLE
     * ========================================================= */
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
                        'pre-test'  => 'info',
                        'post-test' => 'success',
                        'survei'    => 'warning',
                        default     => 'gray',
                    }),

                Tables\Columns\TextColumn::make('pelatihan.nama_pelatihan')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('durasi_menit')
                    ->numeric()
                    ->label('Durasi'),

                Tables\Columns\TextColumn::make('pertanyaan_count')
                    ->counts('pertanyaan')
                    ->label('Jumlah Soal'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('tipe')
                    ->options([
                        'pre-test'  => 'Pre-Test',
                        'post-test' => 'Post-Test',
                        'survei'    => 'Survei',
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
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListTes::route('/'),
            'create' => Pages\CreateTes::route('/create'),
            'edit'   => Pages\EditTes::route('/{record}/edit'),
        ];
    }
}
