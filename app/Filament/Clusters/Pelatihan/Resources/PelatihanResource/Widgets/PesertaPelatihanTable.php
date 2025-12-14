<?php

namespace App\Filament\Clusters\Pelatihan\Resources\PelatihanResource\Widgets;

use App\Models\PendaftaranPelatihan;
use Filament\Forms;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Model;

class PesertaPelatihanTable extends BaseWidget
{
    public ?Model $record = null;
    public ?int $kompetensiPelatihanId = null;

    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                PendaftaranPelatihan::query()
                    ->with(['peserta.instansi', 'kompetensi'])
                    ->where('pelatihan_id', $this->record->id)
                    ->when($this->kompetensiPelatihanId, fn($q) => $q->where('kompetensi_pelatihan_id', $this->kompetensiPelatihanId))
            )
            ->columns([
                Tables\Columns\TextColumn::make('nomor_registrasi')
                    ->label('No. Registrasi')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('peserta.nama')
                    ->label('Info Peserta')
                    ->description(fn(PendaftaranPelatihan $record): string => $record->peserta?->user?->email . ' | ' . $record->peserta?->no_hp)
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('kompetensiPelatihan.kompetensi.nama_kompetensi')
                    ->label('Kompetensi')
                    ->sortable(),
                Tables\Columns\TextColumn::make('tanggal_pendaftaran')
                    ->label('Tanggal Daftar')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status_pendaftaran')
                    ->label('Status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'Pending' => 'warning',
                        'Diterima' => 'success',
                        'Ditolak' => 'danger',
                        default => 'warning',
                    }),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'Diterima' => 'Diterima',
                        'Ditolak' => 'Ditolak',
                        'Cadangan' => 'Cadangan',
                    ]),
                Tables\Filters\SelectFilter::make('kompetensi_pelatihan_id')
                    ->label('Kompetensi')
                    ->options(function () {
                        return \App\Models\KompetensiPelatihan::with('kompetensi')
                            ->where('pelatihan_id', $this->record->id)
                            ->get()
                            ->pluck('kompetensi.nama_kompetensi', 'id');
                    })
                    ->query(function (\Illuminate\Database\Eloquent\Builder $query, array $data) {
                        return $query->when($data['value'], fn($q, $v) => $q->where('kompetensi_pelatihan_id', $v));
                    })
                    ->visible(fn() => is_null($this->kompetensiPelatihanId)),
            ])
            ->headerActions([])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->slideOver()
                    ->form([
                        Forms\Components\Select::make('status_pendaftaran')
                            ->label('Status Pendaftaran')
                            ->options([
                                'Pending' => 'Pending',
                                'Diterima' => 'Diterima',
                                'Ditolak' => 'Ditolak',
                                'Cadangan' => 'Cadangan',
                            ])
                            ->required()
                            ->native(false),
                        Forms\Components\Select::make('kompetensi_pelatihan_id')
                            ->label('Kompetensi')
                            ->options(function () {
                                return \App\Models\KompetensiPelatihan::with('kompetensi')
                                    ->where('pelatihan_id', $this->record->id)
                                    ->get()
                                    ->mapWithKeys(fn($item) => [$item->id => $item->kompetensi->nama_kompetensi ?? 'Unknown']);
                            })
                            ->searchable()
                            ->preload()
                            ->required(),
                    ]),
                Tables\Actions\DeleteAction::make(),
            ]);
    }
}
