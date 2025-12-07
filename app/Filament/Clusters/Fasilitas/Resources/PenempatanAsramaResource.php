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
use Filament\Actions;
use Illuminate\Database\Eloquent\Builder;

class PenempatanAsramaResource extends Resource
{
    protected static ?string $model = PenempatanAsrama::class;
    protected static ?string $cluster = Fasilitas::class;

    protected static ?string $navigationIcon  = 'heroicon-o-home-modern';
    protected static ?string $navigationLabel = 'Penempatan Asrama';
    protected static ?string $modelLabel      = 'Penempatan Asrama';
    protected static ?string $pluralModelLabel = 'Penempatan Asrama';

    public static function form(Form $form): Form
    {
        // arsip → biasanya view only, form bisa kosong
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
                    ->color(fn (string $state) => $state === 'Perempuan' ? 'danger' : 'info'),

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
                // Pelatihan yang sudah selesai (arsip)
                Tables\Filters\Filter::make('selesai')
                    ->label('Pelatihan Selesai')
                    ->query(fn (Builder $query) =>
                        $query->whereHas('pelatihan', function ($q) {
                            $q->whereDate('tanggal_selesai', '<', now());
                        })
                    ),

                // Filter per pelatihan
                Tables\Filters\SelectFilter::make('pelatihan_id')
                    ->relationship('pelatihan', 'nama_pelatihan')
                    ->label('Filter Pelatihan'),
            ])
            ->headerActions([
                // TOMBOL OTOMASI ASRAMA
                Actions\Action::make('otomasi')
                    ->label('Jalankan Otomasi Penempatan')
                    ->icon('heroicon-o-bolt')
                    ->color('success')
                    ->requiresConfirmation()
                    ->action(function (AsramaAllocator $allocator) {
                        // contoh: pilih pelatihan paling baru
                        $pelatihan = Pelatihan::latest('id')->firstOrFail();

                        $peserta = Peserta::where('pelatihan_id', $pelatihan->id)
                            ->whereDoesntHave('penempatanAsrama')
                            ->get();

                        $allocator->allocate($pelatihan, $peserta);

                        $this->notify('success', 'Otomasi penempatan kamar berhasil dijalankan untuk pelatihan: '.$pelatihan->nama_pelatihan);
                    }),
            ])
            ->actions([
                // biasanya arsip cukup read only
                // kalau mau bisa hapus / edit, tinggal aktifkan:
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
            // tidak perlu create/edit kalau cuma arsip
        ];
    }

    // di AsramaResource.php
public static function getWidgets(): array
{
    return [
        \App\Filament\Clusters\Fasilitas\Resources\AsramaResource\Widgets\AsramaDenahWidget::class,
    ];
}

}
