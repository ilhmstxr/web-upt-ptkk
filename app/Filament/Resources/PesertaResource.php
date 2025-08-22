<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PesertaResource\Pages;
use App\Models\Peserta;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

// 🔑 Import plugin export
use AlperenErsoy\FilamentExport\Actions\FilamentExportHeaderAction;
use AlperenErsoy\FilamentExport\Actions\FilamentExportBulkAction;

use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Tables\Filters\SelectFilter;

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

                Forms\Components\Section::make('Data Instansi')
                    ->relationship('instansi')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('instansi.asal_instansi')->required(),
                        Forms\Components\TextInput::make('bidang_keahlian')->required(),
                        Forms\Components\TextInput::make('kelas')->required(),
                        Forms\Components\TextInput::make('cabang_dinas_wilayah')->required(),
                        Forms\Components\TextInput::make('alamat_instansi')->columnSpanFull(),
                    ]),

                Forms\Components\Section::make('Lampiran')
                    ->relationship('lampiran')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('no_surat_tugas')->nullable(),
                        Forms\Components\FileUpload::make('fc_ktp')->disk('public')->directory('berkas_pendaftaran/foto')->required(),
                        Forms\Components\FileUpload::make('pas_foto')->disk('public')->directory('berkas_pendaftaran/ktp')->required(),
                        Forms\Components\FileUpload::make('fc_ijazah')->disk('public')->directory('berkas_pendaftaran/ijazah')->required(),
                        Forms\Components\FileUpload::make('fc_surat_tugas')->disk('public')->directory('berkas_pendaftaran/surat-tugas')->required(),
                        Forms\Components\FileUpload::make('fc_surat_sehat')->disk('public')->directory('berkas_pendaftaran/surat-sehat')->required(),
                    ]),
            ]);
    }

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
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama')->searchable(),
                Tables\Columns\TextColumn::make('pelatihan.nama_pelatihan')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('instansi.asal_instansi')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('nik')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('email'),
            ])
            ->filters([
                SelectFilter::make('bidang')->label('Bidang')->relationship('instansi', 'nama_bidang')->seaerchable()->preload(),
                SelectFilter::make('instansi')->label('Asal Instansi')->relationship('instansi', 'asal_instansi')->seaerchable()->preload(),
                SelectFilter::make('pelatihan')->label('Nama Pelatihan')->relationship('instansi', 'nama_pelatihan')->seaerchable()->preload(),
            ])
            ->headerActions([
                // FilamentExportHeaderAction::make('export'), // tombol export di header
            ])
            ->actions([
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
