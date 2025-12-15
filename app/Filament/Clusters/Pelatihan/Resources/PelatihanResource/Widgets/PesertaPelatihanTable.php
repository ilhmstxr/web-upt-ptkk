<?php

namespace App\Filament\Clusters\Pelatihan\Resources\PelatihanResource\Widgets;

use App\Models\PendaftaranPelatihan;
use Filament\Forms;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Model;

class PesertaPelatihanTable extends BaseWidget
{
    public ?Model $record = null;
    public ?int $kompetensiPelatihanId = null;

    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                PendaftaranPelatihan::query()
                    ->with(['peserta.instansi', 'kompetensi'])
                    ->where('pelatihan_id', $this->record->id)
                    ->when($this->kompetensiPelatihanId, fn($q) => $q->where('kompetensi_pelatihan_id', $this->kompetensiPelatihanId))
            )
            ->columns([
                Tables\Columns\TextColumn::make('nomor_registrasi')
                    ->label('No. Registrasi')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('peserta.nama')
                    ->label('Info Peserta')
                    ->description(fn(PendaftaranPelatihan $record): string => $record->peserta?->user?->email . ' | ' . $record->peserta?->no_hp)
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
                    ->color(fn(string $state): string => match (strtolower($state)) {
                        'pending' => 'warning',
                        'diterima' => 'success',
                        'ditolak' => 'danger',
                        default => 'gray',
                    }),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'Diterima' => 'Diterima',
                        'Ditolak' => 'Ditolak',
                        'Cadangan' => 'Cadangan',
                    ]),
                Tables\Filters\SelectFilter::make('kompetensi_pelatihan_id')
                    ->label('Kompetensi')
                    ->options(function () {
                        return \App\Models\KompetensiPelatihan::with('kompetensi')
                            ->where('pelatihan_id', $this->record->id)
                            ->get()
                            ->pluck('kompetensi.nama_kompetensi', 'id');
                    })
                    ->query(function (\Illuminate\Database\Eloquent\Builder $query, array $data) {
                        return $query->when($data['value'], fn($q, $v) => $q->where('kompetensi_pelatihan_id', $v));
                    })
                    ->visible(fn() => is_null($this->kompetensiPelatihanId)),
            ])
            ->headerActions([])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\Action::make('accept')
                    ->label('Terima')
                    ->icon('heroicon-o-check')
                    ->color('success')
                    ->requiresConfirmation(fn() => !session()->get('suppress_peserta_approval_confirmation'))
                    ->modalIcon('heroicon-o-check')
                    ->modalHeading('Terima Peserta')
                    ->modalDescription('Apakah Anda yakin ingin menerima peserta ini? Status akan berubah menjadi Diterima.')
                    ->modalSubmitActionLabel('Ya, Terima')
                    ->form([
                        Forms\Components\Checkbox::make('dont_show_again')
                            ->label('Jangan tampilkan lagi (Sesi ini)'),
                    ])
                    ->action(function (PendaftaranPelatihan $record, array $data) {
                        if ($data['dont_show_again'] ?? false) {
                            session()->put('suppress_peserta_approval_confirmation', true);
                        }

                        $record->update(['status_pendaftaran' => 'Diterima']);

                        // Load necessary relations
                        $record->load([
                            'peserta.instansi.cabangDinas',
                            'peserta.user',
                            'kompetensiPelatihan.kompetensi',
                            'pelatihan',
                            'penempatanAsramaAktif.kamarPelatihan.kamar'
                        ]);

                        // Prepare data for email
                        $emailData = [
                            'id_peserta' => $record->nomor_registrasi,
                            'nama_peserta' => $record->peserta->nama,
                            'asal_lembaga' => $record->peserta->instansi->asal_instansi ?? '-',
                            'cabang_dinas' => $record->peserta->instansi->cabangDinas->nama ?? '-',
                            'kompetensi' => $record->kompetensiPelatihan->kompetensi->nama_kompetensi ?? '-',
                            'kamar_asrama' => ($record->penempatanAsramaAktif?->kamarPelatihan?->kamar?->nomor_kamar
                                ? 'Kamar ' . $record->penempatanAsramaAktif->kamarPelatihan->kamar->nomor_kamar
                                : 'Belum Ditentukan'),
                            'waktu_mulai' => $record->pelatihan->tanggal_mulai ? $record->pelatihan->tanggal_mulai->translatedFormat('d F Y') : '-',
                            'waktu_selesai' => $record->pelatihan->tanggal_selesai ? $record->pelatihan->tanggal_selesai->translatedFormat('d F Y') : '-',
                            'lokasi' => 'UPT PTKK Surabaya',
                            'alamat_lengkap' => $record->pelatihan->lokasi_text ?? 'Jl. Menur No. 123, Surabaya',
                        ];

                        // Send Email
                        $email = $record->peserta->user->email ?? null;
                        if ($email) {
                            \App\Services\EmailService::send(
                                $email,
                                'Informasi Pendaftaran dan Undangan Pelatihan',
                                '', // Content ignored by specific view
                                $emailData,
                                'template_surat.informasi_kegiatan'
                            );
                        }

                        \Filament\Notifications\Notification::make()
                            ->title('Peserta diterima & Email dikirim')
                            ->success()
                            ->send();
                    })
                    ->visible(fn(PendaftaranPelatihan $record) => strtolower($record->status_pendaftaran) === 'pending'),
                Tables\Actions\Action::make('reject')
                    ->label('Tolak')
                    ->icon('heroicon-o-x-mark')
                    ->color('danger')
                    ->requiresConfirmation(fn() => !session()->get('suppress_peserta_approval_confirmation'))
                    ->modalIcon('heroicon-o-x-mark')
                    ->modalHeading('Tolak Peserta')
                    ->modalDescription('Apakah Anda yakin ingin menolak peserta ini? Status akan berubah menjadi Ditolak.')
                    ->modalSubmitActionLabel('Ya, Tolak')
                    ->form([
                        Forms\Components\Checkbox::make('dont_show_again')
                            ->label('Jangan tampilkan lagi (Sesi ini)'),
                    ])
                    ->action(function (PendaftaranPelatihan $record, array $data) {
                        if ($data['dont_show_again'] ?? false) {
                            session()->put('suppress_peserta_approval_confirmation', true);
                        }
                        $record->update(['status_pendaftaran' => 'Ditolak']);

                        \Filament\Notifications\Notification::make()
                            ->title('Peserta ditolak')
                            ->success()
                            ->send();
                    })
                    ->visible(fn(PendaftaranPelatihan $record) => strtolower($record->status_pendaftaran) === 'pending'),

                Tables\Actions\EditAction::make()
                    ->slideOver()
                    ->form([
                        Forms\Components\Select::make('status_pendaftaran')
                            ->label('Status Pendaftaran')
                            ->options([
                                'Pending' => 'Pending',
                                'Diterima' => 'Diterima',
                                'Ditolak' => 'Ditolak',
                                'Cadangan' => 'Cadangan',
                            ])
                            ->required()
                            ->native(false),
                        Forms\Components\Select::make('kompetensi_pelatihan_id')
                            ->label('Kompetensi')
                            ->options(function () {
                                return \App\Models\KompetensiPelatihan::with('kompetensi')
                                    ->where('pelatihan_id', $this->record->id)
                                    ->get()
                                    ->mapWithKeys(fn($item) => [$item->id => $item->kompetensi->nama_kompetensi ?? 'Unknown']);
                            })
                            ->searchable()
                            ->preload()
                            ->required(),
                    ])
                    ->visible(fn(PendaftaranPelatihan $record) => strtolower($record->status_pendaftaran) !== 'pending'),
                Tables\Actions\DeleteAction::make()
                    ->visible(fn(PendaftaranPelatihan $record) => strtolower($record->status_pendaftaran) !== 'pending'),
            ]);
    }
}
