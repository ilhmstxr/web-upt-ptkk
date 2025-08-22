<?php

namespace App\Filament\Resources\PesertaResource\Pages;

use App\Filament\Resources\PesertaResource;
use App\Models\Lampiran;
use App\Models\Instansi;
use App\Models\CabangDinas;
use Filament\Actions;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\DB;
use Filament\Forms\Components\View; // <-- Tambahkan ini di atas


class EditPeserta extends EditRecord
{
    protected static string $resource = PesertaResource::class;

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $lampiran = $this->record->lampiran;

        if ($lampiran) {
            $data['lampiran_no_surat_tugas'] = $lampiran->no_surat_tugas;
            // Kita tidak perlu mengisi data file di sini lagi
            // karena akan ditangani oleh komponen View kustom.
        }

        return $data;
    }


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
                                    'Islam' => 'Islam',
                                    'Kristen' => 'Kristen',
                                    'Katolik' => 'Katolik',
                                    'Hindu' => 'Hindu',
                                    'Buddha' => 'Buddha',
                                    'Konghucu' => 'Konghucu',
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
                                    ->required()
                                    ->live() // Membuat form reaktif
                                    ->afterStateUpdated(function (Set $set, ?string $state) {
                                        if ($state) {
                                            $instansi = Instansi::find($state);
                                            if ($instansi) {
                                                $set('cabang_dinas_id', $instansi->cabang_dinas_id);
                                            }
                                        }
                                    }),
                                Select::make('pelatihan_id')
                                    ->relationship('pelatihan', 'nama_pelatihan')
                                    ->label('Pelatihan')
                                    ->searchable()
                                    ->required(),
                                Select::make('bidang_id')
                                    ->relationship('bidang', 'nama_bidang')
                                    ->label('Bidang Keahlian')
                                    ->searchable()
                                    ->required(),
                                Select::make('cabang_dinas_id')
                                    ->label('Cabang Dinas')
                                    ->options(CabangDinas::all()->pluck('nama', 'id'))
                                    ->searchable()
                                    ->required()
                                    ->disabled(), // dinonaktifkan karena diisi otomatis
                            ])->columns(2),

                        Section::make('Lampiran Dokumen')
                            ->description('Dokumen-dokumen pendukung yang diunggah oleh pendaftar.')
                            ->schema([
                                TextInput::make('lampiran_no_surat_tugas')
                                    ->label('Nomor Surat Tugas')
                                    ->required(),

                                // KTP
                                View::make('ktp_preview')->view('components.file-preview-with-download')->viewData(['filePath' => $this->record->lampiran?->fc_ktp]),
                                FileUpload::make('lampiran_fc_ktp')->label('Unggah Fotocopy KTP Baru (Opsional)')->disk('public')->image(),

                                // Ijazah
                                View::make('ijazah_preview')->view('components.file-preview-with-download')->viewData(['filePath' => $this->record->lampiran?->fc_ijazah]),
                                FileUpload::make('lampiran_fc_ijazah')->label('Unggah Fotocopy Ijazah Baru (Opsional)')->disk('public')->image(),

                                // Surat Tugas
                                View::make('surat_tugas_preview')->view('components.file-preview-with-download')->viewData(['filePath' => $this->record->lampiran?->fc_surat_tugas]),
                                FileUpload::make('lampiran_fc_surat_tugas')->label('Unggah Fotocopy Surat Tugas Baru (Opsional)')->disk('public'),

                                // Surat Sehat
                                View::make('surat_sehat_preview')->view('components.file-preview-with-download')->viewData(['filePath' => $this->record->lampiran?->fc_surat_sehat]),
                                FileUpload::make('lampiran_fc_surat_sehat')->label('Unggah Surat Keterangan Sehat Baru (Opsional)')->disk('public'),

                                // Pas Foto
                                View::make('pas_foto_preview')->view('components.file-preview-with-download')->viewData(['filePath' => $this->record->lampiran?->pas_foto]),
                                FileUpload::make('lampiran_pas_foto')->label('Unggah Pas Foto Baru (Opsional)')->disk('public')->image(),

                            ])->columns(1), // Menggunakan 1 kolom agar rapi

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
