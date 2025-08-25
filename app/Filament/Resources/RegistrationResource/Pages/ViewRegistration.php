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
use Filament\Forms\Form; // Impor class Form
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

    public function form(Form $form): Form // Perbaikan di sini
    {
        return $form
            ->schema([
                Group::make()
                    ->schema([
                        Section::make('Biodata Diri')
                            ->description('Data diri lengkap dari pendaftar.')
                            ->schema([
                                TextInput::make('name')->label('Nama Lengkap'),
                                TextInput::make('nik')->label('NIK'),
                                TextInput::make('birth_place')->label('Tempat Lahir'),
                                DatePicker::make('birth_date')->label('Tanggal Lahir'),
                                Select::make('gender')->label('Jenis Kelamin')->options([
                                    'Laki-laki' => 'Laki-laki',
                                    'Perempuan' => 'Perempuan',
                                ]),
                                Select::make('religion')->label('Agama')->options([
                                    'Islam' => 'Islam', 'Kristen' => 'Kristen', 'Katolik' => 'Katolik',
                                    'Hindu' => 'Hindu', 'Buddha' => 'Buddha', 'Konghucu' => 'Konghucu',
                                ]),
                                Textarea::make('address')->label('Alamat Tempat Tinggal'),
                                TextInput::make('phone')->label('Nomor Handphone'),
                                TextInput::make('email')->label('Email'),
                            ])->columns(2),

                        Section::make('Biodata Sekolah')
                            ->description('Data lembaga asal dari pendaftar.')
                            ->schema([
                                TextInput::make('school_name')->label('Asal Lembaga / Sekolah'),
                                Textarea::make('school_address')->label('Alamat Sekolah'),
                                Select::make('competence')->label('Kompetensi / Bidang Keahlian')->options([
                                    'Tata Boga' => 'Tata Boga', 'Tata Busana' => 'Tata Busana',
                                    'Tata Kecantikan' => 'Tata Kecantikan',
                                    'Teknik Pendingin dan Tata Udara' => 'Teknik Pendingin dan Tata Udara',
                                ]),
                                TextInput::make('class')->label('Kelas'),
                                Select::make('dinas_branch')->label('Cabang Dinas Wilayah')->options([
                                    'Wilayah I' => 'Wilayah I', 'Wilayah II' => 'Wilayah II',
                                    'Wilayah III' => 'Wilayah III', 'Wilayah IV' => 'Wilayah IV',
                                    'Wilayah V' => 'Wilayah V',
                                ]),
                            ])->columns(2),

                        Section::make('Lampiran Dokumen')
                            ->description('Dokumen-dokumen pendukung yang diunggah oleh pendaftar.')
                            ->schema([
                                TextInput::make('surat_tugas_nomor')->label('Nomor Surat Tugas'),
                                FileUpload::make('ktp_path')->label('Fotocopy KTP')->disk('public')->disabled(),
                                FileUpload::make('ijazah_path')->label('Fotocopy Ijazah Terakhir')->disk('public')->disabled(),
                                FileUpload::make('surat_tugas_path')->label('Fotocopy Surat Tugas')->disk('public')->disabled(),
                                FileUpload::make('surat_sehat_path')->label('Surat Keterangan Sehat')->disk('public')->disabled(),
                                FileUpload::make('pas_foto_path')->label('Pas Foto Formal Background Merah')->disk('public')->disabled(),
                            ])->columns(2),
                    ])->disabled(), // Tambahkan disabled di sini untuk seluruh grup
            ]);
    }
}