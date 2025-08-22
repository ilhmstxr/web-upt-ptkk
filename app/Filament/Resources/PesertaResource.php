<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PesertaResource\Pages;
use App\Models\Peserta;
use App\Models\Pelatihan;
use App\Models\Bidang;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Tables\Actions\Action;
use AlperenErsoy\FilamentExport\Actions\FilamentExportHeaderAction;
use AlperenErsoy\FilamentExport\Actions\FilamentExportBulkAction;

class PesertaResource extends Resource
{
    protected static ?string $model = Peserta::class;
    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationGroup = 'Pendaftaran';

    /** ==================== FORM ==================== */
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Pendaftaran')
                    ->columns(2)
                    ->schema([
                        Forms\Components\Select::make('pelatihan_id')
                            ->relationship('pelatihan', 'nama_pelatihan')
                            ->required(),
                    ]),

                Forms\Components\Section::make('Data Diri Peserta')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('nama')->required(),
                        Forms\Components\TextInput::make('nik')->required()->unique(ignoreRecord: true),
                        Forms\Components\TextInput::make('tempat_lahir')->required(),
                        Forms\Components\DatePicker::make('tanggal_lahir')->required(),
                        Forms\Components\Select::make('jenis_kelamin')
                            ->options([
                                'Laki-laki' => 'Laki-laki',
                                'Perempuan' => 'Perempuan',
                            ])
                            ->required(),
                        Forms\Components\TextInput::make('agama')->required(),
                        Forms\Components\TextInput::make('no_hp')->required()->tel(),
                        Forms\Components\TextInput::make('email')->required()->email()->unique(ignoreRecord: true),
                        Forms\Components\Textarea::make('alamat')->required()->columnSpanFull(),
                    ]),

                Forms\Components\Section::make('Data Instansi')
                    ->relationship('instansi')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('asal_instansi')->required(),
                        Forms\Components\TextInput::make('bidang_keahlian')->required(),
                        Forms\Components\TextInput::make('kelas')->required(),
                        Forms\Components\TextInput::make('cabang_dinas_wilayah')->required(),
                        Forms\Components\Textarea::make('alamat_instansi')->required()->columnSpanFull(),
                    ]),
            ]);
    }

    /** ==================== INFOLIST ==================== */
    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Informasi Pendaftaran')
                    ->columns(2)
                    ->schema([
                        Infolists\Components\TextEntry::make('pelatihan.nama_pelatihan'),
                        Infolists\Components\TextEntry::make('instansi.asal_instansi'),
                    ]),

                Infolists\Components\Section::make('Data Diri Peserta')
                    ->columns(2)
                    ->schema([
                        Infolists\Components\TextEntry::make('nama'),
                        Infolists\Components\TextEntry::make('nik'),
                        Infolists\Components\TextEntry::make('tempat_lahir'),
                        Infolists\Components\TextEntry::make('tanggal_lahir'),
                        Infolists\Components\TextEntry::make('jenis_kelamin'),
                        Infolists\Components\TextEntry::make('agama'),
                        Infolists\Components\TextEntry::make('no_hp'),
                        Infolists\Components\TextEntry::make('email'),
                        Infolists\Components\TextEntry::make('alamat')->columns(),
                    ]),

                Infolists\Components\Section::make('Preview Lampiran Berkas')
                    ->columns(2)
                    ->schema([
                        Infolists\Components\ViewEntry::make('lampiran.pas_foto')
                            ->label('Pas Foto')
                            ->view('filament.infolists.components.file-preview'),
                        Infolists\Components\ViewEntry::make('lampiran.fc_ktp')
                            ->label('KTP')
                            ->view('filament.infolists.components.file-preview'),
                        Infolists\Components\ViewEntry::make('lampiran.fc_ijaza')
                            ->label('Ijazah')
                            ->view('filament.infolists.components.file-preview'),
                        Infolists\Components\ViewEntry::make('lampiran.fc_surat')
                            ->label('Surat Sehat')
                            ->view('filament.infolists.components.file-preview'),
                        Infolists\Components\ViewEntry::make('lampiran.fc_surat')
                            ->label('Surat Tugas')
                            ->view('filament.infolists.components.file-preview'),
                        Infolists\Components\TextEntry::make('lampiran.no_surat')
                            ->label('Nomor Surat Tugas'),
                    ]),
            ]);
    }

    /** ==================== TABLE ==================== */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama')->searchable(),
                Tables\Columns\TextColumn::make('bidang.nama_bidang')->sortable(),
                Tables\Columns\TextColumn::make('bidang.nama_bidang')->sortable(),
                Tables\Columns\TextColumn::make('instansi.asal_instansi')->sortable(),
                Tables\Columns\TextColumn::make('email'),
                Tables\Columns\TextColumn::make('pelatihan.nama_pelatihan')->sortable(),

                // tambahan kamar & bed
                Tables\Columns\TextColumn::make('kamar_virtual')
                    ->label('Kamar')
                    ->getStateUsing(fn ($record) => self::assignKamar($record)),
                Tables\Columns\TextColumn::make('bed_virtual')
                    ->label('Bed')
                    ->getStateUsing(fn ($record) => self::assignBed($record)),
            ])
            ->filters([
                SelectFilter::make('bidang')
                    ->label('Bidang')
                    ->relationship('bidang', 'nama_bidang')
                    ->searchable()
                    ->preload(),

                SelectFilter::make('instansi')
                    ->label('Asal Instansi')
                    ->relationship('instansi', 'asal_instansi')
                    ->searchable()
                    ->preload(),

                SelectFilter::make('pelatihan')
                    ->label('Nama Pelatihan')
                    ->relationship('pelatihan', 'nama_pelatihan')
                    ->searchable()
                    ->preload(),
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
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                FilamentExportBulkAction::make('export'),
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    /** ==================== FUNGSI KAMAR ==================== */
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
                        'bed'  => (int) $r['bed'],
                    ];
                });
            })
            ->flatten(1)
            ->filter(fn ($k) => $k['bed'] > 0)
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

    protected static function assignBed($record)
    {
        $kamars = session('kamars') ?? config('kamar');
        $pesertas = Peserta::where('jenis_kelamin', $record->jenis_kelamin)
            ->orderBy('id')
            ->get();

        // Cari kamar peserta
        $kamar = self::assignKamar($record);
        if ($kamar === 'Penuh') {
            return '-';
        }

        [$blok, $noText] = explode(' - No.', $kamar);
        $no = (int) $noText;

        // Ambil kapasitas kamar
        $capacity = collect($kamars[$blok] ?? [])
            ->firstWhere('no', $no)['bed'] ?? 0;

        // Peserta yang berada di kamar itu saja
        $pesertaInRoom = $pesertas->filter(function ($p) use ($blok, $no) {
            return self::assignKamar($p) === $blok . ' - No.' . $no;
        })->values();

        // Cari index peserta di kamar tersebut
        $indexInRoom = $pesertaInRoom->search(fn ($p) => $p->id === $record->id);

        if ($indexInRoom === false || $indexInRoom >= $capacity) {
            return '-';
        }

        return 'Bed ' . ($indexInRoom + 1);
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
