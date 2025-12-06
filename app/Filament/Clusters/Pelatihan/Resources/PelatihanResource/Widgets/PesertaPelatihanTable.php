<?php

namespace App\Filament\Clusters\Pelatihan\Resources\PelatihanResource\Widgets;

use App\Models\PendaftaranPelatihan;
use Filament\Forms;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Model;

class PesertaPelatihanTable extends BaseWidget
{
    public ?Model $record = null;

    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                PendaftaranPelatihan::query()
                    ->with(['peserta.instansi', 'kompetensi'])
                    ->where('pelatihan_id', $this->record->id)
            )
            ->columns([
                Tables\Columns\TextColumn::make('peserta.nama')
                    ->label('Nama Peserta')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('peserta.instansi.asal_instansi')
                    ->label('Instansi')
                    ->searchable(),
                Tables\Columns\TextColumn::make('kompetensi.nama_kompetensi')
                    ->label('Kompetensi')
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Diterima' => 'success',
                        'Ditolak' => 'danger',
                        'Cadangan' => 'warning',
                        'Menunggu' => 'gray',
                        default => 'info',
                    }),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'Menunggu' => 'Menunggu',
                        'Diterima' => 'Diterima',
                        'Ditolak' => 'Ditolak',
                        'Cadangan' => 'Cadangan',
                    ]),
                Tables\Filters\SelectFilter::make('kompetensi_id')
                    ->label('Kompetensi')
                    ->relationship('kompetensi', 'nama_kompetensi', function ($query) {
                        // Filter kompetensi that are part of this pelatihan?
                        // Complex, but standard relationship filter works.
                        $query->whereHas('kompetensiPelatihan', function ($q) {
                            $q->where('pelatihan_id', $this->record->id);
                        });
                    }),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Tambah Peserta')
                    ->model(PendaftaranPelatihan::class)
                    ->form([
                        Forms\Components\Select::make('peserta_id')
                            ->label('Peserta')
                            ->relationship('peserta', 'nama')
                            ->searchable()
                            ->preload()
                            ->required(),
                         Forms\Components\Select::make('kompetensi_pelatihan_id')
                            ->label('Kompetensi')
                            ->options(function () {
                                return \App\Models\KompetensiPelatihan::with('kompetensi')
                                    ->where('pelatihan_id', $this->record->id)
                                    ->get()
                                    ->pluck('kompetensi.nama_kompetensi', 'id');
                            })
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set) {
                                // Auto fill kompetensi_id hidden field if needed
                                $kp = \App\Models\KompetensiPelatihan::find($state);
                                if ($kp) {
                                    $set('kompetensi_id', $kp->kompetensi_id);
                                }
                            }),
                        Forms\Components\Hidden::make('kompetensi_id'),
                        Forms\Components\Hidden::make('pelatihan_id')
                            ->default($this->record->id),
                        Forms\Components\Select::make('status')
                            ->options([
                                'Menunggu' => 'Menunggu',
                                'Diterima' => 'Diterima',
                                'Ditolak' => 'Ditolak',
                            ])
                            ->default('Diterima')
                            ->required(),
                    ])
                    ->mutateFormDataUsing(function (array $data) {
                        $data['pelatihan_id'] = $this->record->id;
                        return $data;
                    })
                    ->slideOver(),
            ])
            ->actions([
                Tables\Actions\EditAction::make()->slideOver(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }
}
