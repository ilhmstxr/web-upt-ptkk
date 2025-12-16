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
    /**
     * Record Pelatihan (DISET VIA mount / data)
     */
    public ?Model $record = null;

    /**
     * Optional: filter global kompetensi
     */
    public ?int $kompetensiPelatihanId = null;

    protected int|string|array $columnSpan = 'full';

    /**
     * ✅ FIX UTAMA:
     * mount dibuat fleksibel agar:
     * - aman saat widget render duluan (record belum ada)
     * - support 2 cara pemanggilan:
     *   1) @livewire(Widget::class, ['record' => $record, 'kompetensiPelatihanId' => ...])
     *   2) <x-filament-widgets::widget :widget="..." :data="['record'=>..., 'kompetensiPelatihanId'=>...]" />
     */
    public function mount(?Model $record = null, ?int $kompetensiPelatihanId = null, array $data = []): void
    {
        // Prioritas: argumen langsung, fallback: data array (untuk <x-filament-widgets::widget :data="...">)
        $this->record = $record ?? ($data['record'] ?? null);
        $this->kompetensiPelatihanId = $kompetensiPelatihanId ?? ($data['kompetensiPelatihanId'] ?? null);
    }

    /**
     * Query utama (SELALU return Builder)
     */
    protected function getPesertaQuery(): Builder
    {
        // ✅ Aman kalau record null / belum ada key
        if (! $this->record?->getKey()) {
            return PendaftaranPelatihan::query()->whereRaw('1 = 0');
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

    /**
     * ✅ Required oleh sebagian flow internal Filament TableWidget
     * (tetap dipertahankan biar fitur lama aman)
     */
    protected function getTableQuery(): Builder
    {
        return $this->getPesertaQuery();
    }

    /**
     * Dropdown filter kompetensi
     */
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
            /**
             * ✅ FIX: Lazy query closure supaya query dipanggil SETELAH mount
             * (ini yang paling penting untuk hindari "getQuery() on null")
             */
            ->query(fn () => $this->getPesertaQuery())

            ->columns([
                Tables\Columns\TextColumn::make('nomor_registrasi')
                    ->label('No. Registrasi')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('peserta.nama')
                    ->label('Info Peserta')
                    ->description(fn (PendaftaranPelatihan $record) =>
                        ($record->peserta?->user?->email ?? '-') . ' | ' . ($record->peserta?->no_hp ?? '-')
                    )
                    ->searchable()
                    ->sortable(),

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
                    ->color(fn (?string $state) => match (strtolower($state ?? '')) {
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
                    ->query(fn (Builder $query, array $data) =>
                        $query->when(
                            $data['value'] ?? null,
                            fn (Builder $q, $value) => $q->where('kompetensi_pelatihan_id', $value)
                        )
                    )
                    // ✅ fitur lama: hanya tampil kalau bukan sedang dibatasi 1 kompetensi
                    ->visible(fn () => is_null($this->kompetensiPelatihanId)),
            ])

            // fitur lama kamu: headerActions kosong (tetap dipertahankan)
            ->headerActions([])

            ->actions([
                /**
                 * ✅ FITUR LAMA:
                 * Kalau ViewAction kamu sebelumnya jalan dan memang ada context resource,
                 * biarkan. Kalau sering error, kamu bisa ganti ke Action custom url.
                 */
                Tables\Actions\ViewAction::make(),

                Tables\Actions\Action::make('accept')
                    ->label('Terima')
                    ->icon('heroicon-o-check')
                    ->color('success')
                    ->requiresConfirmation(fn () => ! session()->get('suppress_peserta_approval_confirmation'))
                    ->form([
                        Forms\Components\Checkbox::make('dont_show_again')
                            ->label('Jangan tampilkan lagi (Sesi ini)'),
                    ])
                    ->action(function (PendaftaranPelatihan $record, array $data) {
                        if (($data['dont_show_again'] ?? false) === true) {
                            session()->put('suppress_peserta_approval_confirmation', true);
                        }

                        $record->update(['status_pendaftaran' => 'diterima']);

                        $record->load([
                            'peserta.user',
                            'peserta.instansi.cabangDinas',
                            'kompetensiPelatihan.kompetensi',
                            'pelatihan',
                            'penempatanAsramaAktif.kamarPelatihan.kamar',
                        ]);

                        $email = $record->peserta?->user?->email;
                        if ($email) {
                            \App\Services\EmailService::send(
                                $email,
                                'Informasi Pendaftaran dan Undangan Pelatihan',
                                '',
                                [],
                                'template_surat.informasi_kegiatan'
                            );
                        }

                        \Filament\Notifications\Notification::make()
                            ->title('Peserta diterima')
                            ->success()
                            ->send();
                    })
                    ->visible(fn (PendaftaranPelatihan $record) => strtolower((string) $record->status_pendaftaran) === 'pending'),

                Tables\Actions\Action::make('reject')
                    ->label('Tolak')
                    ->icon('heroicon-o-x-mark')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->action(fn (PendaftaranPelatihan $record) => $record->update(['status_pendaftaran' => 'ditolak']))
                    ->visible(fn (PendaftaranPelatihan $record) => strtolower((string) $record->status_pendaftaran) === 'pending'),

                Tables\Actions\EditAction::make()
                    ->slideOver(),

                Tables\Actions\DeleteAction::make()
                    ->visible(fn (PendaftaranPelatihan $record) => strtolower((string) $record->status_pendaftaran) !== 'pending'),
            ]);
    }
}
