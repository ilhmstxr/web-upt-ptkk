<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PesertaResource\Pages;
use App\Models\Peserta;
use App\Models\Instansi;
use App\Models\Pelatihan;
use App\Models\Bidang;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Filament\Tables\Filters\SelectFilter; // <-- Import SelectFilter
use Illuminate\Database\Eloquent\Collection;

use AlperenErsoy\FilamentExport\Actions\FilamentExportHeaderAction;
use AlperenErsoy\FilamentExport\Actions\FilamentExportBulkAction;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\BulkAction;

class PesertaResource extends Resource
{
    protected static ?string $model = Peserta::class;
    protected static ?string $navigationLabel   = 'Peserta';
    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationGroup = 'Pendaftaran';

    /** ==================== FORM ==================== */
    public static function form(Form $form): Form
    {
        // ... Form Anda tidak berubah
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
                        Forms\Components\TextInput::make('nama')->required()->live(onBlur: true),
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
                        Forms\Components\TextInput::make('instansi.asal_instansi')->required(),
                        Forms\Components\TextInput::make('bidang_keahlian')->required(),
                        Forms\Components\TextInput::make('kelas')->required(),
                        Forms\Components\TextInput::make('cabang_dinas_wilayah')->required(),
                        Forms\Components\TextInput::make('alamat_instansi')->required()->columnSpanFull(),
                    ]),

                Forms\Components\Section::make('Lampiran Berkas')
                    ->relationship('lampiran')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('no_surat_tugas')->nullable(),
                        Forms\Components\FileUpload::make('fc_ktp')->disk('public')->directory('berkas_pendaftaran/foto')->required(),
                        Forms\Components\FileUpload::make('pas_foto')->disk('public')->directory('berkas_pendaftaran/ktp')->required(),
                        Forms\Components\FileUpload::make('fc_ijazah')->disk('public')->directory('berkas_pendaftaran/ijazah')->required(),
                        Forms\Components\FileUpload::make('fc_surat_tugas')->disk('public')->directory('berkas_pendaftaran/surat-tugas')->nullable(),
                        Forms\Components\FileUpload::make('fc_surat_sehat')->disk('public')->directory('berkas_pendaftaran/surat-sehat')->required(),
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
                Tables\Columns\TextColumn::make('instansi.asal_instansi')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('jenis_kelamin')->sortable(),
                Tables\Columns\TextColumn::make('created_at')->sortable(),
                Tables\Columns\TextColumn::make('email'),
                // tambahan kamar & bed
                Tables\Columns\TextColumn::make('kamar_virtual')
                    ->label('Kamar')
                    ->getStateUsing(fn($record) => self::assignKamar($record)),
                Tables\Columns\TextColumn::make('bed_virtual')
                    ->label('Bed')
                    ->getStateUsing(fn($record) => self::assignBed($record)),
                Tables\Columns\TextColumn::make('pelatihan.nama_pelatihan')->sortable(),

            ])
            ->filters([
                SelectFilter::make('bidang')->label('Bidang')->relationship('bidang', 'nama_bidang')->searchable()->preload(),
                SelectFilter::make('instansi')->label('Asal Instansi')->relationship('instansi', 'asal_instansi')->searchable()->preload(),
                SelectFilter::make('pelatihan')->label('Nama Pelatihan')->relationship('pelatihan', 'nama_pelatihan')->searchable()->preload(),
            ])
            ->headerActions([
                Action::make('atur_kamar')
                    ->label('Atur Jumlah Kamar & Bed')
                    ->form(function () {
                        $kamar = session('kamar') ?? config('kamar');
                        return [
                            Forms\Components\Repeater::make('kamar')
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
                                    collect($kamar)->flatMap(function ($rooms, $blok) {
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
                            'kamar' => collect($data['kamar'])
                                ->groupBy('blok')
                                ->map(fn($rooms) => $rooms->map(fn($r) => [
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
                Action::make('download_pdf')
                    ->label('Cetak PDF')
                    ->icon('heroicon-o-printer')
                    ->url(fn(Peserta $record): string => route('peserta.download-pdf', $record))
                    ->openUrlInNewTab(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),

                // --- AKSI GABUNGAN UNTUK EXCEL + ZIP ---
                BulkAction::make('export_paket_lengkap')
                    ->label('Export Paket Lengkap (Excel + Lampiran)')
                    ->icon('heroicon-o-gift')
                    ->action(function (Collection $records) {
                        // Ambil semua ID yang dipilih
                        $ids = $records->pluck('id')->toArray();
                        // Buat nama file Excel dinamis
                        $excelFileName = 'data-peserta-terpilih-' . now()->format('Y-m-d');
                        // Buat URL dengan query string dari array ID dan nama file Excel
                        $url = route('peserta.download-bulk', [
                            'ids' => $ids,
                            'excelFileName' => $excelFileName
                        ]);
                        // Redirect ke URL download yang akan ditangani oleh Controller
                        return redirect($url);
                    }),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        // ... Infolist Anda tidak berubah
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
                        Infolists\Components\TextEntry::make('tanggal_lahir')->date(),
                        Infolists\Components\TextEntry::make('jenis_kelamin'),
                        Infolists\Components\TextEntry::make('agama'),
                        Infolists\Components\TextEntry::make('no_hp'),
                        Infolists\Components\TextEntry::make('email'),
                        Infolists\Components\TextEntry::make('alamat')->columnSpanFull(),
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
                        Infolists\Components\ViewEntry::make('lampiran.fc_ijazah')
                            ->label('Ijazah')
                            ->view('filament.infolists.components.file-preview'),
                        Infolists\Components\ViewEntry::make('lampiran.fc_surat_sehat')
                            ->label('Surat Sehat')
                            ->view('filament.infolists.components.file-preview'),
                        Infolists\Components\ViewEntry::make('lampiran.fc_surat_tugas')
                            ->label('Surat Tugas')
                            ->view('filament.infolists.components.file-preview'),
                        Infolists\Components\TextEntry::make('lampiran.no_surat_tugas')
                            ->label('Nomor Surat Tugas'),
                    ]),
            ]);
    }

    /** ==================== FUNGSI KAMAR ==================== */
    protected static function assignKamar($record)
    {
        $kamar = session('kamar') ?? config('kamar');
        $gender = $record->jenis_kelamin;

        $blokDipakai = $gender === 'Laki-laki'
            ? ['Melati Bawah', 'Tulip Bawah']
            : ['Mawar', 'Melati Atas', 'Tulip Atas'];

        $listKamar = collect($kamar)
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
            ->filter(fn($k) => $k['bed'] > 0)
            ->values();

        $peserta = Peserta::where('jenis_kelamin', $gender)->orderBy('id')->get();
        $index = $peserta->search(fn($p) => $p->id === $record->id);

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
        $kamar = session('kamar') ?? config('kamar');
        $peserta = Peserta::where('jenis_kelamin', $record->jenis_kelamin)
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
        $capacity = collect($kamar[$blok] ?? [])
            ->firstWhere('no', $no)['bed'] ?? 0;

        // Peserta yang berada di kamar itu saja
        $pesertaInRoom = $peserta->filter(function ($p) use ($blok, $no) {
            return self::assignKamar($p) === $blok . ' - No.' . $no;
        })->values();

        $indexInRoom = $pesertaInRoom->search(fn($p) => $p->id === $record->id);

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
