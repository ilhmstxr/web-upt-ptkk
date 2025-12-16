<?php

namespace App\Filament\Clusters\Fasilitas\Resources\PenempatanAsramaResource\RelationManagers;

use App\Models\PendaftaranPelatihan;
use App\Models\Peserta;
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
            ->modifyQueryUsing(function (Builder $query) {
                $owner = $this->getOwnerRecord();
                $pelatihanId = $owner->pelatihan_id ?? null;

                $query
                    ->when($pelatihanId, fn (Builder $q) => $q->where('pelatihan_id', $pelatihanId))
                    ->with([
                        'peserta.instansi',
                        'penempatanAsramas.kamarPelatihan.kamar.asrama',
                    ]);
            })

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
                    ->color(fn ($state) => match (strtolower($state ?? '')) {
                        'laki-laki', 'l' => 'info',
                        'perempuan', 'p' => 'warning',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn ($state) => $state ? ucfirst($state) : '-')
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('asrama_kamar')
                    ->label('Asrama / Kamar')
                    ->state(function (PendaftaranPelatihan $row) {
                        $penempatan = $row->relationLoaded('penempatanAsramaAktif')
                            ? $row->penempatanAsramaAktif
                            : $row->penempatanAsramaAktif()->first();

                        if (! $penempatan) return 'Belum dibagi';

                        $kamar = $penempatan->kamarPelatihan?->kamar;
                        if (! $kamar) return 'Belum dibagi';

                        $asramaName =
                            $kamar->asrama?->nama
                            ?? $kamar->asrama?->name
                            ?? $kamar->asrama?->nama_asrama
                            ?? 'Asrama';

                        $kamarNo =
                            $kamar->nomor_kamar
                            ?? $kamar->nomor
                            ?? $kamar->no_kamar
                            ?? '-';

                        return "{$asramaName} - Kamar {$kamarNo}";
                    })
                    ->wrap(),
            ])

            ->headerActions([
                TableAction::make('otomasi')
                    ->label('Otomasi Penempatan Asrama')
                    ->icon('heroicon-o-bolt')
                    ->color('success')
                    ->requiresConfirmation()
                    ->action(function (AsramaAllocator $allocator) {
                        $owner = $this->getOwnerRecord();
                        $pelatihanId = $owner->pelatihan_id ?? null;

                        if (! $pelatihanId) {
                            Notification::make()
                                ->title('Pelatihan tidak ditemukan pada record Penempatan Asrama')
                                ->danger()
                                ->send();
                            return;
                        }

                        $pesertaIds = PendaftaranPelatihan::query()
                            ->where('pelatihan_id', $pelatihanId)
                            ->pluck('peserta_id')
                            ->filter()
                            ->unique()
                            ->values();

                        $pesertaList = Peserta::query()
                            ->whereIn('id', $pesertaIds)
                            ->get();

                        if ($pesertaList->isEmpty()) {
                            Notification::make()
                                ->title('Tidak ada peserta untuk pelatihan ini')
                                ->warning()
                                ->send();
                            return;
                        }

                        $kamarConfig = session('kamars') ?? config('kamars');

                        $allocator->generateRoomsFromConfig($pelatihanId, $kamarConfig);
                        $result = $allocator->allocatePesertaPerPelatihan($pelatihanId);

                        $this->dispatch('$refresh');

                        Notification::make()
                            ->title('Otomasi selesai')
                            ->body(
                                'OK=' . ($result['ok'] ?? 0) .
                                ' | skipped=' . ($result['skipped_already_assigned'] ?? 0) .
                                ' | gagal=' . ($result['failed_full'] ?? 0)
                            )
                            ->success()
                            ->send();
                    }),
            ])
            ->actions([])
            ->bulkActions([]);
    }
}
