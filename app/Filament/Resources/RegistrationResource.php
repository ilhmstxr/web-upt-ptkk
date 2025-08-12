<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RegistrationResource\Pages;
use App\Models\Registration;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;
use Filament\Tables\Actions\Action;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\TuExport;
use App\Exports\UptExport;

class RegistrationResource extends Resource
{
    protected static ?string $model = Registrbation::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make()
                    ->schema([
                        Section::make('Biodata Diri')
                            ->description('Data diri lengkap dari pendaftar.')
                            ->schema([
                                Forms\Components\TextInput::make('name')->label('Nama Lengkap')->required()->maxLength(255),
                                Forms\Components\TextInput::make('nik')->label('NIK')->required()->numeric()->maxLength(16),
                                Forms\Components\TextInput::make('birth_place')->label('Tempat Lahir')->required()->maxLength(255),
                                Forms\Components\DatePicker::make('birth_date')->label('Tanggal Lahir')->required(),
                                Forms\Components\Select::make('gender')->label('Jenis Kelamin')->options([
                                    'Laki-laki' => 'Laki-laki',
                                    'Perempuan' => 'Perempuan',
                                ])->required(),
                                Forms\Components\Select::make('religion')->label('Agama')->options([
                                    'Islam' => 'Islam',
                                    'Kristen' => 'Kristen',
                                    'Katolik' => 'Katolik',
                                    'Hindu' => 'Hindu',
                                    'Buddha' => 'Buddha',
                                    'Konghucu' => 'Konghucu',
                                ])->required(),
                                Forms\Components\TextInput::make('address')->label('Alamat Tempat Tinggal')->required()->maxLength(255),
                                Forms\Components\TextInput::make('phone')->label('Nomor Handphone')->required()->tel()->maxLength(15),
                                Forms\Components\TextInput::make('email')->label('Email')->email()->required()->maxLength(255),
                            ])->columns(2),

                        Section::make('Biodata Sekolah')
                            ->description('Data lembaga asal dari pendaftar.')
                            ->schema([
                                Forms\Components\TextInput::make('school_name')->label('Asal Lembaga / Sekolah')->required()->maxLength(255),
                                Forms\Components\TextInput::make('school_address')->label('Alamat Sekolah')->required()->maxLength(255),
                                Forms\Components\Select::make('competence')->label('Kompetensi / Bidang Keahlian')->options([
                                    'Tata Boga' => 'Tata Boga',
                                    'Tata Busana' => 'Tata Busana',
                                    'Tata Kecantikan' => 'Tata Kecantikan',
                                    'Teknik Pendingin dan Tata Udara' => 'Teknik Pendingin dan Tata Udara',
                                ])->required(),
                                Forms\Components\TextInput::make('class')->label('Kelas')->required()->maxLength(255),
                                Forms\Components\Select::make('dinas_branch')->label('Cabang Dinas Wilayah')->options([
                                    'Wilayah I' => 'Wilayah I',
                                    'Wilayah II' => 'Wilayah II',
                                    'Wilayah III' => 'Wilayah III',
                                    'Wilayah IV' => 'Wilayah IV',
                                    'Wilayah V' => 'Wilayah V',
                                ])->required(),
                            ])->columns(2),
                    ]),

                // Section::make('Lampiran Dokumen')
                //     ->description('Dokumen-dokumen pendukung yang diunggah oleh pendaftar.')
                //     ->schema([
                //         Forms\Components\FileUpload::make('ktp_path')->label('Fotocopy KTP')->disk('public')->required(),
                //         Forms\Components\FileUpload::make('ijazah_path')->label('Fotocopy Ijazah Terakhir')->disk('public')->required(),
                //         Forms\Components\FileUpload::make('surat_tugas_path')->label('Fotocopy Surat Tugas')->disk('public')->required(),
                //         Forms\Components\TextInput::make('surat_tugas_nomor')->label('Nomor Surat Tugas')->required()->maxLength(255),
                //         Forms\Components\FileUpload::make('surat_sehat_path')->label('Surat Keterangan Sehat')->disk('public')->required(),
                //         Forms\Components\FileUpload::make('pas_foto_path')->label('Pas Foto Formal Background Merah')->disk('public')->required(),
                //     ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('email')->searchable(),
                Tables\Columns\TextColumn::make('school_name')->searchable(),
                Tables\Columns\TextColumn::make('competence')->searchable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->headerActions([
                Action::make('download_tu')
                    ->label('Download TU')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->action(fn() => Excel::download(new TuExport, 'TU.xlsx')),

                Action::make('download_upt')
                    ->label('Download UPT-PELATIHAN')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->action(fn() => Excel::download(new UptExport, 'UPT-PELATIHAN.xlsx')),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRegistrations::route('/'),
            'create' => Pages\CreateRegistration::route('/create'),
            'edit' => Pages\EditRegistration::route('/{record}/edit'),
        ];
    }
}
