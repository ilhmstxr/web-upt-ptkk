<?php

namespace App\Filament\Clusters\Pelatihan\Resources\PelatihanResource\Widgets;

use App\Models\PenempatanAsrama;
use Filament\Forms;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Model;

class AsramaPelatihanTable extends BaseWidget
{
    public ?Model $record = null;

    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                PenempatanAsrama::query()
                    ->whereHas('pendaftaranPelatihan', function ($q) {
                        $q->where('pelatihan_id', $this->record->id);
                    })
            )
            ->columns([
                Tables\Columns\TextColumn::make('pendaftaranPelatihan.peserta.nama')
                    ->label('Nama Peserta')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('kamar.nama_kamar')
                    ->label('Kamar')
                    ->searchable(),
                Tables\Columns\TextColumn::make('kamar.asrama.nama_asrama')
                    ->label('Asrama')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal Check-in')
                    ->date(),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Tempatkan Peserta')
                    ->form([
                        Forms\Components\Select::make('pendaftaran_id')
                            ->label('Peserta (yang terdaftar di pelatihan ini)')
                            ->options(function () {
                                return \App\Models\PendaftaranPelatihan::with('peserta')
                                    ->where('pelatihan_id', $this->record->id)
                                    // Filter out those already placed? Optional.
                                    ->whereDoesntHave('penempatanAsrama') // Assuming One-to-One placement per pendaftaran?
                                    ->get()
                                    ->mapWithKeys(function ($item) {
                                        return [$item->id => $item->peserta->nama . ' - ' . ($item->peserta->instansi->asal_instansi ?? '')];
                                    });
                            })
                            ->required()
                            ->searchable(),
                        Forms\Components\Select::make('kamar_id')
                            ->label('Kamar')
                            ->relationship('kamar', 'nama_kamar') // You might want to filter available rooms
                            ->required()
                            ->searchable()
                            ->preload(),
                    ])
                    ->mutateFormDataUsing(function (array $data) {
                        // Logic to assign
                        return $data;
                    })
                    ->slideOver(),
            ])
            ->actions([
                Tables\Actions\DeleteAction::make()->label('Check-out/Hapus'),
            ]);
    }
}
