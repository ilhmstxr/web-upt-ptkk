<?php

namespace App\Filament\Clusters\Fasilitas\Resources\PenempatanAsramaResource\Pages;

use App\Filament\Clusters\Fasilitas\Resources\PenempatanAsramaResource;
use App\Models\PendaftaranPelatihan;
use App\Services\AsramaAllocator;
use App\Services\AsramaConfigSyncService;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ViewPenempatanAsrama extends ViewRecord
{
    protected static string $resource = PenempatanAsramaResource::class;

    protected static ?string $title = 'Penempatan Asrama';

    /**
     * =========================
     * HEADER ACTIONS
     * =========================
     */
    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('finalize_penempatan')
                ->label('Finalize Penempatan Asrama')
                ->icon('heroicon-o-bolt')
                ->color('success')
                ->requiresConfirmation()
                ->action(function (AsramaAllocator $allocator, AsramaConfigSyncService $sync) {

                    $pelatihanId = $this->record->id;

                    // pastikan data kamar/asrama sudah tersinkron dari config
                    $sync->syncFromConfig();

                    // sinkron kamar global -> kamar_pelatihan
                    $allocator->attachKamarToPelatihan($pelatihanId);

                    // auto-allocate peserta
                    $result = $allocator->allocatePeserta($pelatihanId);

                    // refresh Livewire
                    $this->dispatch('$refresh');

                    Notification::make()
                        ->title('Penempatan selesai')
                        ->body(
                            "OK={$result['ok']} | " .
                            "Skipped={$result['skipped_already_assigned']} | " .
                            "Gagal={$result['failed_full']}"
                        )
                        ->success()
                        ->send();
                }),

            Actions\Action::make('export_csv')
                ->label('Export CSV')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('info')
                ->action(fn () => $this->exportCsv()),
        ];
    }

    /**
     * =========================
     * EXPORT CSV (FULL FEATURE)
     * =========================
     */
    protected function exportCsv(): StreamedResponse
    {
        $pelatihan   = $this->record;
        $pelatihanId = $pelatihan->id;

        // helper sanitasi cell
        $clean = function ($v) {
            $v = (string) ($v ?? '');
            $v = trim($v);
            $v = preg_replace("/\r|\n|\t/", ' ', $v);
            $v = preg_replace("/\s{2,}/", ' ', $v);
            return $v === '' ? '-' : $v;
        };

        $inferLantai = function (?string $asramaName): ?int {
            $name = strtolower((string) $asramaName);
            if (str_contains($name, 'atas')) return 2;
            if (str_contains($name, 'bawah')) return 1;
            return null;
        };

        $rows = PendaftaranPelatihan::query()
            ->where('pelatihan_id', $pelatihanId)
            ->with([
                'peserta.instansi',
                // ⬇️ INI KUNCI: JANGAN PAKAI penempatanAsrama.kamar
                'penempatanAsrama.kamarPelatihan.kamar.asrama',
            ])
            ->orderBy('id')
            ->get()
            ->values()
            ->map(function ($pend, $i) use ($clean, $inferLantai) {

                $peserta    = $pend->peserta;
                $penempatan = $pend->penempatanAsramaAktif();

                $kamar      = $penempatan?->kamarPelatihan?->kamar;
                $asrama     = $kamar?->asrama;
                $lantai     = $kamar?->lantai ?? $inferLantai($asrama?->name);

                return [
                    'no'            => $i + 1,
                    'kode_regis'    => $clean($pend->nomor_registrasi),
                    'nama'          => $clean($peserta?->nama),
                    'nik'           => $clean($peserta?->nik),
                    'jenis_kelamin' => $clean($peserta?->jenis_kelamin),
                    'instansi'      => $clean($peserta?->instansi?->asal_instansi),
                    'asrama'        => $clean($asrama?->name),
                    'lantai'        => $clean($lantai),
                    'kamar'         => $clean($kamar?->nomor_kamar),
                ];
            });

        $baseName = $pelatihan->slug
            ?? str($pelatihan->nama_pelatihan ?? 'pelatihan')->slug()->toString();

        $filename = 'penempatan-asrama-' . $baseName . '-' . now()->format('Ymd-His') . '.csv';

        return response()->streamDownload(function () use ($rows) {

            $handle = fopen('php://output', 'w');

            // UTF-8 BOM (Excel safe)
            fprintf($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));

            // header
            fputcsv($handle, [
                'No',
                'Kode Registrasi',
                'Nama Peserta',
                'NIK',
                'Jenis Kelamin',
                'Instansi',
                'Asrama',
                'Lantai',
                'Kamar',
            ], ';');

            foreach ($rows as $r) {
                fputcsv($handle, [
                    $r['no'],
                    $r['kode_regis'],
                    $r['nama'],
                    $r['nik'],
                    $r['jenis_kelamin'],
                    $r['instansi'],
                    $r['asrama'],
                    $r['lantai'],
                    $r['kamar'],
                ], ';');
            }

            fclose($handle);

        }, $filename, [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }
}
