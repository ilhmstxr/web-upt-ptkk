<?php

namespace App\Filament\Resources\PesertaResource\Pages;

use App\Filament\Resources\PesertaResource;
use Filament\Actions;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ViewField; // Import ViewField
use Filament\Forms\Form;
use Filament\Resources\Pages\ViewRecord;

class ViewPeserta extends ViewRecord
{
    protected static string $resource = PesertaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
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
                                TextInput::make('nama')->label('Nama Lengkap'),
                                TextInput::make('nik')->label('NIK'),
                                TextInput::make('tempat_lahir')->label('Tempat Lahir'),
                                DatePicker::make('tanggal_lahir')->label('Tanggal Lahir'),
                                Select::make('jenis_kelamin')->label('Jenis Kelamin')->options([
                                    'Laki-laki' => 'Laki-laki',
                                    'Perempuan' => 'Perempuan',
                                ]),
                                Select::make('agama')->label('Agama')->options([
                                    'Islam' => 'Islam', 'Kristen' => 'Kristen', 'Katolik' => 'Katolik',
                                    'Hindu' => 'Hindu', 'Buddha' => 'Buddha', 'Konghucu' => 'Konghucu',
                                ]),
                                Textarea::make('alamat')->label('Alamat Tempat Tinggal'),
                                TextInput::make('no_hp')->label('Nomor Handphone'),
                                TextInput::make('email')->label('Email'),
                            ])->columns(2),

                        Section::make('Biodata Sekolah')
                            ->description('Data lembaga asal dari pendaftar.')
                            ->schema([
                                TextInput::make('instansi.asal_instansi')->label('Asal Lembaga / Sekolah'),
                                Textarea::make('instansi.alamat_instansi')->label('Alamat Sekolah'),
                                Select::make('instansi.bidang_keahlian')->label('Kompetensi / Bidang Keahlian')->options([
                                    'Tata Boga' => 'Tata Boga', 'Tata Busana' => 'Tata Busana',
                                    'Tata Kecantikan' => 'Tata Kecantikan',
                                    'Teknik Pendingin dan Tata Udara' => 'Teknik Pendingin dan Tata Udara',
                                ]),
                                TextInput::make('instansi.kelas')->label('Kelas'),
                                Select::make('instansi.cabang_dinas_wilayah')->label('Cabang Dinas Wilayah')->options([
                                    'Wilayah I' => 'Wilayah I', 'Wilayah II' => 'Wilayah II',
                                    'Wilayah III' => 'Wilayah III', 'Wilayah IV' => 'Wilayah IV',
                                    'Wilayah V' => 'Wilayah V',
                                ]),
                            ])->columns(2),

                        Section::make('Lampiran Dokumen')
                            ->description('Dokumen-dokumen pendukung yang diunggah oleh pendaftar.')
                            ->schema([
                                TextInput::make('lampiran.no_surat_tugas')->label('Nomor Surat Tugas'),
                                FileUpload::make('lampiran.fc_ktp')->label('Fotocopy KTP')->disk('public')->image()->downloadable(),
                                FileUpload::make('lampiran.fc_ijazah')->label('Fotocopy Ijazah Terakhir')->disk('public')->downloadable(),
                                FileUpload::make('lampiran.fc_surat_tugas')->label('Fotocopy Surat Tugas')->disk('public')->downloadable(),
                                FileUpload::make('lampiran.fc_surat_sehat')->label('Surat Keterangan Sehat')->disk('public')->downloadable(),
                                FileUpload::make('lampiran.pas_foto')->label('Pas Foto Formal Background Merah')->disk('public')->image()->downloadable(),
                            ])->columns(2),
                    ])->disabled(),
            ]);
    }
}