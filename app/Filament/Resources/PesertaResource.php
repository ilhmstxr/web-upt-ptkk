<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PesertaResource\Pages;
use App\Models\Peserta;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;
use AlperenErsoy\FilamentExport\Actions\FilamentExportHeaderAction;
use AlperenErsoy\FilamentExport\Actions\FilamentExportBulkAction;

class PesertaResource extends Resource
{
    protected static ?string $model = Peserta::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationGroup = 'Pendaftaran';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // ... tidak saya ubah bagian form karena sudah oke
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama')->searchable(),
                Tables\Columns\TextColumn::make('pelatihan.nama_pelatihan')->sortable(),
                Tables\Columns\TextColumn::make('instansi.asal_instansi')->sortable(),
                Tables\Columns\TextColumn::make('email'),
                Tables\Columns\TextColumn::make('kamar_virtual')
                    ->label('Kamar')
                    ->getStateUsing(fn ($record) => self::assignKamar($record)),
                Tables\Columns\TextColumn::make('bed_virtual')
                    ->label('Bed')
                    ->getStateUsing(fn ($record) => self::assignBed($record)),
            ])
            ->headerActions([
                Action::make('atur_kamar')
                    ->label('Atur Jumlah Kamar & Bed')
                    ->form(function () {
                        $kamars = session('kamars') ?? config('kamar');

                        return [
                            Forms\Components\Repeater::make('kamars')
                                ->label('Daftar Asrama & Kamar')
                                ->schema([
                                    Forms\Components\TextInput::make('blok')
                                        ->disabled()
                                        ->dehydrated(true),

                                    Forms\Components\TextInput::make('no')
                                        ->disabled()
                                        ->dehydrated(true),

                                    Forms\Components\TextInput::make('bed')
                                        ->numeric()
                                        ->label('Jumlah Bed'),
                                ])
                                ->default(
                                    collect($kamars)->flatMap(function ($rooms, $blok) {
                                        return collect($rooms)->map(function ($room) use ($blok) {
                                            return [
                                                'blok' => $blok,
                                                'no'   => $room['no'],
                                                'bed'  => is_numeric($room['bed']) ? (int) $room['bed'] : null,
                                            ];
                                        });
                                    })->values()->toArray()
                                )
                                ->columns(3),
                        ];
                    })
                    ->action(function (array $data) {
                        session([
                            'kamars' => collect($data['kamars'])
                                ->groupBy('blok')
                                ->map(fn ($rooms) => $rooms->map(fn ($r) => [
                                    'no' => $r['no'],
                                    'bed' => (int) $r['bed'],
                                ])->toArray())
                                ->toArray()
                        ]);
                    }),

                FilamentExportHeaderAction::make('export'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                FilamentExportBulkAction::make('export'),
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    /**
     * Menentukan kamar berdasarkan gender dan kapasitas bed
     */
    protected static function assignKamar($record)
    {
        $kamars = session('kamars') ?? config('kamar');
        $gender = $record->jenis_kelamin;

        $blokDipakai = $gender === 'Laki-laki'
            ? ['Melati Bawah', 'Tulip Bawah']
            : ['Mawar', 'Melati Atas', 'Tulip Atas'];

        $listKamar = collect($kamars)
            ->only($blokDipakai)
            ->map(function ($rooms, $blok) {
                return collect($rooms)->map(function ($r) use ($blok) {
                    return [
                        'blok' => $blok,
                        'no'   => $r['no'],
                        'bed'  => (int) $r['bed'], // dipaksa int
                    ];
                });
            })
            ->flatten(1)
            ->filter(fn($k) => $k['bed'] > 0)
            ->values();

        $pesertas = Peserta::where('jenis_kelamin', $gender)->orderBy('id')->get();
        $index = $pesertas->search(fn ($p) => $p->id === $record->id);

        $counter = 0;
        foreach ($listKamar as $kamar) {
            $capacity = (int) $kamar['bed'];
            if ($index < $counter + $capacity) {
                return $kamar['blok'] . ' - No.' . $kamar['no'];
            }
            $counter += $capacity;
        }

        return 'Penuh';
    }

    /**
     * Menentukan nomor bed dalam kamar
     */
    protected static function assignBed($record)
    {
        $kamars = session('kamars') ?? config('kamar');

        $pesertas = Peserta::where('jenis_kelamin', $record->jenis_kelamin)
            ->orderBy('id')
            ->get();

        $index = $pesertas->search(fn ($p) => $p->id === $record->id);

        $kamar = self::assignKamar($record);
        if ($kamar === 'Penuh') {
            return '-';
        }

        [$blok, $noText] = explode(' - No.', $kamar);
        $no = (int) $noText;
        $rooms = collect($kamars[$blok] ?? [])->map(fn ($r) => [
            'no' => $r['no'],
            'bed' => (int) $r['bed'],
        ]);

        $capacity = $rooms->firstWhere('no', $no)['bed'] ?? 0;

        // hitung offset peserta sebelum kamar ini
        $listKamar = collect($kamars)
            ->map(fn ($rs, $b) => collect($rs)->map(fn ($r) => [
                'blok' => $b,
                'no'   => $r['no'],
                'bed'  => (int) $r['bed'],
            ]))
            ->flatten(1)
            ->filter(fn ($r) => $r['bed'] > 0)
            ->values();

        $counter = 0;
        foreach ($listKamar as $room) {
            if ($room['blok'] === $blok && $room['no'] == $no) {
                // nomor bed = selisih index dengan jumlah peserta sebelumnya + 1
                return 'Bed ' . (($index - $counter) + 1);
            }
            $counter += $room['bed'];
        }

        return '-';
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPesertas::route('/'),
            'create' => Pages\CreatePeserta::route('/create'),
            'edit' => Pages\EditPeserta::route('/{record}/edit'),
        ];
    }
}
