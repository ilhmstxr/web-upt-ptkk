<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TesPercobaanResource\Pages;
use App\Filament\Widgets\BidangScoresChart;
use App\Filament\Widgets\BidangSummaryTable;
use App\Models\Percobaan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class TesPercobaanResource extends Resource
{
    protected static ?string $model = Percobaan::class;

    protected static ?string $navigationIcon  = 'heroicon-o-clipboard';
    protected static ?string $navigationGroup = 'Hasil Kegiatan';
    protected static ?string $navigationLabel = 'Nilai Peserta';

    protected function getHeaderWidgets(): array
    {
        return [
            // BidangSummaryTable::class,
            // BidangScoresChart::class,
        ];
    }
    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('peserta_id')
                ->label('Peserta')
                ->relationship('peserta', 'nama')
                ->searchable()
                ->required(),

            Forms\Components\Select::make('tes_id')
                ->label('Tes')
                ->relationship('tes', 'judul')
                ->searchable()
                ->required(),

            Forms\Components\DateTimePicker::make('waktu_mulai')
                ->label('Waktu Mulai')
                ->required(),

            Forms\Components\DateTimePicker::make('waktu_selesai')
                ->label('Waktu Selesai')
                ->required(fn(Get $get) => filled($get('skor')))
                ->minDateTime(fn(Get $get) => $get('waktu_mulai')),

            Forms\Components\TextInput::make('skor')
                ->numeric()
                ->label('Skor')
                ->required(fn(Get $get) => filled($get('waktu_selesai'))),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function (Builder $query) {
                // Terapkan default sort di sini
                // 1. Urutkan berdasarkan "apakah skor NULL?" secara menaik (ASC).
                //    - (skor IS NULL) akan jadi '0' (false) jika ADA NILAI.
                //    - (skor IS NULL) akan jadi '1' (true) jika NULL.
                //    - Jadi, yang ada nilai (0) akan tampil lebih dulu.
                $query->orderBy(DB::raw('skor IS NULL'), 'asc')
                    // 2. Untuk baris yang sama-sama punya nilai, urutkan skor-nya
                    //    dari tertinggi ke terendah (DESC).
                    ->orderBy('skor', 'desc');
            })
            ->columns([
                // DIGANTI: dari 'peserta_id' menjadi 'peserta.nama'
                Tables\Columns\TextColumn::make('peserta.nama')
                    ->label('Nama Peserta')
                    ->searchable()
                    ->sortable()
                    ->placeholder('-'), // Tampilkan '-' jika relasi kosong

                // DITAMBAHKAN: 'tes.bidang.nama_bidang'
                Tables\Columns\TextColumn::make('tes.bidang.nama_bidang')
                    ->label('Bidang (dari Tes)')
                    ->badge() // Badge cocok untuk kategori
                    ->searchable()
                    ->sortable()
                    ->placeholder('-'),

                // instansi
                Tables\Columns\TextColumn::make('peserta.instansi.asal_instansi')
                    ->label('Instansi')
                    ->badge() // Badge cocok untuk kategori
                    ->searchable()
                    ->sortable()
                    ->placeholder('-'),

                // Tables\Columns\TextColumn::make('tes_id')
                //     ->label('Tes ID')
                //     ->sortable(),

                Tables\Columns\TextColumn::make('tipe')
                    ->label('Tipe')
                    ->badge()
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('skor')
                    ->label('Skor')
                    ->placeholder('Belum dinilai')
                    // Kita buat sortable() kustom agar saat diklik,
                    // logikanya tetap benar
                    ->sortable(query: function (Builder $query, string $direction): Builder {
                        return $query
                            ->orderBy(DB::raw('skor IS NULL'), $direction === 'asc' ? 'desc' : 'asc')
                            ->orderBy('skor', $direction);
                    }),

                Tables\Columns\IconColumn::make('lulus')
                    ->label('Lulus')
                    ->boolean()
                    ->sortable(),


                // DIGANTI: dari 'pelatihan_id' menjadi 'tes.pelatihan.nama_pelatihan'
                Tables\Columns\TextColumn::make('tes.pelatihan.nama_pelatihan')
                    ->label('Nama Pelatihan (dari Tes)')
                    ->searchable()
                    ->sortable()
                    ->placeholder('-'),

                Tables\Columns\TextColumn::make('waktu_mulai')
                    ->label('Waktu Mulai')
                    ->dateTime('d M Y H:i')
                    ->sortable(),

                Tables\Columns\TextColumn::make('waktu_selesai')
                    ->label('Waktu Selesai')
                    ->dateTime('d M Y H:i')
                    ->placeholder('Belum selesai')
                    ->sortable(),
                Tables\Columns\TextColumn::make('pesan_kesan')
                    ->label('Pesan Kesan')
                    ->searchable()
                    ->limit(50) // Batasi teks agar tidak terlalu panjang
                    ->tooltip(fn($record) => $record->pesan_kesan), // Tampilkan full di tooltip

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y H:i')
                    ->sortable(),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Diperbarui')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            // HAPUS ->defaultSort() DARI SINI
            ->filters([
                SelectFilter::make('tipe')
                    ->label('Tipe')
                    ->options([
                        // Sesuaikan dengan nilai enum di ERD Anda
                        'survey' => 'Survey',
                        'pre-test' => 'Pre-test',
                        'post-test' => 'Post-test',
                    ])
                    ->searchable(),

                Filter::make('skor_range')
                    ->label('Skor')
                    ->form([
                        TextInput::make('min')->numeric()->placeholder('Min'),
                        TextInput::make('max')->numeric()->placeholder('Max'),
                    ])
                    ->query(function (Builder $query, array $data) {
                        $min = $data['min'] ?? null;
                        $max = $data['max'] ?? null;
                        if ($min !== null && $max !== null) {
                            $query->whereBetween('skor', [$min, $max]);
                        } elseif ($min !== null) {
                            $query->where('skor', '>=', $min);
                        } elseif ($max !== null) {
                            $query->where('skor', '<=', $max);
                        }
                    }),

                Filter::make('mulai_range')
                    ->label('Mulai')
                    ->form([
                        DatePicker::make('from')->label('Dari'),
                        DatePicker::make('until')->label('Sampai'),
                    ])
                    ->query(
                        fn(Builder $query, array $data) =>
                        $query->when($data['from'] ?? null, fn(Builder $filtered, $from) => $filtered->whereDate('waktu_mulai', '>=', $from))
                            ->when($data['until'] ?? null, fn(Builder $filtered, $until) => $filtered->whereDate('waktu_mulai', '<=', $until))
                    ),

                TernaryFilter::make('status')
                    ->label('Selesai?')
                    ->trueLabel('Selesai')
                    ->falseLabel('Proses')
                    ->queries(
                        true: fn(Builder $query) => $query->whereNotNull('waktu_selesai'),
                        false: fn(Builder $query) => $query->whereNull('waktu_selesai'),
                        blank: fn(Builder $query) => $query,
                    ),
            ])
            ->filtersFormColumns(3)
            ->persistFiltersInSession()
            ->actions([
                Tables\Actions\ViewAction::make(),
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
            'index'  => Pages\ListTesPercobaan::route('/'),
            'create' => Pages\CreateTesPercobaan::route('/create'),
            'edit'   => Pages\EditTesPercobaan::route('/{record}/edit'),
        ];
    }
}
