<?php

namespace App\Filament\Clusters\Fasilitas\Resources\PenempatanAsramaResource\RelationManagers;

use App\Models\PendaftaranPelatihan;
use App\Models\PenempatanAsrama;
use App\Models\Peserta;
use App\Models\Pelatihan;
use App\Services\AsramaAllocator;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Actions\Action as TableAction;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class PesertaPenempatanRelationManager extends RelationManager
{
    protected static string $relationship = 'pendaftaranPelatihan';
    protected static ?string $title = 'Peserta Pelatihan';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nomor_registrasi')
                    ->label('Nomor Registrasi')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('peserta.nama')
                    ->label('Nama')
                    ->searchable()
                    ->sortable()
                    ->wrap(),

                Tables\Columns\TextColumn::make('kompetensi')
                    ->label('Kompetensi')
                    ->state(function (PendaftaranPelatihan $record) {
                        return $record->kompetensi?->nama_kompetensi
                            ?? $record->kompetensiPelatihan?->kompetensi?->nama_kompetensi
                            ?? '-';
                    })
                    ->wrap(),

                Tables\Columns\TextColumn::make('peserta.jenis_kelamin')
                    ->label('Jenis Kelamin')
                    ->badge()
                    ->color(fn($state) => match (strtolower($state ?? '')) {
                        'laki-laki', 'l' => 'info',
                        'perempuan', 'p' => 'warning',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn($state) => $state ? ucfirst($state) : '-')
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('asrama')
                    ->label('Asrama')
                    ->state(function (PendaftaranPelatihan $record) {
                        $kamar = $record->penempatanAsramaAktif()?->kamarPelatihan?->kamar;
                        return $kamar?->asrama?->name ?? '-';
                    })
                    ->wrap(),

                Tables\Columns\TextColumn::make('kamar')
                    ->label('Kamar')
                    ->state(function (PendaftaranPelatihan $record) {
                        $kamar = $record->penempatanAsramaAktif()?->kamarPelatihan?->kamar;
                        return $kamar?->nomor_kamar ?? '-';
                    })
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('bed')
                    ->label('Bed')
                    ->state(function (PendaftaranPelatihan $record) {
                        $penempatan = $record->penempatanAsramaAktif();
                        $kpId = $penempatan?->kamar_pelatihan_id;
                        if (!$kpId) {
                            return '-';
                        }

                        static $bedCache = [];
                        if (!array_key_exists($kpId, $bedCache)) {
                            $bedCache[$kpId] = PenempatanAsrama::query()
                                ->where('pelatihan_id', $record->pelatihan_id)
                                ->where('kamar_pelatihan_id', $kpId)
                                ->orderBy('id')
                                ->pluck('peserta_id')
                                ->values()
                                ->all();
                        }

                        $idx = array_search($record->peserta_id, $bedCache[$kpId], true);
                        return $idx === false ? '-' : $idx + 1;
                    })
                    ->alignCenter(),

            ])
            ->actions([])
            ->bulkActions([]);
    }

    protected function getTableQuery(): Builder
    {
        /** @var Pelatihan $pelatihan */
        $pelatihan = $this->getOwnerRecord();

        return PendaftaranPelatihan::query()
            ->where('pelatihan_id', $pelatihan->id)
            ->with([
                'peserta.instansi',
                'kompetensi',
                'kompetensiPelatihan.kompetensi',
                'penempatanAsrama.kamarPelatihan.kamar.asrama',
            ]);
    }
}
