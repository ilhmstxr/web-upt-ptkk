<?php

namespace App\Filament\Clusters\Pelatihan\Resources\PelatihanResource\Widgets;

use App\Models\KompetensiPelatihan;
use App\Models\PendaftaranPelatihan;
use Filament\Forms;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class PesertaPelatihanTable extends BaseWidget
{
    public ?Model $record = null; // Pelatihan
    public ?int $kompetensiPelatihanId = null;

    protected int|string|array $columnSpan = 'full';

    /**
     * Query utama table (dibikin aman kalau record belum kebawa).
     */
    protected function getPesertaQuery(): Builder
    {
        if (! $this->record?->getKey()) {
            return PendaftaranPelatihan::query()->whereRaw('1=0');
        }

        return PendaftaranPelatihan::query()
            ->where('pelatihan_id', $this->record->getKey())
            ->when(
                $this->kompetensiPelatihanId,
                fn (Builder $q) => $q->where('kompetensi_pelatihan_id', $this->kompetensiPelatihanId)
            )
            ->with([
                'peserta.user',
                'peserta.instansi.cabangDinas',
                'kompetensiPelatihan.kompetensi',
                'pelatihan',
                'penempatanAsramaAktif.kamarPelatihan.kamar',
            ]);
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

    public function table(Table $table): Table
    {
        return $table
            ->query($this->getPesertaQuery())
            ->columns([
                Tables\Columns\TextColumn::make('nomor_registrasi')
                    ->label('No. Registrasi')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('peserta.nama')
                    ->label('Info Peserta')
                    ->description(fn (PendaftaranPelatihan $record): string =>
                        ($record->peserta?->user?->email ?? '-') . ' | ' . ($record->peserta?->no_hp ?? '-')
                    )
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('kompetensiPelatihan.kompetensi.nama_kompetensi')
                    ->label('Kompetensi')
                    ->sortable(),

                Tables\Columns\TextColumn::make('tanggal_pendaftaran')
                    ->label('Tanggal Daftar')
                    ->dateTime()
                    ->sortable(),

                Tables\Columns\TextColumn::make('status_pendaftaran')
                    ->label('Status')
                    ->badge()
                    ->color(fn (?string $state): string => match (strtolower($state ?? '')) {
                        'pending'    => 'warning',
                        'verifikasi' => 'info',
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
                        'pending'    => 'Pending',
                        'verifikasi' => 'Verifikasi',
                        'diterima'   => 'Diterima',
                        'ditolak'    => 'Ditolak',
                        'cadangan'   => 'Cadangan',
                    ]),

                Tables\Filters\SelectFilter::make('kompetensi_pelatihan_id')
                    ->label('Kompetensi')
                    ->options(fn () => $this->getKompetensiOptions())
                    ->query(function (Builder $query, array $data) {
                        $value = $data['value'] ?? null;

                        return $query->when(
                            $value,
                            fn (Builder $q) => $q->where('kompetensi_pelatihan_id', $value)
                        );
                    })
                    ->visible(fn () => is_null($this->kompetensiPelatihanId)),
            ])
            ->headerActions([])
            ->actions([
                Tables\Actions\ViewAction::make(),

                Tables\Actions\Action::make('accept')
                    ->label('Terima')
                    ->icon('heroicon-o-check')
                    ->color('success')
                    ->requiresConfirmation(fn () => ! session()->get('suppress_peserta_approval_confirmation'))
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
                            session()->put('suppress_peserta_approval_confirmation', true);
                        }

                        $record->update(['status_pendaftaran' => 'diterima']);

                        // Load relations needed for email template
                        $record->load([
                            'peserta.instansi.cabangDinas',
                            'peserta.user',
                            'kompetensiPelatihan.kompetensi',
                            'pelatihan',
                            'penempatanAsramaAktif.kamarPelatihan.kamar',
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

                        \Filament\Notifications\Notification::make()
                            ->title('Peserta diterima & Email dikirim')
                            ->success()
                            ->send();
                    })
                    ->visible(fn (PendaftaranPelatihan $record) => strtolower((string) $record->status_pendaftaran) === 'pending'),

                Tables\Actions\Action::make('reject')
                    ->label('Tolak')
                    ->icon('heroicon-o-x-mark')
                    ->color('danger')
                    ->requiresConfirmation(fn () => ! session()->get('suppress_peserta_approval_confirmation'))
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
                            session()->put('suppress_peserta_approval_confirmation', true);
                        }

                        $record->update(['status_pendaftaran' => 'ditolak']);

                        \Filament\Notifications\Notification::make()
                            ->title('Peserta ditolak')
                            ->success()
                            ->send();
                    })
                    ->visible(fn (PendaftaranPelatihan $record) => strtolower((string) $record->status_pendaftaran) === 'pending'),

                Tables\Actions\EditAction::make()
                    ->slideOver()
                    ->form([
                        Forms\Components\Select::make('status_pendaftaran')
                            ->label('Status Pendaftaran')
                            ->options([
                                'pending'    => 'Pending',
                                'verifikasi' => 'Verifikasi',
                                'diterima'   => 'Diterima',
                                'ditolak'    => 'Ditolak',
                                'cadangan'   => 'Cadangan',
                            ])
                            ->required()
                            ->native(false),

                        Forms\Components\Select::make('kompetensi_pelatihan_id')
                            ->label('Kompetensi')
                            ->options(fn () => $this->getKompetensiOptions())
                            ->searchable()
                            ->preload()
                            ->required(),
                    ])
                    ->visible(fn (PendaftaranPelatihan $record) => strtolower((string) $record->status_pendaftaran) !== 'pending'),

                Tables\Actions\DeleteAction::make()
                    ->visible(fn (PendaftaranPelatihan $record) => strtolower((string) $record->status_pendaftaran) !== 'pending'),
            ]);
    }
}
