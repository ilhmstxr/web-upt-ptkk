<?php

namespace App\Filament\Clusters\Fasilitas\Resources;

use App\Filament\Clusters\Fasilitas;
use App\Filament\Clusters\Fasilitas\Resources\PenempatanAsramaResource\Pages;

use App\Models\PendaftaranPelatihan;   // basis tabel
use App\Models\PenempatanAsrama;
use App\Models\Pelatihan;              // ✅ FIX: sebelumnya belum di-import
use App\Services\AsramaAllocator;

use Filament\Forms\Form;
use Filament\Resources\Resource;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action as TableAction;
use Filament\Notifications\Notification;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Schema;

class PenempatanAsramaResource extends Resource
{
    // ✅ basis tabel pendaftaran pelatihan
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

    /**
     * ✅ eager load relasi yang dibutuhkan tabel
     */
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

                // =========================
                // DATA REGISTRASI & PESERTA
                // =========================
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

                // =========================
                // DATA PENEMPATAN ASRAMA
                // =========================
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

            // =========================
            // EMPTY STATE
            // =========================
            ->emptyStateHeading('Pilih pelatihan dulu untuk melihat data penempatan.')
            ->emptyStateDescription('Gunakan filter "Pilih Pelatihan" di atas tabel.')

            // =========================
            // FILTER PELATIHAN
            // =========================
            ->filters([
                Tables\Filters\SelectFilter::make('pelatihan_id')
                    ->label('Pilih Pelatihan')
                    ->relationship('pelatihan', 'nama_pelatihan')
                    ->searchable()
                    ->preload()
                    ->placeholder('— Pilih pelatihan dulu —')
                    ->query(function (Builder $query, array $data): Builder {
                        if (empty($data['value'])) {
                            return $query->whereRaw('1=0'); // kosongkan tabel kalau belum pilih
                        }

                        return $query->where('pelatihan_id', $data['value']);
                    }),
            ])

            // =========================
            // HEADER ACTION OTOMASI
            // =========================
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

                        /**
                         * ✅ Ambil semua pendaftaran pelatihan tsb
                         * ✅ Status OPTIONAL + legacy aman:
                         *    - jika status_pendaftaran ADA → ambil semua kecuali "ditolak"
                         *      (termasuk null + variasi kapital)
                         *    - jika status_pendaftaran TIDAK ADA → tidak usah filter status
                         * ✅ hanya yang BELUM punya penempatan untuk pelatihan ini
                         */
                        $pendaftarans = PendaftaranPelatihan::with('peserta')
                            ->where('pelatihan_id', $pelatihanId)

                            ->when(
                                Schema::hasColumn('pendaftaran_pelatihan', 'status_pendaftaran'),
                                function ($q) {
                                    $q->where(function ($qq) {
                                        $qq->whereNull('status_pendaftaran')
                                           ->orWhereRaw('LOWER(status_pendaftaran) != ?', ['ditolak']);
                                    });
                                }
                            )

                            ->when(
                                !Schema::hasColumn('pendaftaran_pelatihan', 'status_pendaftaran')
                                && Schema::hasColumn('pendaftaran_pelatihan', 'status'),
                                function ($q) {
                                    $q->where(function ($qq) {
                                        $qq->whereNull('status')
                                           ->orWhereRaw('LOWER(status) != ?', ['ditolak']);
                                    });
                                }
                            )

                            ->whereDoesntHave('peserta.penempatanAsramas', function ($q) use ($pelatihanId) {
                                $q->where('pelatihan_id', $pelatihanId);
                            })
                            ->get();

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

                        // ✅ jalankan allocator (return jumlah yg ditempatkan)
                        $allocatedCount = $allocator->allocate($pelatihan, $peserta);

                        /**
                         * ✅ FIX: jangan resetTable()
                         * resetTable() akan menghapus filter pelatihan_id → tabel kosong lagi.
                         * Cukup refresh komponen.
                         */
                        $livewire->dispatch('$refresh');

                        Notification::make()
                            ->title('Otomasi berhasil dijalankan')
                            ->body("Penempatan dibuat untuk {$allocatedCount} peserta.")
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
