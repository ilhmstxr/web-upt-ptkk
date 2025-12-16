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

class PesertaPelatihanTable extends BaseWidget
{
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
                Tables\Actions\ViewAction::make(),

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
                            ->default(true),
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
                                    'nama_peserta'   => $record->peserta->nama,
                                    'asal_lembaga'   => $record->peserta->instansi->asal_instansi ?? '-',
                                    'cabang_dinas'   => $record->peserta->instansi->cabangDinas->nama_cabang ?? '-',
                                    'kompetensi'     => $record->kompetensiPelatihan->kompetensi->nama_kompetensi ?? '-',
                                    // Menggunakan helper penempatanAsramaAktif() yang ada di model PendaftaranPelatihan
                                    'kamar_asrama'   => $record->penempatanAsramaAktif()?->kamarPelatihan->kamar->nama_kamar ?? 'Belum ditentukan',
                                    'waktu_mulai'    => \Carbon\Carbon::parse($record->pelatihan->tanggal_mulai)->translatedFormat('d F Y'),
                                    'waktu_selesai'  => \Carbon\Carbon::parse($record->pelatihan->tanggal_selesai)->translatedFormat('d F Y'),
                                    'lokasi'         => $record->pelatihan->lokasi ?? 'UPT PTKK Surabaya',
                                    'alamat'         => 'Jl. Menur No. 123, Surabaya',
                                    'cp_nama'        => 'Sdri. Admin',
                                    'cp_phone'       => '082249999447',
                                    'email_penerima' => $emailPeserta,
                                ];

                                \Illuminate\Support\Facades\Mail::to($emailPeserta)->send(new \App\Mail\EmailKonfirmasi($emailData));

                                Notification::make()
                                    ->title('Peserta diterima & Email dikirim')
                                    ->success()
                                    ->send();
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
                        strtolower((string) $record->status_pendaftaran) === 'pending'
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
                        strtolower((string) $record->status_pendaftaran) === 'pending'
                    ),

                Tables\Actions\EditAction::make()->slideOver(),

                Tables\Actions\DeleteAction::make()
                    ->visible(
                        fn(PendaftaranPelatihan $record) =>
                        strtolower((string) $record->status_pendaftaran) !== 'pending'
                    ),
            ]);
    }
}
