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
use Filament\Forms\Components\View;
use Filament\Forms\Get;
use Illuminate\Support\Arr;
use Illuminate\Database\Eloquent\Model;
// use App\Traits\ManagesRegistrationTokens;

class EditPeserta extends EditRecord
{
    // use ManagesRegistrationTokens;

    protected static string $resource = PesertaResource::class;

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $lampiran = $this->record->lampiran;
        if ($lampiran) {
            $data['no_surat_tugas'] = $lampiran->no_surat_tugas;
        }
        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $kelasBaru = data_get($this->form->getState(), 'instansi_kelas');
        if (!empty($data['instansi_id']) && $kelasBaru !== null) {
            $base = Instansi::find($data['instansi_id']);
            if ($base) {
                $instansi = Instansi::updateOrCreate(
                    [
                        'asal_instansi'   => $base->asal_instansi,
                        'alamat_instansi' => $base->alamat_instansi,
                        'kota'            => $base->kota,
                        'kota_id'         => $base->kota_id,
                        'kelas'           => $kelasBaru,
                    ],
                    [
                        'bidang_keahlian' => $base->bidang_keahlian,
                        'cabangDinas_id' => $base->cabangDinas_id,
                    ]
                );
                $data['instansi_id'] = $instansi->id;
            }
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
                                Select::make('user_id')
                                    ->relationship('user', 'email')
                                    ->label('Email')
                                    ->searchable()
                                    ->required(),
                            ])->columns(2),

                        Section::make('Biodata Sekolah')
                            ->description('Pilih instansi yang sudah ada atau buat baru.')
                            ->schema([
                                Select::make('instansi_id')
                                    ->relationship('instansi', 'asal_instansi')
                                    ->label('Asal Lembaga / Sekolah')
                                    ->getOptionLabelFromRecordUsing(
                                        fn(Instansi $i) => trim("{$i->asal_instansi}" . ($i->kelas ? " — Kelas {$i->kelas}" : ''))
                                    )
                                    ->searchable()->preload()->required()->live()
                                    ->afterStateUpdated(function (Set $set, ?string $state) {
                                        if ($state && ($instansi = Instansi::find($state))) {
                                            $set('cabangDinas_id', $instansi->cabangDinas_id);
                                            $set('instansi_kelas', $instansi->kelas);
                                        } else {
                                            $set('instansi_kelas', null);
                                        }
                                    })
                                    ->afterStateHydrated(function (Get $get, Set $set, ?Model $record) {
                                        if ($record?->instansi) {
                                            $set('instansi_kelas', $record->instansi->kelas);
                                        }
                                    }),
                                Select::make('pelatihan_id')
                                    ->relationship('pelatihan', 'nama_pelatihan')
                                    ->label('Pelatihan')->searchable()->required(),
                                Select::make('bidang_id')
                                    ->relationship('bidang', 'nama_bidang')
                                    ->label('Bidang Keahlian')->searchable()->required(),
                                TextInput::make('instansi_kelas')
                                    ->label('Kelas (Instansi)')->required()->dehydrated(false)->reactive()
                                    ->afterStateUpdated(function (Get $get, Set $set, ?string $state) {
                                        if ($state === null || $state === '') return;
                                        $currentInstansiId = $get('instansi_id');
                                        if (!$currentInstansiId) return;
                                        $base = Instansi::find($currentInstansiId);
                                        if (!$base) return;
                                        $target = Instansi::updateOrCreate(
                                            [
                                                'asal_instansi'   => $base->asal_instansi,
                                                'alamat_instansi' => $base->alamat_instansi,
                                                'kota'            => $base->kota,
                                                'kota_id'         => $base->kota_id,
                                                'kelas'           => $state,
                                            ],
                                            [
                                                'bidang_keahlian' => $base->bidang_keahlian,
                                                'cabangDinas_id' => $base->cabangDinas_id,
                                            ]
                                        );
                                        $set('instansi_id', $target->id);
                                    })
                                    ->helperText('Mengubah ini akan otomatis membuat/memilih instansi sesuai kelas yang baru.'),
                            ])->columns(2),

                        Section::make('Lampiran Dokumen')
                            ->description('Dokumen-dokumen pendukung yang diunggah oleh pendaftar.')
                            ->schema([
                                TextInput::make('no_surat_tugas')->label('Nomor Surat Tugas'),
                                View::make('ktp_preview')->view('components.file-preview-with-download')->viewData(['filePath' => $this->record->lampiran?->fc_ktp]),
                                FileUpload::make('fc_ktp')->label('Unggah Fotocopy KTP Baru (Opsional)')->disk('public')->dehydrated(fn($state) => filled($state)),
                                View::make('ijazah_preview')->view('components.file-preview-with-download')->viewData(['filePath' => $this->record->lampiran?->fc_ijazah]),
                                FileUpload::make('fc_ijazah')->label('Unggah Fotocopy Ijazah Baru (Opsional)')->disk('public')->dehydrated(fn($state) => filled($state)),
                                View::make('surat_tugas_preview')->view('components.file-preview-with-download')->viewData(['filePath' => $this->record->lampiran?->fc_surat_tugas]),
                                FileUpload::make('fc_surat_tugas')->label('Unggah Fotocopy Surat Tugas Baru (Opsional)')->disk('public')->dehydrated(fn($state) => filled($state)),
                                View::make('surat_sehat_preview')->view('components.file-preview-with-download')->viewData(['filePath' => $this->record->lampiran?->fc_surat_sehat]),
                                FileUpload::make('fc_surat_sehat')->label('Unggah Surat Keterangan Sehat Baru (Opsional)')->disk('public')->dehydrated(fn($state) => filled($state)),
                                View::make('pas_foto_preview')->view('components.file-preview-with-download')->viewData(['filePath' => $this->record->lampiran?->pas_foto]),
                                FileUpload::make('pas_foto')->label('Unggah Pas Foto Baru (Opsional)')->disk('public')->dehydrated(fn($state) => filled($state)),
                            ])->columns(1),
                    ]),
            ]);
    }

    /**
     * Method ini di-override untuk menambahkan logika update nomor registrasi.
     */
    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        DB::beginTransaction();
        try {
            // Ambil ID bidang sebelum ada perubahan, untuk perbandingan
            $originalBidangId = $record->bidang_id;
            $newBidangId = $data['bidang_id'];

            // Definisikan key-key yang ada di tabel lampiran
            $lampiranKeys = ['no_surat_tugas', 'fc_ktp', 'fc_ijazah', 'fc_surat_tugas', 'fc_surat_sehat', 'pas_foto'];

            // Pisahkan dan update data untuk tabel 'peserta'
            $pesertaData = Arr::except($data, $lampiranKeys);
            $record->update($pesertaData);

            // Cek jika bidang keahlian diubah. Jika ya, generate ulang nomor registrasi.
            if ($originalBidangId != $newBidangId && $record->pendaftaranPelatihan) {
                // Panggil fungsi dari Trait untuk membuat nomor & urutan baru
                ['nomor' => $newNomorReg, 'urutan' => $newUrut] = $this->generateToken(
                    $record->pelatihan_id,
                    $newBidangId
                );

                // Update record di tabel 'pendaftaran_pelatihan'
                $record->pendaftaranPelatihan->update([
                    'nomor_registrasi' => $newNomorReg,
                    // 'urutan_per_bidang' => $newUrut, // (Opsional) Uncomment jika ada kolom ini
                ]);
            }

            // Pisahkan dan update/create data untuk tabel 'lampiran'
            $lampiranData = Arr::only($data, $lampiranKeys);
            if (!empty(array_filter($lampiranData))) {
                $record->lampiran()->updateOrCreate(['peserta_id' => $record->id], $lampiranData);
            }

            DB::commit();
            return $record;
            
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}