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
use Illuminate\Support\Str;

class TesResource extends Resource
{
    protected static ?string $model = Tes::class;
    protected static ?string $cluster = Evaluasi::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static function defaultLikertOptions(): array
    {
        return [
            ['teks_opsi' => 'Sangat Tidak Setuju', 'apakah_benar' => false, 'emoji' => '😡', 'gambar' => null],
            ['teks_opsi' => 'Tidak Setuju', 'apakah_benar' => false, 'emoji' => '😕', 'gambar' => null],
            ['teks_opsi' => 'Setuju', 'apakah_benar' => false, 'emoji' => '🙂', 'gambar' => null],
            ['teks_opsi' => 'Sangat Setuju', 'apakah_benar' => false, 'emoji' => '😍', 'gambar' => null],
        ];
    }

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
        if (!is_array($items))
            $items = [];

        $list = collect($items)
            ->pluck('kategori')
            ->map(fn($v) => trim((string) $v))
            ->filter(fn($v) => $v !== '')
            ->unique()
            ->values();

        return $list->mapWithKeys(fn($v) => [$v => $v])->toArray();
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Grid::make(3)->schema([
                // LEFT
                Forms\Components\Group::make()->schema([
                    Forms\Components\Section::make('Pengaturan Tes')->schema([

                        Forms\Components\Select::make('tipe')
                            ->options([
                                'pre-test' => 'Pre-Test',
                                'post-test' => 'Post-Test',
                                'survei' => 'Survei',
                            ])
                            ->required()
                            ->live()
                            ->afterStateUpdated(function ($state, Forms\Set $set) {
                                // kalau jadi survei, kompetensi harus kosong
                                if ($state === 'survei') {
                                    $set('kompetensi_pelatihan_id', null);
                                }
                            }),

                        Forms\Components\Select::make('pelatihan_id')
                            ->relationship('pelatihan', 'nama_pelatihan')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->default(fn() => request()->query('pelatihan_id'))
                            // pelatihan boleh kamu kunci saat edit (opsional)
                            ->disabled(fn(?string $operation) => $operation === 'edit'),

                        /**
                         * ✅ FIX KOMPETENSI:
                         * - pre/post: tampil + wajib + tersimpan + BISA DIEDIT
                         * - survei: hilang + tidak tersimpan + dipaksa null
                         */
                        Forms\Components\Select::make('kompetensi_pelatihan_id')
                            ->relationship('kompetensiPelatihan', 'nama_kompetensi')
                            ->searchable()
                            ->preload()
                            ->default(fn() => request()->query('kompetensi_pelatihan_id'))
                            ->visible(fn(Forms\Get $get) => $get('tipe') !== 'survei')
                            ->required(fn(Forms\Get $get) => in_array($get('tipe'), ['pre-test', 'post-test'], true))
                            ->dehydrated(fn(Forms\Get $get) => $get('tipe') !== 'survei')
                            ->dehydrateStateUsing(function ($state, Forms\Get $get) {
                                // kalau survei => simpan null
                                if ($get('tipe') === 'survei')
                                    return null;
                                return $state;
                            })
                            // ✅ INI PENTING: JANGAN disabled di edit, biar bisa diklik
                            ->disabled(false),

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
                                    ->options(fn(Forms\Get $get) => self::getKategoriOptionsFromRepeater($get))
                                    ->createOptionForm([
                                        Forms\Components\TextInput::make('nama_kategori')
                                            ->label('Kategori Baru')
                                            ->required()
                                            ->maxLength(255)
                                            ->placeholder('Contoh: PERSEPSI TERHADAP PROGRAM PELATIHAN'),
                                    ])
                                    ->createOptionUsing(fn(array $data) => trim((string) ($data['nama_kategori'] ?? '')))
                                    ->visible(fn(Forms\Get $get) => $get('../../tipe') === 'survei')
                                    ->dehydrated(fn(Forms\Get $get) => $get('../../tipe') === 'survei')
                                    ->dehydrateStateUsing(function ($state, Forms\Get $get) {
                                        if ($get('../../tipe') !== 'survei')
                                            return null;
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
                                        'skala_likert' => 'Skala Likert (Survei)',
                                        'teks_bebas' => 'Essay',
                                    ])
                                    ->default('pilihan_ganda')
                                    ->live()
                                    ->afterStateHydrated(function ($state, Forms\Get $get, Forms\Set $set, $component) {
                                        if (!self::isCreating($component))
                                            return;
                                        if ($state !== 'skala_likert')
                                            return;

                                        $current = $get('opsiJawabans');
                                        if (!is_array($current) || count($current) === 0) {
                                            $set('opsiJawabans', self::defaultLikertOptions());
                                        }
                                    })
                                    ->afterStateUpdated(function ($state, Forms\Get $get, Forms\Set $set, $component) {
                                        if (!self::isCreating($component))
                                            return;
                                        if ($state !== 'skala_likert')
                                            return;

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
                                            ->visible(fn(Forms\Get $get) => $get('../../tipe_jawaban') === 'pilihan_ganda')
                                            ->afterStateUpdated(function ($state, Forms\Get $get, Forms\Set $set, $component) {
                                                if (!$state)
                                                    return;

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
                                            ->visible(fn(Forms\Get $get) => $get('../../tipe_jawaban') === 'skala_likert'),
                                    ])
                                    ->columns(2)
                                    ->label('Opsi Jawaban')
                                    ->visible(fn(Forms\Get $get) => in_array($get('tipe_jawaban'), ['pilihan_ganda', 'skala_likert'], true))
                                    ->defaultItems(fn(Forms\Get $get) => $get('tipe_jawaban') === 'pilihan_ganda' ? 4 : 0)
                                    ->rules([
                                        fn(Forms\Get $get): \Closure => function (string $attribute, $value, \Closure $fail) use ($get) {
                                            if ($get('tipe_jawaban') !== 'pilihan_ganda')
                                                return;
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
                            ->itemLabel(fn(array $state): ?string => Str::limit(strip_tags($state['teks_pertanyaan'] ?? ''), 40))
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
                    ->color(fn(string $state): string => match ($state) {
                        'pre-test' => 'info',
                        'post-test' => 'success',
                        'survei' => 'warning',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('pelatihan.nama_pelatihan')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('durasi_menit')->numeric()->label('Durasi'),
                Tables\Columns\TextColumn::make('pertanyaan_count')->counts('pertanyaan')->label('Jumlah Soal'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('tipe')->options([
                    'pre-test' => 'Pre-Test',
                    'post-test' => 'Post-Test',
                    'survei' => 'Survei',
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
        return parent::getEloquentQuery()->with('pertanyaan.opsiJawabans');
    }

    public static function getRelations(): array
    {
        return [];
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
