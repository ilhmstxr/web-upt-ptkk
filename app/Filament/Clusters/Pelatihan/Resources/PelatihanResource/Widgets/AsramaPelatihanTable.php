<?php

namespace App\Filament\Clusters\Pelatihan\Resources\PelatihanResource\Widgets;

use App\Models\PendaftaranPelatihan;
use App\Models\PenempatanAsrama;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class AsramaPelatihanTable extends BaseWidget
{
    public ?Model $record = null;

    protected int|string|array $columnSpan = 'full';
    protected static ?string $heading = 'Rekap Penempatan Asrama Peserta';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                PendaftaranPelatihan::query()
                    ->with([
                        'peserta.instansi',
                        'penempatanAsrama.kamar.asrama',
                    ])
                    ->where('pelatihan_id', $this->record->id)
            )
            ->columns([

                Tables\Columns\TextColumn::make('nomor_registrasi')
                    ->label('Kode Registrasi')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->badge()
                    ->color('primary'),

                Tables\Columns\TextColumn::make('peserta.nama')
                    ->label('Nama Peserta')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('peserta.jenis_kelamin')
                    ->label('Jenis Kelamin')
                    ->badge()
                    ->colors([
                        'info' => 'Laki-laki',
                        'warning' => 'Perempuan',
                    ]),

                Tables\Columns\TextColumn::make('peserta.instansi.asal_instansi')
                    ->label('Asal Sekolah / Instansi')
                    ->searchable()
                    ->wrap(),

                Tables\Columns\TextColumn::make('penempatanAsrama.kamar.nama_kamar')
                    ->label('Kamar')
                    ->formatStateUsing(fn ($state) => $state ?: 'Belum dibagi')
                    ->badge()
                    ->color(fn ($state) => $state ? 'success' : 'gray'),

                Tables\Columns\TextColumn::make('penempatanAsrama.kamar.asrama.nama_asrama')
                    ->label('Asrama')
                    ->formatStateUsing(fn ($state) => $state ?: 'Belum dibagi')
                    ->badge()
                    ->color(fn ($state) => $state ? 'success' : 'gray'),

            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Tempatkan Peserta')
                    ->icon('heroicon-o-plus')
                    ->form([
                        \Filament\Forms\Components\Select::make('pendaftaran_pelatihan_id')
                            ->label('Peserta (terdaftar di pelatihan ini)')
                            ->options(fn () =>
                                PendaftaranPelatihan::with('peserta.instansi')
                                    ->where('pelatihan_id', $this->record->id)
                                    ->get()
                                    ->mapWithKeys(fn ($pp) => [
                                        $pp->id => $pp->nomor_registrasi.' - '.$pp->peserta->nama.' ('.($pp->peserta->instansi->asal_instansi ?? '-').')'
                                    ])
                            )
                            ->searchable()
                            ->required(),

                        \Filament\Forms\Components\Select::make('kamar_id')
                            ->label('Kamar')
                            ->relationship('kamar', 'nama_kamar')
                            ->searchable()
                            ->preload()
                            ->required(),
                    ])
                    ->mutateFormDataUsing(function (array $data) {
                        // ambil info pendaftaran
                        $pp = PendaftaranPelatihan::findOrFail($data['pendaftaran_pelatihan_id']);

                        return [
                            'pelatihan_id' => $pp->pelatihan_id,
                            'peserta_id'   => $pp->peserta_id,
                            'kamar_id'     => $data['kamar_id'],
                            'periode'      => now()->year, // opsional
                        ];
                    })
                    ->using(fn (array $data) => PenempatanAsrama::create($data))
                    ->slideOver(),
            ])
            ->actions([
                Tables\Actions\DeleteAction::make()
                    ->label('Check-out / Hapus')
                    ->icon('heroicon-o-trash'),
            ]);
    }
}
