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
use Filament\Tables\Filters\SelectFilter; // <-- Import SelectFilter
use Filament\Tables\Table;


use Filament\Infolists;
use Filament\Infolists\Infolist;

// 🔑 Import plugin export
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
                Forms\Components\Section::make('Informasi Pendaftaran')
                    ->columns(2)
                    ->schema([
                        Forms\Components\Select::make('pelatihan_id')
                            ->relationship('pelatihan', 'nama_pelatihan')
                            ->required(),
                        Forms\Components\Select::make('instansi_id')
                            ->relationship('instansi', 'asal_instansi')
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
                            ])->required(),
                        Forms\Components\TextInput::make('agama')->required(),
                        Forms\Components\TextInput::make('no_hp')->required()->tel(),
                        Forms\Components\TextInput::make('email')->required()->email()->unique(ignoreRecord: true),
                        Forms\Components\Textarea::make('alamat')->required()->columnSpanFull(),
                    ]),

                Forms\Components\Section::make('Lampiran Berkas')
                    ->columns(2)
                    ->schema([
                        Forms\Components\FileUpload::make('lampiran.fc_ktp')
                            ->disk('public')
                            ->directory(fn ($record) => 'lampiran/' . \Str::slug($record->nama))
                            ->label('KTP')
                            ->required(),

                        Forms\Components\FileUpload::make('lampiran.fc_ijazah')
                            ->disk('public')
                            ->directory(fn ($record) => 'lampiran/' . \Str::slug($record->nama))
                            ->label('Ijazah')
                            ->required(),

                        Forms\Components\FileUpload::make('lampiran.fc_surat_sehat')
                            ->disk('public')
                            ->directory(fn ($record) => 'lampiran/' . \Str::slug($record->nama))
                            ->label('Surat Sehat')
                            ->required(),

                        Forms\Components\FileUpload::make('lampiran.pas_foto')
                            ->disk('public')
                            ->directory(fn ($record) => 'lampiran/' . \Str::slug($record->nama))
                            ->label('Pas Foto')
                            ->required(),

                        Forms\Components\FileUpload::make('lampiran.fc_surat_tugas')
                            ->disk('public')
                            ->directory(fn ($record) => 'lampiran/' . \Str::slug($record->nama))
                            ->label('Surat Tugas')
                            ->nullable(),

                        Forms\Components\TextInput::make('lampiran.no_surat_tugas')
                            ->label('Nomor Surat Tugas')
                            ->nullable(),
                    ]),
            ]);
    }

    // Letakkan method ini di dalam class PesertaResource
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
                        Forms\Components\TextInput::make('asal_instansi')->required(),
                        Forms\Components\TextInput::make('bidang_keahlian')->required(),
                        Forms\Components\TextInput::make('kelas')->required(),
                        Forms\Components\TextInput::make('cabang_dinas_wilayah')->required(),
                        Forms\Components\Textarea::make('alamat_instansi')->required()->columnSpanFull(),
                    ]),
                
                Forms\Components\Section::make('Lampiran Berkas')
                    ->relationship('lampiran') // Mengambil data dari relasi 'lampiran'
                    ->columns(2)
                    ->schema([
                        // Gunakan ViewEntry untuk memanggil blade custom kita
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
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama')->searchable(),
                Tables\Columns\TextColumn::make('bidang.nama_bidang')->sortable(),
                Tables\Columns\TextColumn::make('instansi.asal_instansi')->sortable(),
                Tables\Columns\TextColumn::make('email'),
                Tables\Columns\TextColumn::make('pelatihan.nama_pelatihan')->sortable(),
            ])
  ->filters([
                // Filter berdasarkan Bidang
                SelectFilter::make('bidang')
                    ->label('Bidang')
                    ->relationship('bidang', 'nama_bidang')
                    ->searchable() // Membuat dropdown bisa dicari
                    ->preload(), // Memuat opsi saat halaman dimuat

                // Filter berdasarkan Instansi
                SelectFilter::make('instansi')
                    ->label('Asal Instansi')
                    ->relationship('instansi', 'asal_instansi')
                    ->searchable()
                    ->preload(),

                // Filter berdasarkan Pelatihan
                SelectFilter::make('pelatihan')
                    ->label('Nama Pelatihan')
                    ->relationship('pelatihan', 'nama_pelatihan')
                    ->searchable()
                    ->preload(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                FilamentExportHeaderAction::make('export'), // tombol export di header
            ])
            ->filters([
                //
            ])
            ->headerActions([
                FilamentExportHeaderAction::make('export'), // tombol export di header
            ])
            ->actions([
Tables\Actions\ViewAction::make(), 
           Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                FilamentExportBulkAction::make('export'), // tombol export di bulk action
                Tables\Actions\DeleteBulkAction::make(),
            ]);
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
