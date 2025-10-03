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
            BidangSummaryTable::class,
            BidangScoresChart::class,
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
                // Eager-load yang dipakai
                $with = [
                    'tes:id,judul,tipe',
                    'peserta:id,nama,bidang_id,instansi_id',
                    'pesertaSurvei:id,nama,bidang_id',
                    'peserta.bidang:id,nama_bidang',
                    'pesertaSurvei.bidang:id,nama_bidang',
                    'peserta.instansi:id,asal_instansi',
                ];

                // Tambahkan instansi untuk PesertaSurvei hanya jika relasinya ada
                if (method_exists(\App\Models\PesertaSurvei::class, 'instansi')) {
                    $with[] = 'pesertaSurvei.instansi:id,asal_instansi';
                }

                $query->with($with);
            })
            ->columns([
                // Nomor urut di paling kiri
                Tables\Columns\TextColumn::make('no')
                    ->label('No')
                    ->rowIndex()
                    ->sortable(false),

                Tables\Columns\TextColumn::make('tes.tipe')
                    ->label('Tipe')
                    ->badge()
                    ->sortable()
                    ->searchable(),

                // Nama peserta: prioritas PesertaSurvei, fallback ke Peserta
                Tables\Columns\TextColumn::make('peserta_display')
                    ->label('Peserta')
                    ->state(
                        fn($record) =>
                        $record->pesertaSurvei?->nama
                            ?? $record->peserta?->nama
                            ?? '-'
                    )
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query->where(function (Builder $nested) use ($search) {
                            $nested->whereHas('pesertaSurvei', fn(Builder $rel) => $rel->where('nama', 'like', "%{$search}%"))
                                ->orWhereHas('peserta', fn(Builder $rel) => $rel->where('nama', 'like', "%{$search}%"));
                        });
                    })
                    ->sortable(),

                // Bidang: prioritas milik PesertaSurvei, fallback ke Peserta
                Tables\Columns\TextColumn::make('bidang')
                    ->label('Bidang')
                    ->badge()
                    ->state(
                        fn($record) =>
                        $record->pesertaSurvei?->bidang?->nama_bidang
                            ?? $record->peserta?->bidang?->nama_bidang
                            ?? '-'
                    )
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query->where(function (Builder $nested) use ($search) {
                            $nested->whereHas('pesertaSurvei.bidang', fn(Builder $b) => $b->where('nama_bidang', 'like', "%{$search}%"))
                                ->orWhereHas('peserta.bidang', fn(Builder $b) => $b->where('nama_bidang', 'like', "%{$search}%"));
                        });
                    })
                    ->sortable(),

                // Instansi: IF–ELSE untuk Survei & Tes, aman walau relasi instansi() di PesertaSurvei belum ada
                Tables\Columns\TextColumn::make('instansi')
                    ->label('Instansi')
                    ->badge()
                    ->state(function ($record) {
                        // Coba ambil dari PesertaSurvei->instansi (jika relasi ada & data ada)
                        $fromSurvei = null;
                        if (isset($record->pesertaSurvei) && method_exists(\App\Models\PesertaSurvei::class, 'instansi')) {
                            $fromSurvei = $record->pesertaSurvei?->instansi?->asal_instansi;
                        }
                        // Fallback: Peserta->instansi
                        $fromPeserta = $record->peserta?->instansi?->asal_instansi;

                        return $fromSurvei ?: ($fromPeserta ?: '-');
                    })
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query->where(function (Builder $nested) use ($search) {
                            // Cari di instansi milik Peserta
                            $nested->whereHas(
                                'peserta.instansi',
                                fn(Builder $rel) =>
                                $rel->where('asal_instansi', 'like', "%{$search}%")
                            );

                            // Jika PesertaSurvei punya relasi instansi(), ikut cari juga
                            if (method_exists(\App\Models\PesertaSurvei::class, 'instansi')) {
                                $nested->orWhereHas(
                                    'pesertaSurvei.instansi',
                                    fn(Builder $rel) =>
                                    $rel->where('asal_instansi', 'like', "%{$search}%")
                                );
                            }
                        });
                    })
                    ->sortable(),

                Tables\Columns\TextColumn::make('skor')
                    ->label('Skor')
                    ->formatStateUsing(fn($state) => $state ?? 'Belum dinilai'),

                Tables\Columns\TextColumn::make('waktu_mulai')
                    ->label('Mulai')
                    ->dateTime('d M Y H:i')
                    ->sortable(),

                Tables\Columns\TextColumn::make('waktu_selesai')
                    ->label('Selesai')
                    ->formatStateUsing(fn($state) => $state?->format('d M Y H:i') ?? 'Belum selesai')
                    ->sortable(),

                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->getStateUsing(fn($record) => $record->waktu_selesai ? 'Selesai' : 'Proses')
                    ->colors(['success' => 'Selesai', 'warning' => 'Proses'])
                    ->icons(['heroicon-o-check-circle' => 'Selesai', 'heroicon-o-clock' => 'Proses']),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('tipe')
                    ->label('Tipe Tes')
                    ->options(['tes' => 'Tes', 'survei' => 'Survei'])
                    ->searchable()
                    ->query(
                        fn(Builder $query, array $data) =>
                        $query->when(
                            $data['value'] ?? null,
                            fn(Builder $filtered, string $value) =>
                            $filtered->whereHas('tes', fn(Builder $tesQuery) => $tesQuery->where('tipe', $value))
                        )
                    ),

                Filter::make('nama_peserta')
                    ->label('Nama Peserta')
                    ->form([
                        TextInput::make('search_name')->placeholder('Ketik nama…')->live(debounce: 500),
                    ])
                    ->query(
                        fn(Builder $query, array $data) =>
                        $query->when($data['search_name'] ?? null, function (Builder $filtered, string $term) {
                            $filtered->where(function (Builder $nested) use ($term) {
                                $nested->whereHas('pesertaSurvei', fn(Builder $rel) => $rel->where('nama', 'like', "%{$term}%"))
                                    ->orWhereHas('peserta', fn(Builder $rel) => $rel->where('nama', 'like', "%{$term}%"));
                            });
                        })
                    ),

                SelectFilter::make('bidang_id')
                    ->label('Bidang')
                    ->options(fn() => DB::table('bidang')->orderBy('nama_bidang')->pluck('nama_bidang', 'id')->toArray())
                    ->multiple()
                    ->searchable()
                    ->query(
                        fn(Builder $query, array $data) =>
                        $query->when(($data['values'] ?? []) !== [], function (Builder $filtered) use ($data) {
                            $ids = $data['values'];
                            $filtered->where(function (Builder $nested) use ($ids) {
                                $nested->whereHas('pesertaSurvei', fn(Builder $rel) => $rel->whereIn('bidang_id', $ids))
                                    ->orWhereHas('peserta', fn(Builder $rel) => $rel->whereIn('bidang_id', $ids));
                            });
                        })
                    ),

                // Filter instansi by ID (via peserta.instansi_id). Jika PesertaSurvei punya relasi instansi(), ikutkan juga.
                SelectFilter::make('instansi_id')
                    ->label('Instansi')
                    ->options(fn() => DB::table('instansi')->orderBy('asal_instansi')->pluck('asal_instansi', 'id')->toArray())
                    ->multiple()
                    ->searchable()
                    ->query(function (Builder $query, array $data) {
                        $ids = $data['values'] ?? [];
                        if ($ids === []) return;

                        $query->whereHas('peserta', fn(Builder $rel) => $rel->whereIn('instansi_id', $ids));

                        if (method_exists(\App\Models\PesertaSurvei::class, 'instansi')) {
                            $query->orWhereHas('pesertaSurvei.instansi', fn(Builder $rel) => $rel->whereIn('id', $ids));
                        }
                    }),

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
