<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TesPercobaanResource\Pages;
use App\Exports\TesPercobaanExport;
use App\Exports\TesPercobaanPdfExport;
use App\Models\Percobaan;
use Filament\Forms;
use Filament\Forms\Form as FilamentForm;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table as FilamentTable;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;

class TesPercobaanResource extends Resource
{
    protected static ?string $model = Percobaan::class;

    protected static ?string $navigationIcon  = 'heroicon-o-document-check';
    protected static ?string $navigationGroup = 'Hasil Kegiatan';
    protected static ?string $navigationLabel = 'Tes Percobaan / Nilai';

    public static function form(FilamentForm $form): FilamentForm
    {
        return $form->schema([
            Forms\Components\Select::make('peserta_id')
                ->label('Peserta')
                ->relationship('peserta', 'nama')
                ->searchable()
                ->required(),

            Forms\Components\Select::make('tes_id')
                ->label('Tes (pre/post/praktek/survei)')
                ->relationship('tes', 'judul')
                ->searchable()
                ->required(),

            Forms\Components\DateTimePicker::make('waktu_mulai')
                ->label('Waktu Mulai')
                ->required(),

            Forms\Components\DateTimePicker::make('waktu_selesai')
                ->label('Waktu Selesai')
                ->required(fn (Forms\Get $get) => filled($get('skor')))
                ->minDateTime(fn (Forms\Get $get) => $get('waktu_mulai')),

            Forms\Components\TextInput::make('skor')
                ->numeric()
                ->label('Skor')
                ->helperText('Nilai numerik. Biarkan kosong jika belum dinilai.'),
        ]);
    }

    public static function table(FilamentTable $table): FilamentTable
    {
        return $table
            ->modifyQueryUsing(function (Builder $query) {
                $with = [
                    'tes:id,judul,tipe,pelatihan_id',
                    'peserta:id,nama,bidang_id,instansi_id',
                    'pesertaSurvei:id,nama,bidang_id',
                    'peserta.bidang:id,nama_bidang',
                    'pesertaSurvei.bidang:id,nama_bidang',
                    'peserta.instansi:id,asal_instansi',
                ];

                if (method_exists(\App\Models\PesertaSurvei::class, 'instansi')) {
                    $with[] = 'pesertaSurvei.instansi:id,asal_instansi';
                }

                $query->with($with);
            })
            ->columns([
                TextColumn::make('no')
                    ->label('No')
                    ->rowIndex()
                    ->sortable(false),

                BadgeColumn::make('tipe_tes')
                    ->label('Tipe Tes')
                    ->getStateUsing(fn ($record) => $record->tes?->tipe ?? '-')
                    ->sortable(),

                TextColumn::make('peserta_display')
                    ->label('Peserta')
                    ->state(fn ($record) =>
                        $record->pesertaSurvei?->nama
                            ?? $record->peserta?->nama
                            ?? '-'
                    )
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query->where(function (Builder $nested) use ($search) {
                            $nested->whereHas('pesertaSurvei', fn (Builder $rel) => $rel->where('nama', 'like', "%{$search}%"))
                                   ->orWhereHas('peserta', fn (Builder $rel) => $rel->where('nama', 'like', "%{$search}%"));
                        });
                    })
                    ->sortable(),

                TextColumn::make('bidang')
                    ->label('Bidang')
                    ->badge()
                    ->state(fn ($record) =>
                        $record->pesertaSurvei?->bidang?->nama_bidang
                            ?? $record->peserta?->bidang?->nama_bidang
                            ?? '-'
                    )
                    ->searchable()
                    ->sortable(),

                TextColumn::make('instansi')
                    ->label('Instansi')
                    ->badge()
                    ->state(function ($record) {
                        $fromSurvei = null;
                        if (isset($record->pesertaSurvei) && method_exists(\App\Models\PesertaSurvei::class, 'instansi')) {
                            $fromSurvei = $record->pesertaSurvei?->instansi?->asal_instansi;
                        }
                        $fromPeserta = $record->peserta?->instansi?->asal_instansi;
                        return $fromSurvei ?: ($fromPeserta ?: '-');
                    })
                    ->searchable()
                    ->sortable(),

                TextColumn::make('skor')
                    ->label('Skor')
                    ->formatStateUsing(fn ($state) => $state ?? 'Belum dinilai')
                    ->sortable(),

                TextColumn::make('increase_percent')
                    ->label('Kenaikan (%)')
                    ->getStateUsing(function ($record) {
                        try {
                            $pesertaId = $record->peserta_id;
                            $pre = Percobaan::whereHas('tes', fn($q) => $q->where('tipe', 'pretest'))
                                ->where('peserta_id', $pesertaId)
                                ->orderByDesc('waktu_selesai')
                                ->value('skor');

                            $post = Percobaan::whereHas('tes', fn($q) => $q->where('tipe', 'posttest'))
                                ->where('peserta_id', $pesertaId)
                                ->orderByDesc('waktu_selesai')
                                ->value('skor');

                            if ($pre === null || $post === null) return '-';
                            if ((float)$pre == 0) return ($post > 0) ? '100%' : '0%';
                            $percent = round((($post - $pre) / (float)$pre) * 100, 2);
                            return ($percent > 0 ? '+' : '') . $percent . '%';
                        } catch (\Throwable $e) {
                            return '-';
                        }
                    })
                    ->sortable(),

                TextColumn::make('waktu_mulai')
                    ->label('Mulai')
                    ->dateTime('d M Y H:i')
                    ->sortable(),

                TextColumn::make('waktu_selesai')
                    ->label('Selesai')
                    ->formatStateUsing(fn ($state) => $state ? date('d M Y H:i', strtotime($state)) : 'Belum selesai')
                    ->sortable(),

                BadgeColumn::make('status')
                    ->label('Status')
                    ->getStateUsing(fn ($record) => $record->waktu_selesai ? 'Selesai' : 'Proses')
                    ->colors([
                        'success' => 'Selesai',
                        'warning' => 'Proses',
                    ])
                    ->icons([
                        'heroicon-o-check-circle' => 'Selesai',
                        'heroicon-o-clock' => 'Proses',
                    ]),

                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('tipe')
                    ->label('Tipe Tes')
                    ->options([
                        'pretest'  => 'Pretest',
                        'posttest' => 'Posttest',
                        'survei'   => 'Survei',
                        'praktek'  => 'Praktek',
                    ])
                    ->query(fn (Builder $query, array $data) =>
                        $query->when($data['value'] ?? null, fn (Builder $filtered, string $value) =>
                            $filtered->whereHas('tes', fn (Builder $tesQuery) =>
                                $tesQuery->where('tipe', $value)
                            )
                        )
                    ),

                Filter::make('nama_peserta')
                    ->label('Nama Peserta')
                    ->form([
                        TextInput::make('search_name')->placeholder('Ketik nama…')->live(debounce: 500),
                    ])
                    ->query(fn (Builder $query, array $data) =>
                        $query->when($data['search_name'] ?? null, function (Builder $filtered, string $term) {
                            $filtered->where(function (Builder $nested) use ($term) {
                                $nested->whereHas('pesertaSurvei', fn (Builder $rel) => $rel->where('nama', 'like', "%{$term}%"))
                                       ->orWhereHas('peserta', fn (Builder $rel) => $rel->where('nama', 'like', "%{$term}%"));
                            });
                        })
                    ),

                SelectFilter::make('bidang_id')
                    ->label('Bidang')
                    ->options(fn () => DB::table('bidang')->orderBy('nama_bidang')->pluck('nama_bidang', 'id')->toArray())
                    ->multiple()
                    ->searchable()
                    ->query(fn (Builder $query, array $data) =>
                        $query->when(($data['values'] ?? []) !== [], function (Builder $filtered) use ($data) {
                            $ids = $data['values'];
                            $filtered->where(function (Builder $nested) use ($ids) {
                                $nested->whereHas('pesertaSurvei', fn (Builder $rel) => $rel->whereIn('bidang_id', $ids))
                                       ->orWhereHas('peserta', fn (Builder $rel) => $rel->whereIn('bidang_id', $ids));
                            });
                        })
                    ),

                SelectFilter::make('instansi_id')
                    ->label('Instansi')
                    ->options(fn () => DB::table('instansi')->orderBy('asal_instansi')->pluck('asal_instansi', 'id')->toArray())
                    ->multiple()
                    ->searchable()
                    ->query(function (Builder $query, array $data) {
                        $ids = $data['values'] ?? [];
                        if ($ids === []) return;

                        $query->whereHas('peserta', fn (Builder $rel) => $rel->whereIn('instansi_id', $ids));

                        if (method_exists(\App\Models\PesertaSurvei::class, 'instansi')) {
                            $query->orWhereHas('pesertaSurvei.instansi', fn (Builder $rel) => $rel->whereIn('id', $ids));
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
                    ->query(fn (Builder $query, array $data) =>
                        $query->when($data['from'] ?? null, fn (Builder $filtered, $from) => $filtered->whereDate('waktu_mulai', '>=', $from))
                              ->when($data['until'] ?? null, fn (Builder $filtered, $until) => $filtered->whereDate('waktu_mulai', '<=', $until))
                    ),

                TernaryFilter::make('status')
                    ->label('Selesai?')
                    ->trueLabel('Selesai')
                    ->falseLabel('Proses')
                    ->queries(
                        true:  fn (Builder $query) => $query->whereNotNull('waktu_selesai'),
                        false: fn (Builder $query) => $query->whereNull('waktu_selesai'),
                        blank: fn (Builder $query) => $query,
                    ),
            ])
            ->filtersFormColumns(3)
            ->persistFiltersInSession()
            ->actions([
                Tables\Actions\ViewAction::make(),

                Tables\Actions\EditAction::make()
                    ->visible(fn() => auth()->user()?->hasRole('admin')),

                Tables\Actions\DeleteAction::make()
                    ->visible(fn() => auth()->user()?->hasRole('admin')),

                Tables\Actions\Action::make('export_excel')
                    ->label('Export Excel')
                    ->icon('heroicon-o-document-download')
                    ->action(function () {
                        $query = static::getModel()::query();
                        return Excel::download(new TesPercobaanExport($query), 'tes_percobaan.xlsx');
                    })
                    ->requiresConfirmation()
                    ->visible(fn() => auth()->user()?->can('export percobaan')),

                Tables\Actions\Action::make('export_pdf')
                    ->label('Export PDF')
                    ->icon('heroicon-o-document-text')
                    ->visible(fn() => auth()->user()?->can('export percobaan')),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make()
                    ->visible(fn() => auth()->user()?->hasRole('admin')),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

     public static function getPages(): array
    {
        return [
            'dashboard' => Pages\DashboardTesPercobaan::route('/'),
            'angkatan'  => Pages\AngkatanTesPage::route('/angkatan/{pelatihan}'),
            'bidang'    => Pages\BidangTesPage::route('/angkatan/{pelatihan}/{angkatan}'),
            'peserta'   => Pages\PesertaTesPage::route('/angkatan/{pelatihan}/{angkatan}/{bidang}'),
            'create'    => Pages\CreateTesPercobaan::route('/create'),
            'edit'      => Pages\EditTesPercobaan::route('/{record}/edit'),
        ];
    }
}
