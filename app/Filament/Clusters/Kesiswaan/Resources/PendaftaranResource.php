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
                                ->required(),

                            Forms\Components\DatePicker::make('tanggal_lahir')
                                ->required(),

                            Forms\Components\Select::make('jenis_kelamin')
                                ->options([
                                    'Laki-laki' => 'Laki-laki',
                                    'Perempuan' => 'Perempuan',
                                ])
                                ->required(),

                            Forms\Components\TextInput::make('agama')
                                ->required(),

                            Forms\Components\Textarea::make('alamat')
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
                            Forms\Components\Placeholder::make('peserta_name')
                                ->label('Nama Peserta')
                                ->content(fn($record) => $record?->peserta?->nama ?? '-'),

                            Forms\Components\TextInput::make('nomor_registrasi')
                                ->label('Nomor Registrasi')
                                ->disabled()
                                ->dehydrated(false),

                            Forms\Components\Placeholder::make('pelatihan_name')
                                ->label('Pelatihan')
                                ->content(fn($record) => $record?->pelatihan?->nama_pelatihan ?? '-'),

                            Forms\Components\Placeholder::make('kompetensi_name')
                                ->label('Kompetensi')
                                ->content(fn($record) => $record?->kompetensiPelatihan?->kompetensi?->nama_kompetensi ?? '-'),

                            Forms\Components\TextInput::make('kelas')
                                ->label('Kelas')
                                ->disabled()
                                ->dehydrated(false),

                            Forms\Components\TextInput::make('tanggal_pendaftaran')
                                ->label('Tanggal Pendaftaran')
                                ->disabled()
                                ->dehydrated(false),
                        ])
                        ->columns(2),

                    Forms\Components\Section::make('Verifikasi Berkas')
                        ->schema([
                            Forms\Components\Placeholder::make('lampiran_info')
                                ->label('')
                                ->content(function ($record) {
                                    $lampiran = $record?->peserta?->lampiran;

                                    if (! $lampiran) {
                                        return 'Belum ada berkas lampiran.';
                                    }

                                    $ktp   = $lampiran->fc_ktp_url ?? null;
                                    $ijaz  = $lampiran->fc_ijazah_url ?? null;
                                    $sehat = $lampiran->fc_surat_sehat_url ?? null;
                                    $foto  = $lampiran->pas_foto_url ?? null;

                                    $link = fn($url) => $url
                                        ? '<a href="' . $url . '" target="_blank" class="text-primary-600 hover:underline">Lihat File</a>'
                                        : '<span class="text-gray-500">Tidak ada</span>';

                                    return new HtmlString('
                                        <div class="grid grid-cols-2 gap-4">
                                            <div><strong>KTP:</strong> ' . $link($ktp) . '</div>
                                            <div><strong>Ijazah:</strong> ' . $link($ijaz) . '</div>
                                            <div><strong>Surat Sehat:</strong> ' . $link($sehat) . '</div>
                                            <div><strong>Pas Foto:</strong> ' . $link($foto) . '</div>
                                        </div>
                                    ');
                                })
                                ->columnSpanFull(),
                        ]),
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
                                    'pending'    => 'Pending',
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
                        'pending'    => 'Pending',
                        'verifikasi' => 'Verifikasi',
                        'diterima'   => 'Diterima',
                        'ditolak'    => 'Ditolak',
                        default      => (string) $state,
                    })
                    ->color(fn(?string $state): string => match (strtolower((string) $state)) {
                        'pending'    => 'warning',
                        'verifikasi' => 'info',
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
                        'pending'    => 'Pending',
                        'verifikasi' => 'Verifikasi',
                        'diterima'   => 'Diterima',
                        'ditolak'    => 'Ditolak',
                    ]),

                Tables\Filters\SelectFilter::make('pelatihan_id')
                    ->label('Filter Pelatihan')
                    ->relationship('pelatihan', 'nama_pelatihan'),

                Tables\Filters\SelectFilter::make('kompetensi_id')
                    ->label('Kompetensi')
                    ->options(fn() => Kompetensi::query()->pluck('nama_kompetensi', 'id')->all())
                    ->query(function (Builder $query, array $data) {
                        $kompetensiId = $data['value'] ?? null;
                        if (! $kompetensiId) return $query;

                        // ✅ Lebih stabil karena kolom kompetensi_id ada di pendaftaran_pelatihan
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
                    ])
                    ->action(function (PendaftaranPelatihan $record, array $data) {
                        if (($data['dont_show_again'] ?? false) === true) {
                            session()->put('suppress_pendaftaran_approval', true);
                        }

                        // ✅ enum lowercase
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
                                'alamat_lengkap' => $record->pelatihan?->lokasi_text ?? 'Jl. Menur No. 123, Surabaya',
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
                    ->visible(fn(PendaftaranPelatihan $record) => strtolower((string) $record->status_pendaftaran) === 'pending'),

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

                        // ✅ enum lowercase
                        $record->update(['status_pendaftaran' => 'ditolak']);

                        \Filament\Notifications\Notification::make()
                            ->title('Peserta ditolak')
                            ->success()
                            ->send();
                    })
                    ->visible(fn(PendaftaranPelatihan $record) => strtolower((string) $record->status_pendaftaran) === 'pending'),

                Tables\Actions\EditAction::make()
                    ->label('Edit')
                    ->icon('heroicon-o-pencil-square')
                    ->visible(fn(PendaftaranPelatihan $record) => strtolower((string) $record->status_pendaftaran) !== 'pending'),

                Tables\Actions\DeleteAction::make()
                    ->label('Hapus')
                    ->icon('heroicon-o-trash')
                    ->visible(fn(PendaftaranPelatihan $record) => strtolower((string) $record->status_pendaftaran) !== 'pending'),
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
