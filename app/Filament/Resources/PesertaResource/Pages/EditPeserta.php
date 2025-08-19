<?php

namespace App\Filament\Resources\PesertaResource\Pages;

use App\Filament\Resources\PesertaResource;
use Filament\Actions;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Form;
use Filament\Resources\Pages\EditRecord;

class EditPeserta extends EditRecord
{
    protected static string $resource = PesertaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make()
                    ->schema([
                        Section::make('Biodata Diri')
                            ->description('Data diri lengkap dari pendaftar.')
                            ->schema([
                                TextInput::make('nama')->label('Nama Lengkap')->required(),
                                TextInput::make('nik')->label('NIK')->required(),
                                TextInput::make('tempat_lahir')->label('Tempat Lahir')->required(),
                                DatePicker::make('tanggal_lahir')->label('Tanggal Lahir')->required(),
                                Select::make('jenis_kelamin')->label('Jenis Kelamin')->options([
                                    'Laki-laki' => 'Laki-laki',
                                    'Perempuan' => 'Perempuan',
                                ])->required(),
                                Select::make('agama')->label('Agama')->options([
                                    'Islam' => 'Islam', 'Kristen' => 'Kristen', 'Katolik' => 'Katolik',
                                    'Hindu' => 'Hindu', 'Buddha' => 'Buddha', 'Konghucu' => 'Konghucu',
                                ])->required(),
                                Textarea::make('alamat')->label('Alamat Tempat Tinggal')->required(),
                                TextInput::make('no_hp')->label('Nomor Handphone')->required(),
                                TextInput::make('email')->label('Email')->email()->required(),
                            ])->columns(2),

                        // Menggunakan Select untuk memilih Instansi yang sudah ada
                        Section::make('Biodata Sekolah')
                            ->description('Pilih instansi yang sudah ada atau buat baru.')
                            ->schema([
                                Select::make('instansi_id')
                                    ->relationship('instansi', 'asal_instansi')
                                    ->label('Asal Lembaga / Sekolah')
                                    ->searchable()
                                    ->required(),
                            ]),
                            
                        // Menggunakan dot notation untuk mengedit relasi 'lampiran'
                        Section::make('Lampiran Dokumen')
                            ->description('Dokumen-dokumen pendukung yang diunggah oleh pendaftar.')
                            ->schema([
                                TextInput::make('lampiran.no_surat_tugas')->label('Nomor Surat Tugas')->required(),
                                FileUpload::make('lampiran.fc_ktp')->label('Fotocopy KTP')->disk('public')->required(),
                                FileUpload::make('lampiran.fc_ijazah')->label('Fotocopy Ijazah Terakhir')->disk('public')->required(),
                                FileUpload::make('lampiran.fc_surat_tugas')->label('Fotocopy Surat Tugas')->disk('public')->required(),
                                FileUpload::make('lampiran.fc_surat_sehat')->label('Surat Keterangan Sehat')->disk('public')->required(),
                                FileUpload::make('lampiran.pas_foto')->label('Pas Foto Formal Background Merah')->disk('public')->required(),
                            ])->columns(2),
                    ]),
            ]);
    }
}
