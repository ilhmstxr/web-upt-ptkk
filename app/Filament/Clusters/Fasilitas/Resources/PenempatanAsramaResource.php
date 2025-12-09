<?php

namespace App\Filament\Clusters\Fasilitas\Resources;

use App\Filament\Clusters\Fasilitas;
use App\Filament\Clusters\Fasilitas\Resources\PenempatanAsramaResource\Pages;

use App\Models\PendaftaranPelatihan;
use App\Models\Pelatihan;
use App\Services\AsramaAllocator;

use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action as TableAction;
use Filament\Notifications\Notification;

use Illuminate\Database\Eloquent\Builder;

class PenempatanAsramaResource extends Resource
{
    /** Basis list = pendaftaran peserta per pelatihan */
    protected static ?string $model   = PendaftaranPelatihan::class;
    protected static ?string $cluster = Fasilitas::class;

    protected static ?string $navigationIcon   = 'heroicon-o-home-modern';
    protected static ?string $navigationLabel  = 'Penempatan Asrama';
    protected static ?string $modelLabel       = 'Penempatan Asrama';
    protected static ?string $pluralModelLabel = 'Penempatan Asrama';

    public static function form(Form $form): Form
    {
        return $form->schema([]); // read-only
    }

    /**
     * ✅ Eager load biar tabel cepat & kolom asrama bisa ambil dari relasi.
     */
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with([
            'peserta.instansi',
            'pelatihan',
            'peserta.penempatanAsramas.kamar.asrama', // penting untuk kolom Asrama/Kamar
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // KODE REGIS
                Tables\Columns\TextColumn::make('nomor_registrasi')
                    ->label('Kode Regis')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                // NAMA
                Tables\Columns\TextColumn::make('peserta.nama')
                    ->label('Nama')
                    ->searchable()
                    ->sortable()
                    ->wrap(),

                // KELAS (kolom di pendaftaran_pelatihan)
                Tables\Columns\TextColumn::make('kelas')
                    ->label('Kelas')
                    ->badge()
                    ->color('gray')
                    ->sortable(),

                // ASAL INSTANSI
                Tables\Columns\TextColumn::make('peserta.instansi.asal_instansi')
                    ->label('Asal Instansi')
                    ->default('-')
                    ->searchable()
                    ->wrap(),

                // JENIS KELAMIN
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

                // ASRAMA / KAMAR (tanpa query ulang, ambil dari eager load)
                Tables\Columns\TextColumn::make('ruangan_kamar')
                    ->label('Asrama / Kamar')
                    ->getStateUsing(function (PendaftaranPelatihan $record) {
                        $penempatan = $record->peserta?->penempatanAsramas
                            ?->firstWhere('pelatihan_id', $record->pelatihan_id);

                        if (! $penempatan || ! $penempatan->kamar) {
                            return 'Belum dibagi';
                        }

                        $asrama = $penempatan->kamar->asrama->nama ?? 'Asrama';
                        $kamar  = $penempatan->kamar->nomor_kamar ?? '-';

                        return "{$asrama} - Kamar {$kamar}";
                    })
                    ->wrap(),
            ])

            ->emptyStateHeading('Pilih pelatihan dulu untuk melihat penempatan.')
            ->emptyStateDescription('Gunakan filter "Pilih Pelatihan".')

            /**
             * ✅ Filter utama: tabel kosong sampai pelatihan dipilih
             */
            ->filters([
                Tables\Filters\SelectFilter::make('pelatihan_id')
                    ->label('Pilih Pelatihan')
                    ->relationship('pelatihan', 'nama_pelatihan') // pastikan relasi pelatihan() ada
                    ->searchable()
                    ->preload()
                    ->placeholder('— Pilih pelatihan dulu —')
                    ->query(function (Builder $query, array $data): Builder {
                        $pelatihanId = $data['value'] ?? null;

                        if (! $pelatihanId) {
                            return $query->whereRaw('1=0'); // kosong kalau belum pilih
                        }

                        return $query->where('pelatihan_id', $pelatihanId);
                    }),
            ])

            /**
             * ✅ Otomasi penempatan: pilih pelatihan → reallocate total
             */
            ->headerActions([
                TableAction::make('otomasi')
                    ->label('Otomasi Penempatan')
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

                        // ✅ Ambil SEMUA peserta pada pelatihan ini (tanpa filter status)
                        $peserta = PendaftaranPelatihan::with('peserta')
                            ->where('pelatihan_id', $pelatihanId)
                            ->get()
                            ->pluck('peserta')
                            ->filter()  // buang null kalau data rusak
                            ->values();

                        if ($peserta->isEmpty()) {
                            Notification::make()
                                ->title('Tidak ada peserta pada pelatihan ini')
                                ->warning()
                                ->send();
                            return;
                        }

                        // ✅ Re-allocate total (hapus lama + reset bed + bagi ulang)
                        $allocatedCount = $allocator->reallocate($pelatihan, $peserta);

                        // ✅ refresh tanpa hilangin filter
                        $livewire->dispatch('$refresh');

                        Notification::make()
                            ->title('Otomasi berhasil dijalankan')
                            ->body("Penempatan dibuat untuk {$allocatedCount} peserta.")
                            ->success()
                            ->send();
                    }),
            ])

            ->actions([]) // read-only
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
