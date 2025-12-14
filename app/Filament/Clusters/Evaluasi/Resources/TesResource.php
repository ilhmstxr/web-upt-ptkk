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

class TesResource extends Resource
{
    protected static ?string $model = Tes::class;
    protected static ?string $cluster = Evaluasi::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    /**
     * Default opsi untuk skala likert 1-4 (editable).
     * NOTE: ini hanya default awal saat CREATE, tidak akan override saat EDIT.
     */
    protected static function defaultLikertOptions(): array
    {
        return [
            ['teks_opsi' => 'Sangat Tidak Setuju', 'apakah_benar' => false, 'gambar' => null],
            ['teks_opsi' => 'Tidak Setuju',        'apakah_benar' => false, 'gambar' => null],
            ['teks_opsi' => 'Setuju',              'apakah_benar' => false, 'gambar' => null],
            ['teks_opsi' => 'Sangat Setuju',       'apakah_benar' => false, 'gambar' => null],
        ];
    }

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
                            ->required()
                            ->reactive(),

                        Forms\Components\Select::make('pelatihan_id')
                            ->relationship('pelatihan', 'nama_pelatihan')
                            ->searchable()
                            ->required()
                            ->default(request()->query('pelatihan_id'))
                            ->disabled(fn (?string $operation) =>
                                $operation === 'edit' || request()->has('pelatihan_id')
                            ),

                        /**
                         * ✅ FIX: Kompetensi hanya untuk pre/post.
                         * - Kalau tipe=survei: tidak tampil, tidak required, dan disimpan NULL
                         */
                        Forms\Components\Select::make('kompetensi_id')
                            ->relationship('kompetensi', 'nama_kompetensi')
                            ->searchable()
                            ->default(request()->query('kompetensi_id'))
                            ->required(fn (Forms\Get $get) => $get('tipe') !== 'survei')
                            ->visible(fn (Forms\Get $get) => $get('tipe') !== 'survei')
                            // jangan ikut disimpan kalau survei
                            ->dehydrated(fn (Forms\Get $get) => $get('tipe') !== 'survei')
                            // ekstra aman: kalau survei, paksa null
                            ->dehydrateStateUsing(fn ($state, Forms\Get $get) =>
                                $get('tipe') === 'survei' ? null : $state
                            )
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
                                        'skala_likert'  => 'Skala Likert (1–4)',
                                        'teks_bebas'    => 'Essay',
                                    ])
                                    ->default('pilihan_ganda')
                                    ->reactive()
                                    /**
                                     * ✅ FIX UTAMA:
                                     * - Auto isi default Likert HANYA saat CREATE
                                     * - Tidak override saat EDIT
                                     * - Tidak override kalau opsi sudah ada (user bisa edit)
                                     */
                                    ->afterStateHydrated(function ($state, Forms\Get $get, Forms\Set $set, $component) {
                                        // hanya create
                                        if ($component->getLivewire()->getOperation() !== 'create') {
                                            return;
                                        }

                                        if ($state !== 'skala_likert') {
                                            return;
                                        }

                                        $current = $get('opsiJawabans');
                                        if (is_array($current) && count($current) > 0) {
                                            return;
                                        }

                                        $set('opsiJawabans', self::defaultLikertOptions());
                                    })
                                    ->afterStateUpdated(function ($state, Forms\Get $get, Forms\Set $set, $component) {
                                        // hanya create
                                        if ($component->getLivewire()->getOperation() !== 'create') {
                                            return;
                                        }

                                        if ($state !== 'skala_likert') {
                                            return;
                                        }

                                        $current = $get('opsiJawabans');
                                        if (is_array($current) && count($current) > 0) {
                                            return;
                                        }

                                        $set('opsiJawabans', self::defaultLikertOptions());
                                    }),

                                /**
                                 * ✅ Opsi Jawaban:
                                 * - tampil untuk pilihan_ganda dan skala_likert
                                 * - likert: teks editable, tidak ada jawaban benar
                                 * - PG: tetap bisa pilih jawaban benar (hanya satu)
                                 */
                                Forms\Components\Repeater::make('opsiJawabans')
                                    ->relationship()
                                    ->schema([
                                        Forms\Components\TextInput::make('teks_opsi')
                                            ->required()
                                            ->label('Teks Opsi'),

                                        // ✅ hanya untuk PG
                                        Forms\Components\Toggle::make('apakah_benar')
                                            ->label('Jawaban Benar')
                                            ->default(false)
                                            ->live()
                                            ->visible(fn (Forms\Get $get) =>
                                                $get('../../tipe_jawaban') === 'pilihan_ganda'
                                            )
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

                                        // optional gambar (boleh untuk PG & Likert)
                                        Forms\Components\FileUpload::make('gambar')
                                            ->image()
                                            ->directory('opsi-images')
                                            ->label('Gambar Opsi (Opsional)'),
                                    ])
                                    ->columns(2)
                                    ->label('Opsi Jawaban')
                                    ->visible(fn (Forms\Get $get) =>
                                        in_array($get('tipe_jawaban'), ['pilihan_ganda', 'skala_likert'], true)
                                    )
                                    // default items hanya untuk PG (likert auto-fill)
                                    ->defaultItems(fn (Forms\Get $get) =>
                                        $get('tipe_jawaban') === 'pilihan_ganda' ? 4 : 0
                                    )
                                    // validasi khusus PG: hanya 1 jawaban benar
                                    ->rules([
                                        fn (Forms\Get $get): \Closure => function (
                                            string $attribute,
                                            $value,
                                            \Closure $fail
                                        ) use ($get) {
                                            if ($get('tipe_jawaban') !== 'pilihan_ganda') {
                                                return;
                                            }

                                            $correctCount = collect($value)
                                                ->where('apakah_benar', true)
                                                ->count();

                                            if ($correctCount > 1) {
                                                $fail('Hanya satu jawaban yang boleh ditandai sebagai benar.');
                                            }
                                        },
                                    ])
                                    // saat likert: pastikan semua apakah_benar = false tersimpan
                                    ->mutateDehydratedStateUsing(function ($state, Forms\Get $get) {
                                        if ($get('tipe_jawaban') !== 'skala_likert' || !is_array($state)) {
                                            return $state;
                                        }

                                        return array_map(function ($item) {
                                            $item['apakah_benar'] = false;
                                            return $item;
                                        }, $state);
                                    }),
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

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery();
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
