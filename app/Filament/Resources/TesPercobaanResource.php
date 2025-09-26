<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TesPercobaanResource\Pages;
use App\Models\Percobaan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class TesPercobaanResource extends Resource
{
    protected static ?string $model = Percobaan::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard';
    protected static ?string $navigationGroup = 'Tes & Percobaan';
    protected static ?string $navigationLabel = 'Tes Percobaan';

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
            // eager load yang dipakai saja
            ->modifyQueryUsing(function (Builder $q) {
                $q->with([
                    'tes:id,judul,tipe',
                    'peserta:id,nama',
                    'pesertaSurvei:id,nama',
                    'peserta.bidang:id,nama_bidang',
                    'pesertaSurvei.bidang:id,nama_bidang',
                    // bawa hitungan pertanyaan ke dalam relasi tes
                    // 'tes' => fn($t) => $t->withCount('tesPertanyaan'),
                ]);
            })
            ->columns([
                Tables\Columns\TextColumn::make('tes.tipe')
                    ->label('Tipe')
                    ->badge()
                    ->sortable()
                    ->searchable(),

                // IF–ELSE peserta/pesertaSurvei berdasar tes.tipe
                Tables\Columns\TextColumn::make('peserta_display')
                    ->label('Peserta')
                    ->state(
                        fn($record) =>
                        $record->tes?->tipe === 'survei'
                            ? ($record->pesertaSurvei->nama ?? '-')
                            : ($record->peserta->nama ?? '-')
                    )
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query->where(function (Builder $q) use ($search) {
                            // survei -> cari di pesertaSurvei
                            $q->where(function (Builder $qq) use ($search) {
                                $qq->whereHas('tes', fn(Builder $t) => $t->where('tipe', 'survei'))
                                    ->whereHas('pesertaSurvei', fn(Builder $r) => $r->where('nama', 'like', "%{$search}%"));
                            })
                                // tes -> cari di peserta
                                ->orWhere(function (Builder $qq) use ($search) {
                                    $qq->whereHas('tes', fn(Builder $t) => $t->where('tipe', '!=', 'survei'))
                                        ->whereHas('peserta', fn(Builder $r) => $r->where('nama', 'like', "%{$search}%"));
                                });
                        });
                    }),
                Tables\Columns\TextColumn::make('bidang')
                    ->label('Tipe')
                    ->badge()
                    ->state(
                        fn($record) =>
                        // jika pesertaSurvei ada, pakai bidangnya; jika null, fallback ke peserta
                        $record->pesertaSurvei?->bidang?->nama_bidang
                            ?? $record->peserta?->bidang?->nama_bidang
                            ?? '-'
                    )
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query->where(function (Builder $q) use ($search) {
                            $q->whereHas('pesertaSurvei.bidang', fn(Builder $b) => $b->where('nama_bidang', 'like', "%{$search}%"))
                                ->orWhereHas('peserta.bidang', fn(Builder $b) => $b->where('nama_bidang', 'like', "%{$search}%"));
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
                    // $state sudah Carbon karena di-cast di model
                    ->formatStateUsing(fn($state) => $state?->format('d M Y H:i') ?? 'Belum selesai')
                    ->sortable(),


                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->getStateUsing(fn($record) => $record->waktu_selesai ? 'Selesai' : 'Proses')
                    ->colors([
                        'success' => 'Selesai',
                        'warning' => 'Proses',
                    ])
                    ->icons([
                        'heroicon-o-check-circle' => 'Selesai',
                        'heroicon-o-clock' => 'Proses',
                    ]),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->filters([])
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
            'index' => Pages\ListTesPercobaan::route('/'),
            'create' => Pages\CreateTesPercobaan::route('/create'),
            'edit' => Pages\EditTesPercobaan::route('/{record}/edit'),
        ];
    }
}
