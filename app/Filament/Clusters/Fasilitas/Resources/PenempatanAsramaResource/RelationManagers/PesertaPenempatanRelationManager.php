<?php

namespace App\Filament\Clusters\Fasilitas\Resources\PenempatanAsramaResource\RelationManagers;

use App\Models\PendaftaranPelatihan;
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
                    ->label('Kode Regis')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('peserta.nama')
                    ->label('Nama')
                    ->searchable()
                    ->sortable()
                    ->wrap(),

                Tables\Columns\TextColumn::make('kelas')
                    ->label('Kelas')
                    ->badge()
                    ->color('gray')
                    ->sortable(),

                Tables\Columns\TextColumn::make('peserta.instansi.asal_instansi')
                    ->label('Asal Instansi')
                    ->default('-')
                    ->searchable()
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

                Tables\Columns\TextColumn::make('asrama_kamar')
                    ->label('Asrama / Kamar')
                    ->state(function (PendaftaranPelatihan $record) {
                        $penempatan = $record->penempatanAsramaAktif();

                        $kamar = $penempatan?->kamarPelatihan?->kamar;

                        if (! $kamar) {
                            return 'Belum dibagi';
                        }

                        $asramaName = $kamar->asrama->name ?? 'Asrama';
                        $kamarNo    = $kamar->nomor_kamar ?? '-';

                        return "{$asramaName} - Kamar {$kamarNo}";
                    })
                    ->wrap(),

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
                'penempatanAsrama.kamarPelatihan.kamar.asrama',
            ]);
    }
}
