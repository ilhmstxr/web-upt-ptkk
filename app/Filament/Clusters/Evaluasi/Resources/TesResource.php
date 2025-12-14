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
                            ->disabled(
                                fn(?string $operation) =>
                                $operation === 'edit' || request()->has('pelatihan_id')
                            ),

                        Forms\Components\Select::make('kompetensi_id')
                            ->relationship('kompetensi', 'nama_kompetensi')
                            ->searchable()
                            ->required()
                            ->default(request()->query('kompetensi_id'))
                            ->disabled(
                                fn(?string $operation) =>
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
                         * BANK SOAL (KATEGORI -> PERTANYAAN)
                         * ===================== */
                    Forms\Components\Repeater::make('kelompokPertanyaan')
                        ->label('Kelompok Pertanyaan')
                        ->relationship('kelompokPertanyaan')
                        ->schema([
                            Forms\Components\TextInput::make('nama_kategori')
                                ->label('Nama Kategori')
                                ->required()
                                ->placeHolder('Contoh: Pengetahuan Umum'),

                            Forms\Components\Repeater::make('pertanyaan')
                                ->relationship('pertanyaan')
                                ->schema([
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
                                        // Auto-select 'skala_likert' if parent Tes type is 'survei'
                                        ->default(fn(Forms\Get $get) => $get('../../../tipe') === 'survei' ? 'skala_likert' : 'pilihan_ganda')
                                        ->reactive(),

                                    /* ===== OPSI JAWABAN ===== */
                                    Forms\Components\Repeater::make('opsiJawabans')
                                        ->relationship('opsiJawabans')
                                        ->schema([
                                            Forms\Components\TextInput::make('teks_opsi')
                                                ->required()
                                                ->label('Teks Opsi'),

                                            Forms\Components\Toggle::make('apakah_benar')
                                                ->label('Jawaban Benar')
                                                ->default(false)
                                                ->live()
                                                // Ensure only one correct answer for basic PG (optional for Likert)
                                                ->afterStateUpdated(function ($state, Forms\Get $get, Forms\Set $set, $component) {
                                                    if (!$state) return;
                                                    $path = $component->getStatePath();
                                                    // logic to uncheck others
                                                    $segments = explode('.', $path);
                                                    $currentKey = $segments[count($segments) - 2];
                                                    $items = $get('../../opsiJawabans');
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
                                                ->label('Gambar Opsi'),
                                        ])
                                        ->columns(2)
                                        ->label('Opsi Jawaban')
                                        // Visible only if NOT Essay
                                        ->visible(fn(Forms\Get $get) => $get('tipe_jawaban') !== 'teks_bebas')
                                        // Auto-fill defaults for Survey
                                        ->default(function (Forms\Get $get) {
                                            $tipeJawaban = $get('tipe_jawaban');
                                            // If Survey (Likert) -> 4 options
                                            // 4 = Terbaik, 1 = Terburuk
                                            if ($tipeJawaban === 'skala_likert') {
                                                return [
                                                    ['teks_opsi' => 'Sangat Baik', 'apakah_benar' => false],
                                                    ['teks_opsi' => 'Baik', 'apakah_benar' => false],
                                                    ['teks_opsi' => 'Cukup', 'apakah_benar' => false],
                                                    ['teks_opsi' => 'Kurang', 'apakah_benar' => false],
                                                ];
                                            }
                                            return []; // standard default
                                        }),
                                ])
                                ->label('Daftar Pertanyaan')
                                ->itemLabel(fn(array $state) => strip_tags($state['teks_pertanyaan'] ?? null))
                                ->collapsible()
                                ->collapsed(),
                        ])
                        ->itemLabel(fn(array $state) => $state['nama_kategori'] ?? 'Kategori Baru')
                        ->collapsible(),
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
                    ->color(fn(string $state): string => match ($state) {
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
