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
use Filament\Forms\Get;
use Illuminate\Support\Arr; // <-- 1. TAMBAHKAN USE STATEMENT INI
use Illuminate\Database\Eloquent\Model; // <-- Pastikan 'use' statement ini ada

class EditPeserta extends EditRecord
{
    protected static string $resource = PesertaResource::class;

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $lampiran = $this->record->lampiran;

        if ($lampiran) {
            $data['no_surat_tugas'] = $lampiran->no_surat_tugas;

            // Kita tidak perlu mengisi data file di sini lagi
            // karena akan ditangani oleh komponen View kustom.
        }

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Ambil state non-dehydrated dari form
        $kelasBaru = data_get($this->form->getState(), 'instansi_kelas');

        if (! empty($data['instansi_id']) && $kelasBaru !== null) {
            $base = Instansi::find($data['instansi_id']);

            if ($base) {
                // KUNCI: masukkan 'kelas' supaya sekolah sama + kelas beda → record beda
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

                // Re-assign peserta ke instansi hasil create/update
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
                                    ->searchable()
                                    ->preload()
                                    ->required()
                                    ->live()
                                    ->afterStateUpdated(function (Set $set, ?string $state) {
                                        if ($state && ($instansi = Instansi::find($state))) {
                                            $set('cabangDinas_id', $instansi->cabangDinas_id);
                                            $set('instansi_kelas', $instansi->kelas); // sinkron awal
                                        } else {
                                            $set('instansi_kelas', null);
                                        }
                                    })
                                    ->afterStateHydrated(function (Get $get, Set $set, ?Model $record) {
                                        if ($record?->instansi) {
                                            $set('instansi_kelas', $record->instansi->kelas); // prefill saat membuka Edit
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

                                // Field logic (BUKAN kolom tabel peserta)

                                TextInput::make('instansi_kelas')
                                    ->label('Kelas (Instansi)')
                                    ->required()
                                    ->dehydrated(false)   // keep this
                                    ->reactive()
                                    ->afterStateUpdated(function (Get $get, Set $set, ?string $state) {
                                        // $state = nilai terbaru field instansi_kelas
                                        if ($state === null || $state === '') {
                                            return;
                                        }

                                        $currentInstansiId = $get('instansi_id');
                                        if (! $currentInstansiId) {
                                            return;
                                        }

                                        $base = Instansi::find($currentInstansiId);
                                        if (! $base) {
                                            return;
                                        }

                                        $target = Instansi::updateOrCreate(
                                            [
                                                'asal_instansi'   => $base->asal_instansi,
                                                'alamat_instansi' => $base->alamat_instansi,
                                                'kota'            => $base->kota,
                                                'kota_id'         => $base->kota_id,
                                                'kelas'           => $state, // ← use $state, not $kelas
                                            ],
                                            [
                                                'bidang_keahlian' => $base->bidang_keahlian,
                                                'cabangDinas_id' => $base->cabangDinas_id,
                                            ]
                                        );

                                        // Ganti dropdown instansi di form saat itu juga
                                        $set('instansi_id', $target->id);
                                    })
                                    ->helperText('Mengubah ini akan otomatis membuat/memilih instansi sesuai kelas yang baru.'),
                            ])
                            ->columns(2),

                        Section::make('Lampiran Dokumen')
                            ->description('Dokumen-dokumen pendukung yang diunggah oleh pendaftar.')
                            ->schema([
                                TextInput::make('no_surat_tugas')
                                    ->label('Nomor Surat Tugas'),

                                // KTP
                                View::make('ktp_preview')->view('components.file-preview-with-download')->viewData(['filePath' => $this->record->lampiran?->fc_ktp]),
                                FileUpload::make('fc_ktp')->label('Unggah Fotocopy KTP Baru (Opsional)')->disk('public')->dehydrated(fn($state) => filled($state)), // <-- TAMBAHKAN INI


                                // Ijazah
                                View::make('ijazah_preview')->view('components.file-preview-with-download')->viewData(['filePath' => $this->record->lampiran?->fc_ijazah]),
                                FileUpload::make('fc_ijazah')->label('Unggah Fotocopy Ijazah Baru (Opsional)')->disk('public')->dehydrated(fn($state) => filled($state)), // <-- TAMBAHKAN INI


                                // Surat Tugas
                                View::make('surat_tugas_preview')->view('components.file-preview-with-download')->viewData(['filePath' => $this->record->lampiran?->fc_surat_tugas]),
                                FileUpload::make('fc_surat_tugas')->label('Unggah Fotocopy Surat Tugas Baru (Opsional)')->disk('public')->dehydrated(fn($state) => filled($state)), // <-- TAMBAHKAN INI


                                // Surat Sehat
                                View::make('surat_sehat_preview')->view('components.file-preview-with-download')->viewData(['filePath' => $this->record->lampiran?->fc_surat_sehat]),
                                FileUpload::make('fc_surat_sehat')->label('Unggah Surat Keterangan Sehat Baru (Opsional)')->disk('public')->dehydrated(fn($state) => filled($state)), // <-- TAMBAHKAN INI


                                // Pas Foto
                                View::make('pas_foto_preview')->view('components.file-preview-with-download')->viewData(['filePath' => $this->record->lampiran?->pas_foto]),
                                FileUpload::make('pas_foto')->label('Unggah Pas Foto Baru (Opsional)')->disk('public')->dehydrated(fn($state) => filled($state)), // <-- TAMBAHKAN INI


                            ])->columns(1), // Menggunakan 1 kolom agar rapi

                    ]),
            ]);
    }
    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        // Memulai transaksi database untuk memastikan integritas data
        DB::beginTransaction();

        try {
            // 1. Definisikan semua key yang dimiliki oleh model Lampiran
            $lampiranKeys = [
                'no_surat_tugas',
                'fc_ktp',
                'fc_ijazah',
                'fc_surat_tugas',
                'fc_surat_sehat',
                'pas_foto'
            ];

            // 2. Ambil data HANYA untuk model Peserta (selain data lampiran)
            $pesertaData = Arr::except($data, $lampiranKeys);
            $record->update($pesertaData);

            // 3. Ambil data HANYA untuk model Lampiran (jika ada di dalam form data)
            $lampiranData = Arr::only($data, $lampiranKeys);

            // 4. Update atau Buat data Lampiran hanya jika ada data yang relevan
            if (!empty($lampiranData)) {
                if ($record->lampiran) {
                    // Jika lampiran sudah ada, update
                    $record->lampiran->update($lampiranData);
                } else {
                    // Jika lampiran belum ada, buat baru
                    $record->lampiran()->create($lampiranData);
                }
            }

            // Jika semua berhasil, commit transaksi
            DB::commit();

            return $record;
        } catch (\Exception $e) {
            // Jika terjadi error, batalkan semua perubahan
            DB::rollBack();

            // Lemparkan kembali error untuk ditampilkan
            throw $e;
        }
    }
}
