<?php

namespace App\Filament\Resources\RegistrationResource\Pages;

use App\Filament\Resources\RegistrationResource;
use Filament\Actions;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Form;
use Filament\Resources\Pages\ViewRecord;

class ViewRegistration extends ViewRecord
{
    protected static string $resource = RegistrationResource::class;

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
                                TextInput::make('peserta.nama')->label('Nama Lengkap'),
                                TextInput::make('peserta.nik')->label('NIK'),
                                TextInput::make('peserta.tempat_lahir')->label('Tempat Lahir'),
                                DatePicker::make('peserta.tanggal_lahir')->label('Tanggal Lahir'),
                                Select::make('peserta.jenis_kelamin')->label('Jenis Kelamin')->options([
                                    'Laki-laki' => 'Laki-laki',
                                    'Perempuan' => 'Perempuan',
                                ]),
                                Select::make('peserta.agama')->label('Agama')->options([
                                    'Islam' => 'Islam', 'Kristen' => 'Kristen', 'Katolik' => 'Katolik',
                                    'Hindu' => 'Hindu', 'Buddha' => 'Buddha', 'Konghucu' => 'Konghucu',
                                ]),
                                Textarea::make('peserta.alamat')->label('Alamat Tempat Tinggal'),
                                TextInput::make('peserta.no_hp')->label('Nomor Handphone'),
                                TextInput::make('peserta.email')->label('Email'),
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
                                FileUpload::make('lampiran.fc_ktp')->label('Fotocopy KTP')->disk('public')->disabled(),
                                FileUpload::make('lampiran.fc_ijazah')->label('Fotocopy Ijazah Terakhir')->disk('public')->disabled(),
                                FileUpload::make('lampiran.fc_surat_tugas')->label('Fotocopy Surat Tugas')->disk('public')->disabled(),
                                FileUpload::make('lampiran.fc_surat_sehat')->label('Surat Keterangan Sehat')->disk('public')->disabled(),
                                FileUpload::make('lampiran.pas_foto')->label('Pas Foto Formal Background Merah')->disk('public')->disabled(),
                            ])->columns(2),
                    ])->disabled(),
            ]);
    }
}
