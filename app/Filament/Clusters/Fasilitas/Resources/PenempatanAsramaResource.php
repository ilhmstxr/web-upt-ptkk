<?php

namespace App\Filament\Clusters\Fasilitas\Resources;

use App\Filament\Clusters\Fasilitas;
use App\Filament\Clusters\Fasilitas\Resources\PenempatanAsramaResource\Pages;

use App\Models\PendaftaranPelatihan;
use App\Models\PenempatanAsrama;
use App\Models\Pelatihan;
use App\Models\Peserta;
use App\Services\AsramaAllocator;

use Filament\Forms\Form;
use Filament\Resources\Resource;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action as TableAction;
use Filament\Notifications\Notification;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class PenempatanAsramaResource extends Resource
{
    protected static ?string $model = PendaftaranPelatihan::class;
    protected static ?string $cluster = Fasilitas::class;

    protected static ?string $navigationIcon   = 'heroicon-o-home-modern';
    protected static ?string $navigationLabel  = 'Penempatan Asrama';
    protected static ?string $modelLabel       = 'Penempatan Asrama';
    protected static ?string $pluralModelLabel = 'Penempatan Asrama';

    public static function form(Form $form): Form
    {
        return $form->schema([]); // read-only
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with([
                'peserta.instansi',
                'pelatihan',
            ]);
    }

    public static function table(Table $table): Table
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
                    ->sortable(),

                Tables\Columns\TextColumn::make('peserta.instansi.asal_instansi')
                    ->label('Asal Sekolah')
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

                Tables\Columns\TextColumn::make('ruangan_kamar')
                    ->label('Ruangan Kamar Asrama')
                    ->getStateUsing(function (PendaftaranPelatihan $record) {
                        $pesertaId   = $record->peserta_id;
                        $pelatihanId = $record->pelatihan_id;

                        $penempatan = PenempatanAsrama::query()
                            ->with(['kamar.asrama'])
                            ->where('peserta_id', $pesertaId)
                            ->where('pelatihan_id', $pelatihanId)
                            ->first();

                        if (! $penempatan || ! $penempatan->kamar) {
                            return 'Belum dibagi';
                        }

                        $asrama = $penempatan->kamar->asrama->nama ?? 'Asrama';
                        $kamar  = $penempatan->kamar->nomor_kamar ?? '-';

                        return "{$asrama} - Kamar {$kamar}";
                    })
                    ->wrap()
                    ->sortable(false),
            ])

            ->emptyStateHeading('Pilih pelatihan dulu untuk melihat data penempatan.')
            ->emptyStateDescription('Gunakan filter "Pilih Pelatihan" di atas tabel.')

            ->filters([
                Tables\Filters\SelectFilter::make('pelatihan_id')
                    ->label('Pilih Pelatihan')
                    ->relationship('pelatihan', 'nama_pelatihan')
                    ->searchable()
                    ->preload()
                    ->placeholder('— Pilih pelatihan dulu —')
                    ->query(function (Builder $query, array $data): Builder {
                        if (empty($data['value'])) {
                            return $query->whereRaw('1=0');
                        }
                        return $query->where('pelatihan_id', $data['value']);
                    }),
            ])

            ->headerActions([
                TableAction::make('otomasi')
                    ->label('Jalankan Otomasi Penempatan')
                    ->icon('heroicon-o-bolt')
                    ->color('success')
                    ->requiresConfirmation()
                    ->action(function (AsramaAllocator $allocator, Tables\Contracts\HasTable $livewire) {

                        $pelatihanId = $livewire->getTableFilterState('pelatihan_id')['value'] ?? null;

                        if (! $pelatihanId) {
                            Notification::make()
                                ->title('Pilih pelatihan dulu')
                                ->warning()
                                ->send();
                            return;
                        }

                        $pelatihan = Pelatihan::find($pelatihanId);

                        if (! $pelatihan) {
                            Notification::make()
                                ->title('Pelatihan tidak ditemukan')
                                ->danger()
                                ->send();
                            return;
                        }

                        // ✅ basis pendaftaran agar 1:1 dengan resource table
                        $pendaftarans = PendaftaranPelatihan::with('peserta')
                            ->where('pelatihan_id', $pelatihanId)
                            ->where('status_pendaftaran', 'Diterima')
                            ->whereDoesntHave('penempatanAsrama', fn($q) =>
                                $q->where('pelatihan_id', $pelatihanId)
                            )
                            ->get();

                        /** @var \Illuminate\Support\Collection<int, Peserta> $peserta */
                        $peserta = $pendaftarans
                            ->pluck('peserta')
                            ->filter()
                            ->values();

                        if ($peserta->isEmpty()) {
                            Notification::make()
                                ->title('Tidak ada peserta yang perlu ditempatkan')
                                ->warning()
                                ->send();
                            return;
                        }

                        // jalankan allocator
                        $allocator->allocate($pelatihan, $peserta);

                        // ✅ refresh tabel (cara aman Filament/Livewire)
                        if (method_exists($livewire, 'resetTable')) {
                            $livewire->resetTable();
                        }
                        $livewire->dispatch('$refresh');

                        Notification::make()
                            ->title('Otomasi berhasil dijalankan')
                            ->body('Penempatan kamar dibuat untuk pelatihan: ' . $pelatihan->nama_pelatihan)
                            ->success()
                            ->send();
                    }),
            ])

            ->actions([])

            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPenempatanAsramas::route('/'),
        ];
    }
}
