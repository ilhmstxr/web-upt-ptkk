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
use Filament\Tables\Actions\Action as TableAction; // ✅ ini yang dipakai untuk headerActions

use Filament\Notifications\Notification; // ✅ buat notify dari closure

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
        // Arsip / read-only → form bisa kosong
        return $form->schema([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('peserta.nama')
                    ->label('Nama Peserta')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('peserta.jenis_kelamin')
                    ->label('Gender')
                    ->badge()
                    ->color(fn (?string $state) => $state === 'Perempuan' ? 'danger' : 'info'),

                Tables\Columns\TextColumn::make('pelatihan.nama_pelatihan')
                    ->label('Pelatihan')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('periode')
                    ->label('Periode')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('asrama.nama')
                    ->label('Asrama')
                    ->sortable(),

                Tables\Columns\TextColumn::make('kamar.nomor_kamar')
                    ->label('Kamar')
                    ->sortable(),

                Tables\Columns\TextColumn::make('kamar.lantai')
                    ->label('Lantai')
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
                            ->body('Penempatan kamar dibuat untuk pelatihan: '.$pelatihan->nama_pelatihan)
                            ->success()
                            ->send();
                    }),
            ])
            ->actions([
                // arsip read-only, kalau mau edit/hapus tinggal aktifkan:
                // Tables\Actions\EditAction::make(),
                // Tables\Actions\DeleteAction::make(),
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
