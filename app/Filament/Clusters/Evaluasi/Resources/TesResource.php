<?php

namespace App\Filament\Clusters\Evaluasi\Resources;

use App\Filament\Clusters\Evaluasi;
use App\Filament\Clusters\Evaluasi\Resources\TesResource\Pages;
use App\Models\Percobaan;
use App\Models\Tes;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class TesResource extends Resource
{
    protected static ?string $model = Tes::class;
    protected static ?string $cluster = Evaluasi::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    /**
     * Default opsi Likert (untuk tipe_jawaban = skala_likert)
     */
    protected static function defaultLikertOptions(): array
    {
        return [
            ['teks_opsi' => 'Sangat Tidak Setuju', 'apakah_benar' => false, 'emoji' => '😡', 'gambar' => null],
            ['teks_opsi' => 'Tidak Setuju',        'apakah_benar' => false, 'emoji' => '😕', 'gambar' => null],
            ['teks_opsi' => 'Setuju',              'apakah_benar' => false, 'emoji' => '🙂', 'gambar' => null],
            ['teks_opsi' => 'Sangat Setuju',       'apakah_benar' => false, 'emoji' => '😍', 'gambar' => null],
        ];
    }

    /**
     * Helper untuk mendeteksi apakah halaman sedang create.
     * (Tetap mempertahankan behavior sebelumnya)
     */
    protected static function isCreating($component): bool
    {
        $livewire = $component->getLivewire();

        if (method_exists($livewire, 'isCreate')) {
            return $livewire->isCreate();
        }

        return property_exists($livewire, 'operation') && $livewire->operation === 'create';
    }

    /**
     * Ambil kategori unik dari state repeater "pertanyaan"
     */
    protected static function getKategoriOptionsFromRepeater(Forms\Get $get): array
    {
        $items = $get('../../pertanyaan') ?? [];
        if (!is_array($items)) {
            $items = [];
        }

        $list = collect($items)
            ->pluck('kategori')
            ->map(fn ($v) => trim((string) $v))
            ->filter(fn ($v) => $v !== '')
            ->unique()
            ->values();

        return $list->mapWithKeys(fn ($v) => [$v => $v])->toArray();
    }

    // =====================================================================
    // ✅ TAMBAHAN: Helper pengambilan nilai/percobaan yang pakai peserta_survei_id
    // =====================================================================

    /**
     * Query builder Percobaan yang "benar" untuk peserta:
     * - Jika peserta_survei_id ada, pakai itu.
     * - Jika tidak, fallback ke peserta_id.
     *
     * Kamu bisa pakai ini di controller/page lain untuk ambil skor/pre/post/survei.
     */
    public static function percobaanQueryForPeserta(
        int $tesId,
        ?int $pesertaId,
        ?int $pesertaSurveiId
    ): Builder {
        return Percobaan::query()
            ->where('tes_id', $tesId)
            ->where(function (Builder $q) use ($pesertaId, $pesertaSurveiId) {
                if (!empty($pesertaSurveiId)) {
                    $q->where('peserta_survei_id', $pesertaSurveiId);
                } else {
                    $q->where('peserta_id', $pesertaId);
                }
            });
    }

    /**
     * Ambil skor terakhir (latest) untuk peserta pada tes tertentu.
     * Mengurutkan dari waktu_selesai -> updated_at -> id sebagai fallback.
     */
    public static function ambilSkorTerakhir(
        int $tesId,
        ?int $pesertaId,
        ?int $pesertaSurveiId
    ): ?float {
        $row = self::percobaanQueryForPeserta($tesId, $pesertaId, $pesertaSurveiId)
            ->orderByDesc('waktu_selesai')
            ->orderByDesc('updated_at')
            ->orderByDesc('id')
            ->first(['skor']);

        return $row?->skor !== null ? (float) $row->skor : null;
    }

    /**
     * ✅ FIX yang dibutuhkan:
     * Masalah "TesResource salah kompetensi" biasanya karena penentuan tes_id-nya
     * cuma berdasarkan pelatihan+tipe (tanpa ngunci kompetensi), jadi ketuker antar kompetensi.
     *
     * Helper ini cari tes_id yang tepat:
     * - berdasarkan pelatihan_id + tipe
     * - untuk pre/post: WAJIB ngunci kompetensi_id bila ada
     * - survei: kompetensi dipaksa null (abaikan kompetensi)
     * - legacy 'survey' dinormalisasi ke 'survei'
     */
    public static function cariTesIdByKonteks(
        int $pelatihanId,
        ?int $kompetensiId,
        string $tipe
    ): ?int {
        $tipe = $tipe === 'survey' ? 'survei' : $tipe;

        return Tes::query()
            ->where('pelatihan_id', $pelatihanId)
            ->where('tipe', $tipe)
            ->when(
                $tipe !== 'survei' && !empty($kompetensiId),
                fn (Builder $q) => $q->where('kompetensi_id', $kompetensiId)
            )
            ->orderByDesc('id')
            ->value('id');
    }

    /**
     * ✅ Helper skor yang "pasti benar" untuk konteks pelatihan+kompetensi+tipe.
     * Pakai ini kalau kamu sebelumnya ambil tes_id secara "longgar" (cuma pelatihan+tipe).
     *
     * Contoh:
     *  TesResource::ambilSkorTerakhirByKonteks($pelatihanId, $kompetensiId, 'pre-test', $pesertaId, $pesertaSurveiId)
     */
    public static function ambilSkorTerakhirByKonteks(
        int $pelatihanId,
        ?int $kompetensiId,
        string $tipe,
        ?int $pesertaId,
        ?int $pesertaSurveiId
    ): ?float {
        $tesId = self::cariTesIdByKonteks($pelatihanId, $kompetensiId, $tipe);
        if (!$tesId) return null;

        return self::ambilSkorTerakhir($tesId, $pesertaId, $pesertaSurveiId);
    }

    // =====================================================================

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Grid::make(3)->schema([

                // LEFT
                Forms\Components\Group::make()->schema([
                    Forms\Components\Section::make('Pengaturan Tes')->schema([

                        Forms\Components\Select::make('tipe')
                            ->options([
                                'pre-test'  => 'Pre-Test',
                                'post-test' => 'Post-Test',
                                'survei'    => 'Survei',
                                // Optional legacy label (kalau DB lama sempat pakai "survey")
                                'survey'    => 'Survey (Legacy)',
                            ])
                            ->required()
                            ->live()
                            ->afterStateUpdated(function ($state, Forms\Set $set) {
                                // kalau jadi survei/survey, kompetensi harus kosong
                                if (in_array($state, ['survei', 'survey'], true)) {
                                    $set('kompetensi_id', null);
                                }
                            })
                            // Normalisasi: kalau user pilih "survey", simpan sebagai "survei"
                            // (biar konsisten, tapi tetap bisa baca data legacy)
                            ->dehydrateStateUsing(fn ($state) => $state === 'survey' ? 'survei' : $state),

                        Forms\Components\Select::make('pelatihan_id')
                            ->relationship('pelatihan', 'nama_pelatihan')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->default(fn () => request()->query('pelatihan_id'))
                            ->disabled(fn (?string $operation) => $operation === 'edit'),

                        /**
                         * ✅ FIX KOMPETENSI:
                         * - pre/post: tampil + wajib + tersimpan
                         * - survei: hilang + dipaksa null + tidak ikut tersimpan
                         */
                        Forms\Components\Select::make('kompetensi_id')
                            ->label('Kompetensi')
                            ->relationship('kompetensi', 'nama_kompetensi')
                            ->searchable()
                            ->preload()
                            ->default(fn () => request()->query('kompetensi_id'))
                            ->visible(fn (Forms\Get $get) => !in_array($get('tipe'), ['survei', 'survey'], true))
                            ->required(fn (Forms\Get $get) => in_array($get('tipe'), ['pre-test', 'post-test'], true))
                            ->dehydrated(fn (Forms\Get $get) => !in_array($get('tipe'), ['survei', 'survey'], true))
                            ->dehydrateStateUsing(fn ($state, Forms\Get $get) =>
                                in_array($get('tipe'), ['survei', 'survey'], true) ? null : $state
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

                    Forms\Components\View::make('filament.clusters.evaluasi.resources.tes-resource.partials.tips-card'),
                ])->columnSpan(1),

                // RIGHT
                Forms\Components\Group::make()->schema([
                    Forms\Components\Section::make('Detail Tes')->schema([
                        Forms\Components\TextInput::make('judul')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),

                        Forms\Components\RichEditor::make('deskripsi')
                            ->columnSpanFull(),
                    ]),

                    Forms\Components\Section::make('Bank Soal')->schema([
                        Forms\Components\Repeater::make('pertanyaan')
                            ->relationship()
                            ->schema([

                                // KATEGORI hanya survei
                                Forms\Components\Select::make('kategori')
                                    ->label('Kategori / Bagian (Sub Section)')
                                    ->placeholder('Pilih kategori atau tambah baru')
                                    ->searchable()
                                    ->live()
                                    ->options(fn (Forms\Get $get) => self::getKategoriOptionsFromRepeater($get))
                                    ->createOptionForm([
                                        Forms\Components\TextInput::make('nama_kategori')
                                            ->label('Kategori Baru')
                                            ->required()
                                            ->maxLength(255)
                                            ->placeholder('Contoh: PERSEPSI TERHADAP PROGRAM PELATIHAN'),
                                    ])
                                    ->createOptionUsing(fn (array $data) => trim((string) ($data['nama_kategori'] ?? '')))
                                    ->visible(fn (Forms\Get $get) => in_array($get('../../tipe'), ['survei', 'survey'], true))
                                    ->dehydrated(fn (Forms\Get $get) => in_array($get('../../tipe'), ['survei', 'survey'], true))
                                    ->dehydrateStateUsing(function ($state, Forms\Get $get) {
                                        if (!in_array($get('../../tipe'), ['survei', 'survey'], true)) {
                                            return null;
                                        }
                                        $v = trim((string) $state);
                                        return $v === '' ? null : $v;
                                    }),

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
                                    ->live()
                                    ->afterStateHydrated(function ($state, Forms\Get $get, Forms\Set $set, $component) {
                                        if (!self::isCreating($component)) return;
                                        if ($state !== 'skala_likert') return;

                                        $current = $get('opsiJawabans');
                                        if (!is_array($current) || count($current) === 0) {
                                            $set('opsiJawabans', self::defaultLikertOptions());
                                        }
                                    })
                                    ->afterStateUpdated(function ($state, Forms\Get $get, Forms\Set $set, $component) {
                                        if (!self::isCreating($component)) return;
                                        if ($state !== 'skala_likert') return;

                                        $current = $get('opsiJawabans');
                                        if (!is_array($current) || count($current) === 0) {
                                            $set('opsiJawabans', self::defaultLikertOptions());
                                        }
                                    }),

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
                                            ->visible(fn (Forms\Get $get) => $get('../../tipe_jawaban') === 'pilihan_ganda')
                                            ->afterStateUpdated(function ($state, Forms\Get $get, Forms\Set $set, $component) {
                                                if (!$state) return;

                                                $path = $component->getStatePath();
                                                $segments = explode('.', $path);
                                                $currentKey = $segments[count($segments) - 2];

                                                $items = $get('../../opsiJawabans') ?? [];
                                                foreach ($items as $key => $value) {
                                                    if ($key !== $currentKey && ($value['apakah_benar'] ?? false)) {
                                                        $targetPath = str_replace(".{$currentKey}.", ".{$key}.", $path);
                                                        $set($targetPath, false);
                                                    }
                                                }
                                            }),

                                        Forms\Components\FileUpload::make('gambar')
                                            ->image()
                                            ->directory('opsi-images')
                                            ->label('Gambar Opsi (Opsional)'),

                                        Forms\Components\TextInput::make('emoji')
                                            ->label('Emoji Likert')
                                            ->visible(fn (Forms\Get $get) => $get('../../tipe_jawaban') === 'skala_likert'),
                                    ])
                                    ->columns(2)
                                    ->label('Opsi Jawaban')
                                    ->visible(fn (Forms\Get $get) => in_array($get('tipe_jawaban'), ['pilihan_ganda', 'skala_likert'], true))
                                    ->defaultItems(fn (Forms\Get $get) => $get('tipe_jawaban') === 'pilihan_ganda' ? 4 : 0)
                                    ->rules([
                                        fn (Forms\Get $get): \Closure => function (string $attribute, $value, \Closure $fail) use ($get) {
                                            if ($get('tipe_jawaban') !== 'pilihan_ganda') return;
                                            $correctCount = collect($value)->where('apakah_benar', true)->count();
                                            if ($correctCount > 1) {
                                                $fail('Hanya satu jawaban yang boleh ditandai sebagai benar.');
                                            }
                                        },
                                    ])
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
                            ->itemLabel(fn (array $state): ?string => Str::limit(strip_tags($state['teks_pertanyaan'] ?? ''), 40))
                            ->collapsible()
                            ->collapsed(),
                    ]),
                ])->columnSpan(2),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('judul')->searchable()->sortable(),

                Tables\Columns\TextColumn::make('tipe')
                    ->badge()
                    ->formatStateUsing(fn (string $state) => $state === 'survey' ? 'survei' : $state)
                    ->color(fn (string $state): string => match ($state) {
                        'pre-test'  => 'info',
                        'post-test' => 'success',
                        'survei', 'survey' => 'warning',
                        default     => 'gray',
                    }),

                Tables\Columns\TextColumn::make('pelatihan.nama_pelatihan')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('durasi_menit')->numeric()->label('Durasi'),
                Tables\Columns\TextColumn::make('pertanyaan_count')->counts('pertanyaan')->label('Jumlah Soal'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('tipe')->options([
                    'pre-test'  => 'Pre-Test',
                    'post-test' => 'Post-Test',
                    'survei'    => 'Survei',
                    'survey'    => 'Survey (Legacy)',
                ]),
                Tables\Filters\SelectFilter::make('pelatihan')->relationship('pelatihan', 'nama_pelatihan'),
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
        // Tetap eager load seperti sebelumnya
        return parent::getEloquentQuery()->with('pertanyaan.opsiJawabans');
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
