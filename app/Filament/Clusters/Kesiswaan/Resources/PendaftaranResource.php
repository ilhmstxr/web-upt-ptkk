<?php

namespace App\Filament\Clusters\Kesiswaan\Resources;

use App\Filament\Clusters\Kesiswaan;
use App\Filament\Clusters\Kesiswaan\Resources\PendaftaranResource\Pages;
use App\Models\CabangDinas;
use App\Models\Kompetensi;
use App\Models\KompetensiPelatihan;
use App\Models\PendaftaranPelatihan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Facades\Log;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Illuminate\Support\Facades\DB;

class PendaftaranResource extends Resource
{
    protected static ?string $model = PendaftaranPelatihan::class;
    protected static ?string $cluster = Kesiswaan::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationLabel = 'Peserta';
    protected static ?string $modelLabel = 'Peserta';
    protected static ?string $pluralModelLabel = 'Peserta';

    /**
     * Eager load supaya table/view lebih cepat & menghindari N+1.
     */
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with([
            'peserta.lampiran',
            'peserta.instansi.cabangDinas',
            'peserta.user',
            'pelatihan',
            'kompetensiPelatihan.kompetensi',
            'penempatanAsrama.kamarPelatihan.kamar',
        ]);
    }

    /**
     * Ambil skor terakhir dari percobaan dengan gaya "TesResource":
     * 1) JOIN tes untuk pastikan pelatihan match (mengabaikan p.pelatihan_id yang sering NULL)
     * 2) Fallback: pakai p.tipe dan izinkan p.pelatihan_id NULL (buat data lama)
     *
     * NOTE: untuk fallback p.tipe, tipe harus enum asli: pre-test/post-test/survei
     */
    protected static function skorTerakhirByPelatihanDanTipe(int $pelatihanId, int $pesertaId, array $tipeList): ?float
    {
        // 1) Cara "TesResource": join tes
        $skor = DB::table('percobaan as p')
            ->join('tes as t', 't.id', '=', 'p.tes_id')
            ->where('t.pelatihan_id', $pelatihanId)
            ->whereIn('t.tipe', $tipeList)
            ->whereNotNull('p.skor')
            ->where('p.peserta_id', $pesertaId)
            ->orderByDesc('p.waktu_selesai')
            ->orderByDesc('p.updated_at')
            ->orderByDesc('p.id')
            ->value('p.skor');

        if ($skor !== null) {
            return (float) $skor;
        }

        // 2) Fallback: pakai p.tipe + toleransi pelatihan_id null
        $skor2 = DB::table('percobaan as p')
            ->where('p.peserta_id', $pesertaId)
            ->whereIn('p.tipe', $tipeList)
            ->whereNotNull('p.skor')
            ->where(function ($q) use ($pelatihanId) {
                $q->where('p.pelatihan_id', $pelatihanId)
                    ->orWhereNull('p.pelatihan_id');
            })
            ->orderByDesc('p.waktu_selesai')
            ->orderByDesc('p.updated_at')
            ->orderByDesc('p.id')
            ->value('p.skor');

        return $skor2 !== null ? (float) $skor2 : null;
    }

    /**
     * Hitung rata-rata dari nilai yang > 0 (pre/post/praktek).
     */
    protected static function hitungRataRata(?float $pre, ?float $post, ?float $praktek): float
    {
        $vals = array_filter(
            [(float) ($pre ?? 0), (float) ($post ?? 0), (float) ($praktek ?? 0)],
            fn($v) => is_numeric($v) && $v > 0
        );

        return count($vals) ? round(array_sum($vals) / count($vals), 2) : 0;
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            /**
             * =========================
             * CREATE: Wizard
             * =========================
             */
            Forms\Components\Wizard::make([
                Forms\Components\Wizard\Step::make('Pelatihan')
                    ->schema([
                        Forms\Components\Select::make('pelatihan_id')
                            ->label('Pelatihan')
                            ->relationship('pelatihan', 'nama_pelatihan')
                            ->reactive()
                            ->required(),

                        Forms\Components\Select::make('kompetensi_pelatihan_id')
                            ->label('Kompetensi Keahlian')
                            ->options(function (Forms\Get $get) {
                                $pelatihanId = $get('pelatihan_id');
                                if (! $pelatihanId) return [];

                                return KompetensiPelatihan::query()
                                    ->where('pelatihan_id', $pelatihanId)
                                    ->with('kompetensi')
                                    ->get()
                                    ->pluck('kompetensi.nama_kompetensi', 'id')
                                    ->all();
                            })
                            ->searchable()
                            ->preload()
                            ->required(),
                    ]),

                Forms\Components\Wizard\Step::make('Data Diri')
                    ->schema([
                        Forms\Components\Grid::make(2)->schema([
                            Forms\Components\TextInput::make('nama')
                                ->label('Nama Lengkap')
                                ->helperText('Tulis gelar jika ada (cth: S.Kom, S.Pd)')
                                ->required()
                                ->maxLength(150),

                            Forms\Components\TextInput::make('nik')
                                ->label('NIK')
                                ->required()
                                ->numeric()
                                ->length(16),

                            Forms\Components\TextInput::make('no_hp')
                                ->label('No. HP')
                                ->required()
                                ->tel(),

                            Forms\Components\TextInput::make('email')
                                ->email()
                                ->required(),

                            Forms\Components\TextInput::make('tempat_lahir')
                                ->label('Tempat Lahir')
                                ->required(),

                            Forms\Components\DatePicker::make('tanggal_lahir')
                                ->label('Tanggal Lahir')
                                ->required(),

                            Forms\Components\Select::make('jenis_kelamin')
                                ->label('Jenis Kelamin')
                                ->options([
                                    'Laki-laki' => 'Laki-laki',
                                    'Perempuan' => 'Perempuan',
                                ])
                                ->required(),

                            Forms\Components\TextInput::make('agama')
                                ->label('Agama')
                                ->required(),

                            Forms\Components\Textarea::make('alamat')
                                ->label('Alamat Lengkap')
                                ->columnSpanFull()
                                ->required(),
                        ]),
                    ]),

                Forms\Components\Wizard\Step::make('Instansi')
                    ->schema([
                        Forms\Components\TextInput::make('asal_instansi')
                            ->label('Asal Sekolah / Instansi')
                            ->required(),

                        Forms\Components\TextInput::make('alamat_instansi')
                            ->required(),

                        Forms\Components\Grid::make(2)->schema([
                            Forms\Components\TextInput::make('kota')
                                ->label('Kota / Kabupaten')
                                ->required(),

                            Forms\Components\Select::make('cabang_dinas_id')
                                ->label('Cabang Dinas')
                                ->options(CabangDinas::query()->pluck('nama', 'id')->all())
                                ->searchable()
                                ->preload()
                                ->required(),

                            Forms\Components\TextInput::make('kelas')
                                ->label('Kelas')
                                ->required(),
                        ]),
                    ]),

                Forms\Components\Wizard\Step::make('Lampiran')
                    ->schema([
                        Forms\Components\FileUpload::make('fc_ktp')
                            ->label('Scan KTP')
                            ->disk('public')
                            ->directory('lampiran-peserta')
                            ->acceptedFileTypes(['application/pdf', 'image/*'])
                            ->maxSize(2048)
                            ->required(),

                        Forms\Components\FileUpload::make('fc_ijazah')
                            ->label('Scan Ijazah')
                            ->disk('public')
                            ->directory('lampiran-peserta')
                            ->acceptedFileTypes(['application/pdf', 'image/*'])
                            ->maxSize(2048)
                            ->required(),

                        Forms\Components\FileUpload::make('fc_surat_tugas')
                            ->label('Surat Tugas')
                            ->disk('public')
                            ->directory('lampiran-peserta')
                            ->acceptedFileTypes(['application/pdf', 'image/*'])
                            ->maxSize(2048),

                        Forms\Components\FileUpload::make('fc_surat_sehat')
                            ->label('Surat Sehat')
                            ->disk('public')
                            ->directory('lampiran-peserta')
                            ->acceptedFileTypes(['application/pdf', 'image/*'])
                            ->maxSize(2048),

                        Forms\Components\FileUpload::make('pas_foto')
                            ->label('Pas Foto')
                            ->disk('public')
                            ->directory('lampiran-peserta')
                            ->image()
                            ->maxSize(2048)
                            ->required(),

                        Forms\Components\TextInput::make('nomor_surat_tugas')
                            ->label('Nomor Surat Tugas'),
                    ]),
            ])
                ->columnSpanFull()
                ->visible(fn($record) => blank($record)),

            /**
             * =========================
             * EDIT: Info + Lampiran + Status
             * =========================
             */
            Forms\Components\Group::make()
                ->schema([
                    Forms\Components\Section::make('Informasi Pendaftar')
                        ->schema([
                            Forms\Components\TextInput::make('nama')->label('Nama Peserta')->required()->maxLength(150),
                            Forms\Components\TextInput::make('nomor_registrasi')->label('Nomor Registrasi')->disabled()->dehydrated(false),
                            Forms\Components\TextInput::make('nik')->label('NIK')->numeric()->length(16)->required(),
                            Forms\Components\TextInput::make('no_hp')->label('No. HP')->tel()->required(),
                            Forms\Components\TextInput::make('email')->label('Email')->email()->required(),
                            Forms\Components\TextInput::make('tempat_lahir')->required(),
                            Forms\Components\DatePicker::make('tanggal_lahir')->required(),
                            Forms\Components\Select::make('jenis_kelamin')->options(['Laki-laki' => 'Laki-laki', 'Perempuan' => 'Perempuan'])->required(),
                            Forms\Components\TextInput::make('agama')->required(),
                            Forms\Components\Textarea::make('alamat')->columnSpanFull()->required(),

                            Forms\Components\Placeholder::make('pelatihan_name')->label('Pelatihan')->content(fn($record) => $record?->pelatihan?->nama_pelatihan ?? '-'),
                            Forms\Components\Placeholder::make('kompetensi_name')->label('Kompetensi')->content(fn($record) => $record?->kompetensiPelatihan?->kompetensi?->nama_kompetensi ?? '-'),
                            Forms\Components\TextInput::make('kelas')->label('Kelas')->required(),
                            Forms\Components\TextInput::make('tanggal_pendaftaran')->label('Tanggal Pendaftaran')->disabled()->dehydrated(false),
                        ])
                        ->columns(2),

                    Forms\Components\Section::make('Detail Instansi')
                        ->schema([
                            Forms\Components\TextInput::make('asal_instansi')->label('Asal Sekolah / Instansi')->required(),
                            Forms\Components\TextInput::make('alamat_instansi')->required(),
                            Forms\Components\TextInput::make('kota')->label('Kota / Kabupaten')->required(),
                            Forms\Components\Select::make('cabangDinas_id')->label('Cabang Dinas')
                                ->options(CabangDinas::query()->pluck('nama', 'id')->all())
                                ->searchable()->preload()->required(),
                        ])->columns(2),

                    Forms\Components\Section::make('Verifikasi Berkas & Lampiran')
                        ->schema([
                            Forms\Components\FileUpload::make('fc_ktp')->label('Scan KTP')->disk('public')->directory('lampiran-peserta')->acceptedFileTypes(['application/pdf', 'image/*'])->maxSize(2048),
                            Forms\Components\FileUpload::make('fc_ijazah')->label('Scan Ijazah')->disk('public')->directory('lampiran-peserta')->acceptedFileTypes(['application/pdf', 'image/*'])->maxSize(2048),
                            Forms\Components\FileUpload::make('fc_surat_tugas')->label('Surat Tugas')->disk('public')->directory('lampiran-peserta')->acceptedFileTypes(['application/pdf', 'image/*'])->maxSize(2048),
                            Forms\Components\FileUpload::make('fc_surat_sehat')->label('Surat Sehat')->disk('public')->directory('lampiran-peserta')->acceptedFileTypes(['application/pdf', 'image/*'])->maxSize(2048),
                            Forms\Components\FileUpload::make('pas_foto')->label('Pas Foto')->disk('public')->directory('lampiran-peserta')->image()->maxSize(2048),
                            Forms\Components\TextInput::make('nomor_surat_tugas')->label('Nomor Surat Tugas'),
                        ])->columns(2),

                    /**
                     * NILAI PESERTA:
                     * - Pre/Post auto hydrate dari percobaan (kalau kosong di DB)
                     * - Tetap bisa diinput manual (tidak disabled)
                     */
                    Section::make('Nilai Peserta')
                        ->schema([
                            TextInput::make('nilai_pre_test')
                                ->label('Nilai Pre-Test (Auto, bisa diubah manual)')
                                ->numeric()
                                ->reactive()
                                ->dehydrated(true)
                                ->afterStateHydrated(function (Set $set, Get $get, $record) {
                                    if (! $record) return;

                                    $current = $get('nilai_pre_test');
                                    $isEmpty = $current === null || (float) $current <= 0;

                                    if (! $isEmpty) return; // kalau sudah ada nilai, jangan ditimpa

                                    $pesertaId   = (int) ($record->peserta_id ?? 0);
                                    $pelatihanId = (int) ($record->pelatihan_id ?? 0);
                                    if (! $pesertaId || ! $pelatihanId) return;

                                    // Tipe enum asli
                                    $pre = self::skorTerakhirByPelatihanDanTipe($pelatihanId, $pesertaId, ['pre-test']);
                                    if ($pre !== null) {
                                        $set('nilai_pre_test', $pre);
                                    }

                                    $preV  = (float) ($get('nilai_pre_test') ?? 0);
                                    $postV = (float) ($get('nilai_post_test') ?? 0);
                                    $prakV = (float) ($get('nilai_praktek') ?? 0);
                                    $set('rata_rata', self::hitungRataRata($preV, $postV, $prakV));
                                })
                                ->afterStateUpdated(function (Set $set, Get $get) {
                                    $preV  = (float) ($get('nilai_pre_test') ?? 0);
                                    $postV = (float) ($get('nilai_post_test') ?? 0);
                                    $prakV = (float) ($get('nilai_praktek') ?? 0);
                                    $set('rata_rata', self::hitungRataRata($preV, $postV, $prakV));
                                }),

                            TextInput::make('nilai_post_test')
                                ->label('Nilai Post-Test (Auto, bisa diubah manual)')
                                ->numeric()
                                ->reactive()
                                ->dehydrated(true)
                                ->afterStateHydrated(function (Set $set, Get $get, $record) {
                                    if (! $record) return;

                                    $current = $get('nilai_post_test');
                                    $isEmpty = $current === null || (float) $current <= 0;

                                    if (! $isEmpty) return;

                                    $pesertaId   = (int) ($record->peserta_id ?? 0);
                                    $pelatihanId = (int) ($record->pelatihan_id ?? 0);
                                    if (! $pesertaId || ! $pelatihanId) return;

                                    $post = self::skorTerakhirByPelatihanDanTipe($pelatihanId, $pesertaId, ['post-test']);
                                    if ($post !== null) {
                                        $set('nilai_post_test', $post);
                                    }

                                    $preV  = (float) ($get('nilai_pre_test') ?? 0);
                                    $postV = (float) ($get('nilai_post_test') ?? 0);
                                    $prakV = (float) ($get('nilai_praktek') ?? 0);
                                    $set('rata_rata', self::hitungRataRata($preV, $postV, $prakV));
                                })
                                ->afterStateUpdated(function (Set $set, Get $get) {
                                    $preV  = (float) ($get('nilai_pre_test') ?? 0);
                                    $postV = (float) ($get('nilai_post_test') ?? 0);
                                    $prakV = (float) ($get('nilai_praktek') ?? 0);
                                    $set('rata_rata', self::hitungRataRata($preV, $postV, $prakV));
                                }),

                            TextInput::make('nilai_praktek')
                                ->label('Nilai Praktik (Input Manual)')
                                ->numeric()
                                ->minValue(0)
                                ->maxValue(100)
                                ->reactive()
                                ->dehydrated(true)
                                ->afterStateUpdated(function (Set $set, Get $get) {
                                    $preV  = (float) ($get('nilai_pre_test') ?? 0);
                                    $postV = (float) ($get('nilai_post_test') ?? 0);
                                    $prakV = (float) ($get('nilai_praktek') ?? 0);
                                    $set('rata_rata', self::hitungRataRata($preV, $postV, $prakV));
                                }),

                            TextInput::make('rata_rata')
                                ->label('Rata-Rata')
                                ->numeric()
                                ->disabled()
                                ->dehydrated(true)
                                ->afterStateHydrated(function (Set $set, Get $get) {
                                    $preV  = (float) ($get('nilai_pre_test') ?? 0);
                                    $postV = (float) ($get('nilai_post_test') ?? 0);
                                    $prakV = (float) ($get('nilai_praktek') ?? 0);
                                    $set('rata_rata', self::hitungRataRata($preV, $postV, $prakV));
                                }),
                        ])
                        ->columns(2),
                ])
                ->columnSpan(['lg' => 2])
                ->visible(fn($record) => filled($record)),

            Forms\Components\Group::make()
                ->schema([
                    Forms\Components\Section::make('Status Pendaftaran')
                        ->schema([
                            Forms\Components\Select::make('status_pendaftaran')
                                ->label('Status Pendaftaran')
                                ->options([
                                    'verifikasi' => 'Verifikasi',
                                    'diterima'   => 'Diterima',
                                    'ditolak'    => 'Ditolak',
                                ])
                                ->required()
                                ->native(false),
                        ]),
                ])
                ->columnSpan(['lg' => 1])
                ->visible(fn($record) => filled($record)),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nomor_registrasi')
                    ->label('No. Registrasi')
                    ->icon('heroicon-o-identification')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('peserta.nama')
                    ->label('Nama Peserta')
                    ->icon('heroicon-o-user')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('pelatihan.nama_pelatihan')
                    ->label('Pelatihan')
                    ->icon('heroicon-o-academic-cap')
                    ->wrap()
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('kelas')
                    ->label('Kelas')
                    ->icon('heroicon-o-building-office-2')
                    ->badge()
                    ->color('gray')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('status_pendaftaran')
                    ->label('Status')
                    ->icon('heroicon-o-shield-check')
                    ->badge()
                    ->formatStateUsing(fn($state) => match (strtolower((string) $state)) {
                        'verifikasi' => 'Verifikasi',
                        'diterima'   => 'Diterima',
                        'ditolak'    => 'Ditolak',
                        default      => (string) $state,
                    })
                    ->color(fn(?string $state): string => match (strtolower((string) $state)) {
                        'verifikasi' => 'warning',
                        'diterima'   => 'success',
                        'ditolak'    => 'danger',
                        default      => 'gray',
                    })
                    ->sortable(),

                Tables\Columns\TextColumn::make('tanggal_pendaftaran')
                    ->label('Tanggal Daftar')
                    ->icon('heroicon-o-calendar-days')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status_pendaftaran')
                    ->label('Filter Status')
                    ->options([
                        'verifikasi' => 'Verifikasi',
                        'diterima'   => 'Diterima',
                        'ditolak'    => 'Ditolak',
                    ]),

                Tables\Filters\SelectFilter::make('pelatihan_id')
                    ->label('Filter Pelatihan')
                    ->relationship('pelatihan', 'nama_pelatihan'),

                Tables\Filters\SelectFilter::make('kompetensi_pelatihan_id')
                    ->label('Kompetensi')
                    ->options(fn() => Kompetensi::query()->pluck('nama_kompetensi', 'id')->all())
                    ->query(function (Builder $query, array $data) {
                        $kompetensiId = $data['value'] ?? null;
                        if (! $kompetensiId) return $query;

                        // Lebih stabil karena kolom kompetensi_id ada di pendaftaran_pelatihan
                        return $query->where('kompetensi_id', $kompetensiId);
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),

                Tables\Actions\Action::make('accept')
                    ->label('Terima')
                    ->icon('heroicon-o-check')
                    ->color('success')
                    ->requiresConfirmation(fn() => ! session()->get('suppress_pendaftaran_approval'))
                    ->modalIcon('heroicon-o-check')
                    ->modalHeading('Terima Peserta')
                    ->modalDescription('Apakah Anda yakin ingin menerima peserta ini? Status akan berubah menjadi Diterima.')
                    ->modalSubmitActionLabel('Ya, Terima')
                    ->form([
                        Forms\Components\Checkbox::make('dont_show_again')
                            ->label('Jangan tampilkan lagi (Sesi ini)'),

                        Forms\Components\TextInput::make('cp_nama')
                            ->label('Nama CP')
                            ->default(fn(PendaftaranPelatihan $record) => $record->pelatihan->nama_cp ?? 'Sdri. Admin')
                            ->required(),

                        Forms\Components\TextInput::make('cp_phone')
                            ->label('No. Telp CP')
                            ->default(fn(PendaftaranPelatihan $record) => $record->pelatihan->no_cp ?? '082249999447')
                            ->required(),
                    ])
                    ->action(function (PendaftaranPelatihan $record, array $data) {
                        if (($data['dont_show_again'] ?? false) === true) {
                            session()->put('suppress_pendaftaran_approval', true);
                        }

                        $record->update(['status_pendaftaran' => 'diterima']);

                        try {
                            $record->load([
                                'peserta.instansi.cabangDinas',
                                'peserta.user',
                                'kompetensiPelatihan.kompetensi',
                                'pelatihan',
                                'penempatanAsrama.kamarPelatihan.kamar',
                            ]);

                            $kamarAsrama =
                                $record->penempatanAsramaAktif?->kamarPelatihan?->kamar?->nomor_kamar
                                ? 'Kamar ' . $record->penempatanAsramaAktif->kamarPelatihan->kamar->nomor_kamar
                                : 'Belum Ditentukan';

                            $emailData = [
                                'id_peserta'     => $record->nomor_registrasi,
                                'nama_peserta'   => $record->peserta?->nama ?? '-',
                                'asal_lembaga'   => $record->peserta?->instansi?->asal_instansi ?? '-',
                                'cabang_dinas'   => $record->peserta?->instansi?->cabangDinas?->nama ?? '-',
                                'kompetensi'     => $record->kompetensiPelatihan?->kompetensi?->nama_kompetensi ?? '-',
                                'kamar_asrama'   => $kamarAsrama,
                                'waktu_mulai'    => $record->pelatihan?->tanggal_mulai
                                    ? $record->pelatihan->tanggal_mulai->translatedFormat('d F Y')
                                    : '-',
                                'waktu_selesai'  => $record->pelatihan?->tanggal_selesai
                                    ? $record->pelatihan->tanggal_selesai->translatedFormat('d F Y')
                                    : '-',
                                'lokasi'         => 'UPT PTKK Surabaya',
                                'alamat_lengkap' => $record->pelatihan?->lokasi_text ?? 'Jl. Ketintang Tengah no 25 komplek UNESA SURABAYA,',
                                'cp_nama'        => $data['cp_nama'] ?? 'Sdri. Admin',
                                'cp_phone'       => $data['cp_phone'] ?? '082249999447',
                            ];

                            $email = $record->peserta?->user?->email ?? null;
                            if ($email) {
                                \App\Services\EmailService::send(
                                    $email,
                                    'Informasi Pendaftaran dan Undangan Pelatihan',
                                    '',
                                    $emailData,
                                    'template_surat.informasi_kegiatan'
                                );
                            }
                        } catch (\Throwable $e) {
                            Log::error('Failed to send accept email: ' . $e->getMessage());
                        }

                        \Filament\Notifications\Notification::make()
                            ->title('Peserta diterima & Email dikirim')
                            ->success()
                            ->send();
                    })
                    ->visible(fn(PendaftaranPelatihan $record) => strtolower((string) $record->status_pendaftaran) === 'verifikasi'),

                Tables\Actions\Action::make('reject')
                    ->label('Tolak')
                    ->icon('heroicon-o-x-mark')
                    ->color('danger')
                    ->requiresConfirmation(fn() => ! session()->get('suppress_pendaftaran_approval'))
                    ->modalIcon('heroicon-o-x-mark')
                    ->modalHeading('Tolak Peserta')
                    ->modalDescription('Apakah Anda yakin ingin menolak peserta ini? Status akan berubah menjadi Ditolak.')
                    ->modalSubmitActionLabel('Ya, Tolak')
                    ->form([
                        Forms\Components\Checkbox::make('dont_show_again')
                            ->label('Jangan tampilkan lagi (Sesi ini)'),
                    ])
                    ->action(function (PendaftaranPelatihan $record, array $data) {
                        if (($data['dont_show_again'] ?? false) === true) {
                            session()->put('suppress_pendaftaran_approval', true);
                        }

                        $record->update(['status_pendaftaran' => 'ditolak']);

                        \Filament\Notifications\Notification::make()
                            ->title('Peserta ditolak')
                            ->success()
                            ->send();
                    })
                    ->visible(fn(PendaftaranPelatihan $record) => strtolower((string) $record->status_pendaftaran) === 'verifikasi'),

                Tables\Actions\EditAction::make()
                    ->label('Edit')
                    ->icon('heroicon-o-pencil-square')
                    ->visible(fn(PendaftaranPelatihan $record) => strtolower((string) $record->status_pendaftaran) !== 'verifikasi'),

                Tables\Actions\DeleteAction::make()
                    ->label('Hapus')
                    ->icon('heroicon-o-trash')
                    ->visible(fn(PendaftaranPelatihan $record) => strtolower((string) $record->status_pendaftaran) !== 'verifikasi'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListPendaftarans::route('/'),
            'create' => Pages\CreatePendaftaran::route('/create'),
            'edit'   => Pages\EditPendaftaran::route('/{record}/edit'),
        ];
    }
}
