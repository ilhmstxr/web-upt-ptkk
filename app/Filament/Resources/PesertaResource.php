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

// Import plugin export
use AlperenErsoy\FilamentExport\Actions\FilamentExportHeaderAction;

// Import untuk Aksi Kustom
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\BulkAction;

class PesertaResource extends Resource
{
    protected static ?string $model = Peserta::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationGroup = 'Pendaftaran';

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
                        Forms\Components\Select::make('instansi_id')
                            ->relationship('instansi', 'asal_instansi')
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
                            ->directory(fn (Forms\Get $get) => 'lampiran/' . Str::slug($get('nama')))
                            ->label('KTP')
                            ->required(),
                        Forms\Components\FileUpload::make('lampiran.fc_ijazah')
                            ->disk('public')
                            ->directory(fn (Forms\Get $get) => 'lampiran/' . Str::slug($get('nama')))
                            ->label('Ijazah')
                            ->required(),
                        Forms\Components\FileUpload::make('lampiran.fc_surat_sehat')
                            ->disk('public')
                            ->directory(fn (Forms\Get $get) => 'lampiran/' . Str::slug($get('nama')))
                            ->label('Surat Sehat')
                            ->required(),
                        Forms\Components\FileUpload::make('lampiran.pas_foto')
                            ->disk('public')
                            ->directory(fn (Forms\Get $get) => 'lampiran/' . Str::slug($get('nama')))
                            ->label('Pas Foto')
                            ->required(),
                        Forms\Components\FileUpload::make('lampiran.fc_surat_tugas')
                            ->disk('public')
                            ->directory(fn (Forms\Get $get) => 'lampiran/' . Str::slug($get('nama')))
                            ->label('Surat Tugas')
                            ->nullable(),
                        Forms\Components\TextInput::make('lampiran.no_surat_tugas')
                            ->label('Nomor Surat Tugas')
                            ->nullable(),
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
            ->headerActions([
                // Aksi ini hanya akan mengekspor Excel saja
                FilamentExportHeaderAction::make('export_excel_only')
                    ->label('Export Excel Saja')
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Action::make('download_pdf')
                    ->label('Cetak PDF')
                    ->icon('heroicon-o-printer')
                    ->url(fn (Peserta $record): string => route('peserta.download-pdf', $record))
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPesertas::route('/'),
            'create' => Pages\CreatePeserta::route('/create'),
            'edit' => Pages\EditPeserta::route('/{record}/edit'),
        ];
    }
}
