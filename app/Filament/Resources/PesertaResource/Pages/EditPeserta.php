<?php

namespace App\Filament\Resources\PesertaResource\Pages;

use App\Filament\Resources\PesertaResource;
use App\Models\Lampiran;
use App\Models\Instansi;
use Filament\Actions;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\DB;

class EditPeserta extends EditRecord
{
    protected static string $resource = PesertaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
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

                        Section::make('Biodata Sekolah')
                            ->description('Pilih instansi yang sudah ada atau buat baru.')
                            ->schema([
                                Select::make('instansi_id')
                                    ->relationship('instansi', 'asal_instansi')
                                    ->label('Asal Lembaga / Sekolah')
                                    ->searchable()
                                    ->required(),
                            ]),
                            
                        Section::make('Lampiran Dokumen')
                            ->description('Dokumen-dokumen pendukung yang diunggah oleh pendaftar.')
                            ->schema([
                                TextInput::make('lampiran_no_surat_tugas')->label('Nomor Surat Tugas')->required()->default(fn ($record) => $record->lampiran->no_surat_tugas ?? null),
                                FileUpload::make('lampiran_fc_ktp')->label('Fotocopy KTP')->disk('public')->required()->default(fn ($record) => $record->lampiran->fc_ktp ?? null),
                                FileUpload::make('lampiran_fc_ijazah')->label('Fotocopy Ijazah Terakhir')->disk('public')->required()->default(fn ($record) => $record->lampiran->fc_ijazah ?? null),
                                FileUpload::make('lampiran_fc_surat_tugas')->label('Fotocopy Surat Tugas')->disk('public')->required()->default(fn ($record) => $record->lampiran->fc_surat_tugas ?? null),
                                FileUpload::make('lampiran_fc_surat_sehat')->label('Surat Keterangan Sehat')->disk('public')->required()->default(fn ($record) => $record->lampiran->fc_surat_sehat ?? null),
                                FileUpload::make('lampiran_pas_foto')->label('Pas Foto Formal Background Merah')->disk('public')->required()->default(fn ($record) => $record->lampiran->pas_foto ?? null),
                            ])->columns(2),
                    ]),
            ]);
    }

    protected function handleRecordUpdate(\Illuminate\Database\Eloquent\Model $record, array $data): \Illuminate\Database\Eloquent\Model
    {
        DB::beginTransaction();
        try {
            // Data untuk model Peserta
            $pesertaData = $data;
            unset($pesertaData['lampiran_no_surat_tugas'], $pesertaData['lampiran_fc_ktp'], $pesertaData['lampiran_fc_ijazah'], $pesertaData['lampiran_fc_surat_tugas'], $pesertaData['lampiran_fc_surat_sehat'], $pesertaData['lampiran_pas_foto']);
            $record->update($pesertaData);

            // Data untuk model Lampiran
            $lampiranData = [
                'no_surat_tugas' => $data['lampiran_no_surat_tugas'],
                'fc_ktp' => $data['lampiran_fc_ktp'],
                'fc_ijazah' => $data['lampiran_fc_ijazah'],
                'fc_surat_tugas' => $data['lampiran_fc_surat_tugas'],
                'fc_surat_sehat' => $data['lampiran_fc_surat_sehat'],
                'pas_foto' => $data['lampiran_pas_foto'],
            ];
            
            // Simpan data Lampiran
            if ($record->lampiran) {
                $record->lampiran->update($lampiranData);
            } else {
                $record->lampiran()->create($lampiranData);
            }
            
            DB::commit();
            
            return $record;

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
