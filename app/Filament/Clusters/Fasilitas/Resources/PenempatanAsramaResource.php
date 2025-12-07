<?php

namespace App\Filament\Clusters\Fasilitas\Resources;

use App\Filament\Clusters\Fasilitas;
use App\Filament\Clusters\Fasilitas\Resources\PenempatanAsramaResource\Pages;

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

class PenempatanAsramaResource extends Resource
{
    protected static ?string $model = PenempatanAsrama::class;
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
     * ✅ eager load biar kenceng + siap ambil data kesiswaan
     */
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with([
                'peserta.user',
                'peserta.instansi',
                'pelatihan',
                'asrama',
                'kamar',
                // kalau kamu punya relasi langsung dari peserta ke pendaftaran, ini membantu eager load:
                'peserta.pendaftaranPelatihans', // <-- kalau nama relasi beda, ganti di sini juga
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                // =========================
                // DATA KESISWAAN
                // =========================

                Tables\Columns\TextColumn::make('peserta.nama')
                    ->label('Nama Peserta')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('asal_sekolah')
                    ->label('Asal Sekolah')
                    ->getStateUsing(function (PenempatanAsrama $record) {
                        return $record->peserta?->instansi?->asal_instansi ?? '-';
                    })
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query->whereHas('peserta.instansi', fn($q) =>
                            $q->where('asal_instansi', 'like', "%{$search}%")
                        );
                    })
                    ->wrap(),

                Tables\Columns\TextColumn::make('kelas')
                    ->label('Kelas')
                    ->getStateUsing(function (PenempatanAsrama $record) {
                        $peserta = $record->peserta;
                        $pelatihanId = $record->pelatihan_id;

                        // ⚠️ kalau relasi peserta kamu bukan pendaftaranPelatihans, ganti di sini
                        $pendaftaran = $peserta?->pendaftaranPelatihans
                            ?->firstWhere('pelatihan_id', $pelatihanId);

                        return $pendaftaran?->kelas ?? '-';
                    })
                    ->toggleable(),

                Tables\Columns\TextColumn::make('pelatihan.nama_pelatihan')
                    ->label('Pelatihan')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('periode')
                    ->label('Periode')
                    ->toggleable(),

                // =========================
                // DATA PENEMPATAN (boleh kosong)
                // =========================

                Tables\Columns\TextColumn::make('asrama.nama')
                    ->label('Asrama')
                    ->getStateUsing(fn (PenempatanAsrama $r) => $r->asrama?->nama ?? '-') // ✅ kosong jika null
                    ->sortable(),

                Tables\Columns\TextColumn::make('kamar.nomor_kamar')
                    ->label('Kamar')
                    ->getStateUsing(fn (PenempatanAsrama $r) => $r->kamar?->nomor_kamar ?? '-') // ✅ kosong jika null
                    ->sortable(),

                Tables\Columns\TextColumn::make('kamar.lantai')
                    ->label('Lantai')
                    ->getStateUsing(fn (PenempatanAsrama $r) => $r->kamar?->lantai ?? '-') // ✅ kosong jika null
                    ->alignCenter()
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y H:i')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])

            ->filters([
                Tables\Filters\Filter::make('selesai')
                    ->label('Pelatihan Selesai')
                    ->query(fn (Builder $query) =>
                        $query->whereHas('pelatihan', function ($q) {
                            $q->whereDate('tanggal_selesai', '<', now());
                        })
                    ),

                Tables\Filters\SelectFilter::make('pelatihan_id')
                    ->relationship('pelatihan', 'nama_pelatihan')
                    ->label('Filter Pelatihan'),
            ])

            ->headerActions([
                TableAction::make('otomasi')
                    ->label('Jalankan Otomasi Penempatan')
                    ->icon('heroicon-o-bolt')
                    ->color('success')
                    ->requiresConfirmation()
                    ->action(function (AsramaAllocator $allocator) {

                        $pelatihan = Pelatihan::latest('id')->first();

                        if (! $pelatihan) {
                            Notification::make()
                                ->title('Pelatihan tidak ditemukan')
                                ->danger()
                                ->send();
                            return;
                        }

                        $peserta = Peserta::where('pelatihan_id', $pelatihan->id)
                            ->whereDoesntHave('penempatanAsrama')
                            ->get();

                        if ($peserta->isEmpty()) {
                            Notification::make()
                                ->title('Tidak ada peserta yang perlu ditempatkan')
                                ->warning()
                                ->send();
                            return;
                        }

                        $allocator->allocate($pelatihan, $peserta);

                        Notification::make()
                            ->title('Otomasi berhasil dijalankan')
                            ->body('Penempatan kamar dibuat untuk pelatihan: ' . $pelatihan->nama_pelatihan)
                            ->success()
                            ->send();
                    }),
            ])

            ->actions([
                // read-only
            ])

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
