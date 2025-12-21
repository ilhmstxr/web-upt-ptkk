<?php

namespace App\Filament\Clusters\Pelatihan\Resources\PelatihanResource\Widgets;

use App\Models\PendaftaranPelatihan;
use Filament\Forms;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class NilaiPesertaPelatihanTable extends BaseWidget
{
    public ?Model $record = null;
    public ?string $kompetensiPelatihanId = null;

    protected int|string|array $columnSpan = 'full';

    public function mount(?Model $record = null, array $data = [], ?string $kompetensiPelatihanId = null): void
    {
        $this->record = $record ?? ($data['record'] ?? null);
        $this->kompetensiPelatihanId = $kompetensiPelatihanId ?? ($data['kompetensiPelatihanId'] ?? null);
    }

    protected function getTableQuery(): Builder
    {
        if (! $this->record?->getKey()) {
            return PendaftaranPelatihan::query()->whereRaw('1 = 0');
        }

        $query = PendaftaranPelatihan::query()
            ->where('pelatihan_id', $this->record->getKey())
            ->with([
                'peserta.user',
                'kompetensiPelatihan.kompetensi',
                'pelatihan',
            ]);

        if ($this->kompetensiPelatihanId) {
            $query->where('kompetensi_pelatihan_id', $this->kompetensiPelatihanId);
        }

        return $query;
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(fn() => $this->getTableQuery())
            ->columns([
                Tables\Columns\TextColumn::make('nomor_registrasi')
                    ->label('No. Registrasi')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('peserta.nama')
                    ->label('Info Peserta')
                    ->description(
                        fn(PendaftaranPelatihan $record) => ($record->peserta?->user?->email ?? '-') . ' | ' .
                            ($record->peserta?->no_hp ?? '-')
                    )
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('kompetensiPelatihan.kompetensi.nama_kompetensi')
                    ->label('Kompetensi')
                    ->sortable(),

                Tables\Columns\TextColumn::make('nilai_pre_test')
                    ->label('Nilai Pretest')
                    ->numeric(1)
                    ->sortable()
                    ->placeholder('-'),

                Tables\Columns\TextColumn::make('nilai_post_test')
                    ->label('Nilai Posttest')
                    ->numeric(1)
                    ->sortable()
                    ->placeholder('-'),

                Tables\Columns\TextColumn::make('nilai_praktek')
                    ->label('Nilai Praktek')
                    ->numeric(1)
                    ->sortable()
                    ->placeholder('-')
                    ->color('warning')
                    ->weight('bold'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('kompetensi_pelatihan_id')
                    ->label('Kompetensi')
                    ->relationship('kompetensiPelatihan.kompetensi', 'nama_kompetensi', fn(Builder $query) => $query->whereHas('kompetensiPelatihan', fn($q) => $q->where('pelatihan_id', $this->record?->getKey()))),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->form(PesertaPelatihanTable::getPesertaFormSchema()), // Reuse existing schema for details

                Tables\Actions\EditAction::make()
                    ->label('Edit Nilai')
                    ->icon('heroicon-o-pencil')
                    ->form([
                        Forms\Components\Section::make('Edit Nilai Peserta')
                            ->schema([
                                Forms\Components\Placeholder::make('info_peserta')
                                    ->label('Peserta')
                                    ->content(fn($record) => $record->peserta?->nama . ' (' . $record->nomor_registrasi . ')'),

                                Forms\Components\TextInput::make('nilai_pre_test')
                                    ->label('Nilai Pretest')
                                    ->numeric()
                                    ->maxValue(100),

                                Forms\Components\TextInput::make('nilai_post_test')
                                    ->label('Nilai Posttest')
                                    ->numeric()
                                    ->maxValue(100),

                                Forms\Components\TextInput::make('nilai_praktek')
                                    ->label('Nilai Praktek')
                                    ->numeric()
                                    ->maxValue(100),
                            ])->columns(2),
                    ]),

                Tables\Actions\DeleteAction::make(),
            ]);
    }
}
