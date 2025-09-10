<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Forms;
use Filament\Tables;
use Filament\Tables\Table;
use App\Models\Peserta;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class PembagianKamarPage extends Page implements Tables\Contracts\HasTable
{
    use Tables\Concerns\InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-home-modern';
    protected static ?string $navigationGroup = 'Pendaftaran';
    protected static string $view = 'filament.pages.pembagian-kamar';

    public array $konfigurasiKamar = [];

    public function mount(): void
    {
        // load default dari config
        $this->konfigurasiKamar = config('kamar');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(Peserta::query()->orderBy('id'))
            ->columns([
                Tables\Columns\TextColumn::make('nama')->label('Nama'),
                Tables\Columns\TextColumn::make('jenis_kelamin')->label('Jenis Kelamin'),
                Tables\Columns\TextColumn::make('kamar')
                    ->label('Kamar')
                    ->getStateUsing(fn ($record) => $this->assignKamar($record)),
                Tables\Columns\TextColumn::make('bed')
                    ->label('Bed')
                    ->getStateUsing(fn ($record) => $this->assignBed($record)),
            ])
            ->headerActions([
                Tables\Actions\Action::make('atur')
                    ->label('Atur Kamar & Bed')
                    ->form([
                        Forms\Components\Repeater::make('konfigurasiKamar')
                            ->label('Daftar Asrama')
                            ->schema([
                                Forms\Components\TextInput::make('nama')->label('Nama Asrama')->required(),
                                Forms\Components\Repeater::make('kamar')
                                    ->label('Kamar')
                                    ->schema([
                                        Forms\Components\TextInput::make('no')->numeric()->label('Nomor'),
                                        Forms\Components\TextInput::make('bed')->numeric()->label('Jumlah Bed'),
                                    ])
                                    ->defaultItems(1)
                                    ->columns(2),
                            ])
                            ->default(function () {
                                // ubah config bawaan jadi array repeater
                                return collect(config('kamar'))->map(function ($kamar, $blok) {
                                    return [
                                        'nama' => $blok,
                                        'kamar' => collect($kamar)->map(fn ($k) => [
                                            'no' => $k['no'],
                                            'bed' => is_numeric($k['bed']) ? $k['bed'] : null,
                                        ])->toArray(),
                                    ];
                                })->values()->toArray();
                            })
                            ->columns(1),
                    ])
                    ->action(function (array $data) {
                        $this->konfigurasiKamar = collect($data['konfigurasiKamar'])
                            ->mapWithKeys(fn ($asrama) => [
                                $asrama['nama'] => $asrama['kamar'],
                            ])
                            ->toArray();
                    }),

                Tables\Actions\Action::make('export_excel')
                    ->label('Export Excel')
                    ->icon('heroicon-o-document-text')
                    ->action(function () {
                        return Excel::download(new \App\Exports\RoomExport($this->konfigurasiKamar), 'pembagian-kamar.xlsx');
                    }),

                Tables\Actions\Action::make('export_pdf')
                    ->label('Export PDF')
                    ->icon('heroicon-o-printer')
                    ->action(function () {
                        $peserta = Peserta::orderBy('id')->get()->map(function ($p) {
                            $p->kamar = $this->assignKamar($p);
                            $p->bed = $this->assignBed($p);
                            return $p;
                        });

                        $pdf = Pdf::loadView('exports.room-pdf', [
                            'peserta' => $peserta,
                            'konfigurasiKamar' => $this->konfigurasiKamar,
                        ]);

                        return response()->streamDownload(
                            fn () => print($pdf->output()),
                            'pembagian-kamar.pdf'
                        );
                    }),
            ]);
    }

    protected function assignKamar($record)
    {
        $gender = $record->jenis_kelamin;
        $blokDipakai = $gender === 'Laki-laki'
            ? ['Melati Bawah', 'Tulip Bawah']
            : ['Mawar', 'Melati Atas', 'Tulip Atas'];

        $listKamar = collect($this->konfigurasiKamar)
            ->only($blokDipakai)
            ->map(function ($rooms, $blok) {
                return collect($rooms)->map(function ($r) use ($blok) {
                    $r['blok'] = $blok;
                    return $r;
                });
            })
            ->flatten(1)
            ->filter(fn ($k) => is_numeric($k['bed']) && $k['bed'] > 0)
            ->values();

        $peserta = Peserta::where('jenis_kelamin', $gender)->orderBy('id')->get();
        $index = $peserta->search(fn ($p) => $p->id === $record->id);

        $counter = 0;
        foreach ($listKamar as $kamar) {
            $capacity = $kamar['bed'];
            if ($index < $counter + $capacity) {
                return $kamar['blok'] . ' - No.' . $kamar['no'];
            }
            $counter += $capacity;
        }

        return 'Penuh';
    }

    protected function assignBed($record)
    {
        $gender = $record->jenis_kelamin;
        $blokDipakai = $gender === 'Laki-laki'
            ? ['Melati Bawah', 'Tulip Bawah']
            : ['Mawar', 'Melati Atas', 'Tulip Atas'];

        $listKamar = collect($this->konfigurasiKamar)
            ->only($blokDipakai)
            ->map(function ($rooms, $blok) {
                return collect($rooms)->map(function ($r) use ($blok) {
                    $r['blok'] = $blok;
                    return $r;
                });
            })
            ->flatten(1)
            ->filter(fn ($k) => is_numeric($k['bed']) && $k['bed'] > 0)
            ->values();

        $peserta = Peserta::where('jenis_kelamin', $gender)->orderBy('id')->get();
        $index = $peserta->search(fn ($p) => $p->id === $record->id);

        $counter = 0;
        foreach ($listKamar as $kamar) {
            $capacity = $kamar['bed'];
            if ($index < $counter + $capacity) {
                return 'Bed ' . (($index - $counter) + 1);
            }
            $counter += $capacity;
        }

        return '-';
    }
}
