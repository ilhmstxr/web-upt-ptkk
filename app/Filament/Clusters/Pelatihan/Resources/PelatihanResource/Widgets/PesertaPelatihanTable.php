<?php

namespace App\Filament\Clusters\Pelatihan\Resources\PelatihanResource\Widgets;

use App\Models\KompetensiPelatihan;
use App\Models\PendaftaranPelatihan;
use App\Services\EmailService;
use Filament\Forms;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\DB;
use App\Models\LampiranPeserta;
use App\Models\Instansi;
use App\Models\User;
use App\Models\Peserta;

class PesertaPelatihanTable extends BaseWidget
{
    public const DEFAULT_CP_NAMA = 'Sdri. Admin';
    public const DEFAULT_CP_PHONE = '082249999447';
    /**
     * Record Pelatihan (DISET VIA mount / data)
     */
    public ?Model $record = null;

    /**
     * Optional: filter global kompetensi (misal saat view-kompetensi)
     */
    public ?int $kompetensiPelatihanId = null;

    protected int|string|array $columnSpan = 'full';

    /**
     * Mount fleksibel:
     * - Aman jika widget render duluan (record belum ada)
     * - Support:
     *   1) @livewire(..., ['record' => $record, 'kompetensiPelatihanId' => ...])
     *   2) <x-filament-widgets::widget ... :data="['record'=>..., 'kompetensiPelatihanId'=>...]" />
     */
    public function mount(?Model $record = null, ?int $kompetensiPelatihanId = null, array $data = []): void
    {
        $this->record = $record ?? ($data['record'] ?? null);
        $this->kompetensiPelatihanId = $kompetensiPelatihanId ?? ($data['kompetensiPelatihanId'] ?? null);
    }

    /**
     * Query utama (SELALU return Builder)
     */
    protected function pesertaQuery(): Builder
    {
        if (! $this->record?->getKey()) {
            return PendaftaranPelatihan::query()->whereRaw('1 = 0');
        }

        return PendaftaranPelatihan::query()
            ->where('pelatihan_id', $this->record->getKey())
            ->when(
                $this->kompetensiPelatihanId,
                fn(Builder $q) => $q->where('kompetensi_pelatihan_id', $this->kompetensiPelatihanId)
            )
            ->with([
                'peserta.user',
                'peserta.instansi.cabangDinas',
                'kompetensiPelatihan.kompetensi',
                'pelatihan',
                'penempatanAsrama.kamarPelatihan.kamar',
            ]);
    }

    /**
     * Required oleh TableWidget
     */
    protected function getTableQuery(): Builder
    {
        return $this->pesertaQuery();
    }

    protected function getKompetensiOptions(): array
    {
        if (! $this->record?->getKey()) {
            return [];
        }

        return KompetensiPelatihan::query()
            ->with('kompetensi')
            ->where('pelatihan_id', $this->record->getKey())
            ->get()
            ->pluck('kompetensi.nama_kompetensi', 'id')
            ->toArray();
    }



    public static function getPesertaFormSchema(): array
    {
        return [
            Forms\Components\Section::make('Informasi Pendaftar')
                ->schema([
                    Forms\Components\TextInput::make('nama')->label('Nama Peserta')->required(),
                    Forms\Components\TextInput::make('nomor_registrasi')->label('Nomor Registrasi')->disabled(),
                    Forms\Components\TextInput::make('nik')->label('NIK'),
                    Forms\Components\TextInput::make('no_hp')->label('No. HP'),
                    Forms\Components\TextInput::make('email')->label('Email'),
                    Forms\Components\TextInput::make('tempat_lahir')->label('Tempat Lahir'),
                    Forms\Components\DateTimePicker::make('tanggal_lahir')->label('Tanggal Lahir'),
                    Forms\Components\TextInput::make('jenis_kelamin')->label('Jenis Kelamin'),
                    Forms\Components\TextInput::make('agama')->label('Agama'),
                    Forms\Components\Textarea::make('alamat')->label('Alamat')->columnSpanFull(),

                    Forms\Components\TextInput::make('pelatihan_nama')->label('Pelatihan')->disabled(),
                    Forms\Components\TextInput::make('kompetensi_nama')->label('Kompetensi')->disabled(),
                    Forms\Components\TextInput::make('kelas')->label('Kelas'),
                    Forms\Components\DateTimePicker::make('tanggal_pendaftaran')->label('Tanggal Pendaftaran')->disabled(),
                ])
                ->columns(2),

            Forms\Components\Section::make('Detail Instansi')
                ->schema([
                    Forms\Components\TextInput::make('asal_instansi')->label('Asal Sekolah / Instansi'),
                    Forms\Components\TextInput::make('alamat_instansi')->label('Alamat Instansi'),
                    Forms\Components\TextInput::make('kota')->label('Kota / Kabupaten'),
                    Forms\Components\TextInput::make('cabang_dinas_nama')->label('Cabang Dinas')->disabled(), // Disabled because it's just a display name
                ])->columns(2),

            Forms\Components\Section::make('Verifikasi Berkas & Lampiran')
                ->schema([
                    Forms\Components\Placeholder::make('lampiran_view')
                        ->label('Lampiran')
                        ->content(function ($record) {
                            $lampiran = $record->peserta?->lampiran;
                            if (! $lampiran) return 'Belum ada berkas lampiran.';

                            $ktp = $lampiran->fc_ktp_url;
                            $ijazah = $lampiran->fc_ijazah_url;
                            $suratTugas = $lampiran->fc_surat_tugas_url;
                            $suratSehat = $lampiran->fc_surat_sehat_url;
                            $foto = $lampiran->pas_foto_url;

                            // Helper function untuk render link/preview
                            $renderFile = function ($label, $url) {
                                if (! $url) {
                                    return '<div><strong>' . $label . ':</strong> <span class="text-gray-400 italic">Tidak ada file</span></div>';
                                }

                                $isImage = preg_match('/\.(jpg|jpeg|png|webp)$/i', $url);
                                $isPdf = str_ends_with(strtolower($url), '.pdf');

                                $content = '';

                                if ($isImage) {
                                    $content = '
                                            <div class="mt-1">
                                                <a href="' . $url . '" target="_blank">
                                                    <img src="' . $url . '" alt="' . $label . '" class="w-full max-w-[200px] rounded-lg border border-gray-200 shadow-sm hover:scale-105 transition-transform duration-200">
                                                </a>
                                            </div>';
                                } elseif ($isPdf) {
                                    $content = '
                                            <div class="mt-1">
                                                <a href="' . $url . '" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors group">
                                                    <svg class="w-5 h-5 text-red-500 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                                                    Lihat Dokumen PDF
                                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                                </a>
                                            </div>';
                                } else {
                                    // Default fallback
                                    $content = '
                                            <div class="mt-1">
                                                <a href="' . $url . '" target="_blank" class="text-primary-600 hover:underline flex items-center gap-1">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                                    Unduh File
                                                </a>
                                            </div>';
                                }

                                return '<div class="mb-2"><strong>' . $label . ':</strong>' . $content . '</div>';
                            };

                            return new \Illuminate\Support\HtmlString('
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-4 bg-gray-50/50 dark:bg-gray-900/50 rounded-xl border border-dashed border-gray-200 dark:border-gray-700">
                                        ' . $renderFile('KTP', $ktp) . '
                                        ' . $renderFile('Ijazah', $ijazah) . '
                                        ' . $renderFile('Surat Tugas', $suratTugas) . '
                                        ' . $renderFile('Surat Sehat', $suratSehat) . '
                                        ' . $renderFile('Pas Foto', $foto) . '
                                    </div>
                                ');
                        })
                        ->columnSpanFull(),
                ]),
        ];
    }

    public function table(Table $table): Table
    {
        return $table
            // ✅ lazy query closure -> dipanggil setelah mount
            ->query(fn() => $this->pesertaQuery())

            ->columns([
                Tables\Columns\TextColumn::make('nomor_registrasi')
                    ->label('No. Registrasi')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('peserta.nama')
                    ->label('Info Peserta')
                    ->description(
                        fn(PendaftaranPelatihan $record) => ($record->peserta?->user?->email ?? '-') . ' | ' .
                            ($record->peserta?->no_hp ?? '-')
                    )
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('kompetensiPelatihan.kompetensi.nama_kompetensi')
                    ->label('Kompetensi')
                    ->sortable(),

                Tables\Columns\TextColumn::make('tanggal_pendaftaran')
                    ->label('Tanggal Daftar')
                    ->dateTime('d M Y H:i')
                    ->sortable(),

                Tables\Columns\TextColumn::make('status_pendaftaran')
                    ->label('Status')
                    ->badge()
                    ->color(fn(?string $state) => match (strtolower($state ?? '')) {
                        'verifikasi' => 'warning',
                        'diterima'   => 'success',
                        'ditolak'    => 'danger',
                        'cadangan'   => 'gray',
                        default      => 'gray',
                    }),
            ])

            ->filters([
                Tables\Filters\SelectFilter::make('status_pendaftaran')
                    ->label('Status')
                    ->options([
                        'verifikasi' => 'Verifikasi',
                        'diterima'   => 'Diterima',
                        'ditolak'    => 'Ditolak',
                        'cadangan'   => 'Cadangan',
                    ]),

                Tables\Filters\SelectFilter::make('kompetensi_pelatihan_id')
                    ->label('Kompetensi')
                    ->options(fn() => $this->getKompetensiOptions())
                    ->query(
                        fn(Builder $query, array $data) =>
                        $query->when(
                            $data['value'] ?? null,
                            fn(Builder $q, $value) => $q->where('kompetensi_pelatihan_id', $value)
                        )
                    )
                    ->visible(fn() => is_null($this->kompetensiPelatihanId)),
            ])

            ->headerActions([])

            ->actions([
                Tables\Actions\ViewAction::make()
                    ->mutateRecordDataUsing(function (array $data, \App\Models\PendaftaranPelatihan $record): array {
                        if ($record->peserta) {
                            $data['nama'] = $record->peserta->nama;
                            $data['nik'] = $record->peserta->nik;
                            $data['no_hp'] = $record->peserta->no_hp;
                            $data['tempat_lahir'] = $record->peserta->tempat_lahir;
                            $data['tanggal_lahir'] = $record->peserta->tanggal_lahir;
                            $data['jenis_kelamin'] = $record->peserta->jenis_kelamin;
                            $data['agama'] = $record->peserta->agama;
                            $data['alamat'] = $record->peserta->alamat;
                            $data['email'] = $record->peserta->user->email ?? '-';

                            if ($record->peserta->instansi) {
                                $data['asal_instansi'] = $record->peserta->instansi->asal_instansi;
                                $data['alamat_instansi'] = $record->peserta->instansi->alamat_instansi;
                                $data['kota'] = $record->peserta->instansi->kota;
                                $data['cabang_dinas_nama'] = $record->peserta->instansi->cabangDinas->nama ?? '-';
                            }
                        }

                        $data['pelatihan_nama'] = $record->pelatihan->nama_pelatihan ?? '-';
                        $data['kompetensi_nama'] = $record->kompetensiPelatihan->kompetensi->nama_kompetensi ?? '-';

                        return $data;
                    })
                    ->form(self::getPesertaFormSchema()),

                Tables\Actions\Action::make('accept')
                    ->label('Terima')
                    ->icon('heroicon-o-check')
                    ->color('success')
                    ->requiresConfirmation(fn() => ! session()->get('suppress_peserta_approval_confirmation'))
                    ->form([
                        Forms\Components\Checkbox::make('dont_show_again')
                            ->label('Jangan tampilkan lagi (Sesi ini)'),
                        Forms\Components\Checkbox::make('kirim_email_konfirmasi')
                            ->label('Kirim Email Konfirmasi ke Peserta')
                            ->default(true)
                            ->reactive(), // Agar input dibawah bisa conditional (opsional, tapi bagus practice)

                        Forms\Components\TextInput::make('cp_nama')
                            ->label('Nama CP')
                            ->default(fn(PendaftaranPelatihan $record) => $record->pelatihan->nama_cp ?? self::DEFAULT_CP_NAMA)
                            ->required()
                            ->visible(fn(Forms\Get $get) => $get('kirim_email_konfirmasi')), // Hanya muncul jika kirim email

                        Forms\Components\TextInput::make('cp_phone')
                            ->label('No. Telp CP')
                            ->default(fn(PendaftaranPelatihan $record) => $record->pelatihan->no_cp ?? self::DEFAULT_CP_PHONE)
                            ->required()
                            ->visible(fn(Forms\Get $get) => $get('kirim_email_konfirmasi')),
                    ])
                    ->action(function (PendaftaranPelatihan $record, array $data) {
                        if (($data['dont_show_again'] ?? false) === true) {
                            session()->put('suppress_peserta_approval_confirmation', true);
                        }

                        $record->update(['status_pendaftaran' => 'diterima']);

                        // SEND EMAIL IF CHECKED
                        if ($data['kirim_email_konfirmasi'] ?? false) {
                            $emailPeserta = $record->peserta?->user?->email;

                            if ($emailPeserta) {
                                // Prepare data
                                $emailData = [
                                    'nama_pelatihan' => $record->pelatihan->nama_pelatihan,
                                    'id_peserta'     => $record->nomor_registrasi,
                                    'password'       => \Carbon\Carbon::parse($record->peserta->tanggal_lahir)->format('dmY'),
                                    'nama_peserta'   => $record->peserta->nama,
                                    'asal_lembaga'   => $record->peserta->instansi->asal_instansi ?? '-',
                                    'cabang_dinas'   => $record->peserta->instansi->cabangDinas->nama ?? '-',
                                    'kompetensi'     => $record->kompetensiPelatihan->kompetensi->nama_kompetensi ?? '-',
                                    // Menggunakan helper penempatanAsramaAktif() yang ada di model PendaftaranPelatihan
                                    'kamar_asrama'   => $record->penempatanAsramaAktif()?->kamarPelatihan->kamar->nama_kamar ?? 'Belum ditentukan',
                                    'waktu_mulai'    => \Carbon\Carbon::parse($record->pelatihan->tanggal_mulai)->translatedFormat('d F Y'),
                                    'waktu_selesai'  => \Carbon\Carbon::parse($record->pelatihan->tanggal_selesai)->translatedFormat('d F Y'),
                                    'lokasi'         => $record->pelatihan->lokasi ?? 'UPT PTKK Surabaya',
                                    'alamat'         => 'Komplek Kampus Unesa Jl. Ketintang No.25, Ketintang, Kec. Gayungan, Surabaya, Jawa Timur 60231',
                                    'cp_nama'        => $data['cp_nama'] ?? 'Sdri. Admin',
                                    'cp_phone'       => $data['cp_phone'] ?? '082249999447',
                                    'email_penerima' => $emailPeserta,
                                ];

                                try {
                                    \Illuminate\Support\Facades\Mail::to($emailPeserta)->send(new \App\Mail\EmailKonfirmasi($emailData));

                                    Notification::make()
                                        ->title('Peserta diterima & Email dikirim')
                                        ->success()
                                        ->send();
                                } catch (\Exception $e) {
                                    Notification::make()
                                        ->title('Peserta diterima, namun GAGAL mengirim email')
                                        ->body('Error: ' . $e->getMessage())
                                        ->danger()
                                        ->persistent()
                                        ->send();
                                }
                                return;
                            }
                        }

                        // Fallback if email not checked or no email found, just notify success
                        Notification::make()
                            ->title('Peserta diterima')
                            ->success()
                            ->send();
                    })
                    ->visible(
                        fn(PendaftaranPelatihan $record) =>
                        strtolower((string) $record->status_pendaftaran) === 'verifikasi'
                    ),

                Tables\Actions\Action::make('reject')
                    ->label('Tolak')
                    ->icon('heroicon-o-x-mark')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->action(
                        fn(PendaftaranPelatihan $record) =>
                        $record->update(['status_pendaftaran' => 'ditolak'])
                    )
                    ->visible(
                        fn(PendaftaranPelatihan $record) =>
                        strtolower((string) $record->status_pendaftaran) === 'verifikasi'
                    ),

                Tables\Actions\EditAction::make()
                    ->slideOver()
                    ->mutateRecordDataUsing(function (array $data, \App\Models\PendaftaranPelatihan $record): array {
                        // Logic same as ViewAction for mapping flattening keys
                        if ($record->peserta) {
                            $data['nama'] = $record->peserta->nama;
                            $data['nik'] = $record->peserta->nik;
                            $data['no_hp'] = $record->peserta->no_hp;
                            $data['tempat_lahir'] = $record->peserta->tempat_lahir;
                            $data['tanggal_lahir'] = $record->peserta->tanggal_lahir;
                            $data['jenis_kelamin'] = $record->peserta->jenis_kelamin;
                            $data['agama'] = $record->peserta->agama;
                            $data['alamat'] = $record->peserta->alamat;
                            $data['email'] = $record->peserta->user->email ?? '-';

                            if ($record->peserta->instansi) {
                                $data['asal_instansi'] = $record->peserta->instansi->asal_instansi;
                                $data['alamat_instansi'] = $record->peserta->instansi->alamat_instansi;
                                $data['kota'] = $record->peserta->instansi->kota;
                                $data['cabang_dinas_nama'] = $record->peserta->instansi->cabangDinas->nama ?? '-';
                            }
                        }

                        $data['pelatihan_nama'] = $record->pelatihan->nama_pelatihan ?? '-';
                        $data['kompetensi_nama'] = $record->kompetensiPelatihan->kompetensi->nama_kompetensi ?? '-';

                        return $data;
                    })
                    ->form(self::getPesertaFormSchema())
                    ->using(function (PendaftaranPelatihan $record, array $data): PendaftaranPelatihan {
                        // Custom save logic to update relations
                        DB::transaction(function () use ($record, $data) {
                            $get = fn(string $key, $default = null) => $data[$key] ?? $default;

                            // 1) Update Instansi
                            if ($record->peserta && $record->peserta->instansi) {
                                $record->peserta->instansi->update([
                                    'asal_instansi'   => $get('asal_instansi', $record->peserta->instansi->asal_instansi),
                                    'alamat_instansi' => $get('alamat_instansi', $record->peserta->instansi->alamat_instansi),
                                    'kota'            => $get('kota', $record->peserta->instansi->kota),
                                    // 'cabangDinas_id' not updated here as it's just a display field in this form
                                ]);
                            }

                            // 2) Update User (Email/Name/Phone)
                            if ($record->peserta && $record->peserta->user) {
                                $record->peserta->user->update([
                                    'email' => $get('email', $record->peserta->user->email),
                                    'name'  => $get('nama', $record->peserta->user->name),
                                    'phone' => $get('no_hp', $record->peserta->user->phone),
                                ]);
                            }

                            // 3) Update Peserta
                            if ($record->peserta) {
                                $record->peserta->update([
                                    'nama'          => $get('nama', $record->peserta->nama),
                                    'nik'           => $get('nik', $record->peserta->nik),
                                    'no_hp'         => $get('no_hp', $record->peserta->no_hp),
                                    'tempat_lahir'  => $get('tempat_lahir', $record->peserta->tempat_lahir),
                                    'tanggal_lahir' => $get('tanggal_lahir', $record->peserta->tanggal_lahir),
                                    'jenis_kelamin' => $get('jenis_kelamin', $record->peserta->jenis_kelamin),
                                    'agama'         => $get('agama', $record->peserta->agama),
                                    'alamat'        => $get('alamat', $record->peserta->alamat),
                                ]);
                            }

                            // 4) Update Pendaftaran
                            $record->update([
                                'kelas' => $get('kelas', $record->kelas),
                            ]);
                        });

                        return $record;
                    }),

                Tables\Actions\DeleteAction::make()
                    ->visible(
                        fn(PendaftaranPelatihan $record) =>
                        strtolower((string) $record->status_pendaftaran) !== 'verifikasi'
                    ),
            ]);
    }
}
