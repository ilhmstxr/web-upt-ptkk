<?php

namespace App\Filament\Clusters\Fasilitas\Resources\PenempatanAsramaResource\Pages;

use App\Filament\Clusters\Fasilitas\Resources\PenempatanAsramaResource;
use App\Models\PendaftaranPelatihan;
use App\Services\AsramaAllocator;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ViewPenempatanAsrama extends ViewRecord
{
    protected static string $resource = PenempatanAsramaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('finalize_penempatan')
                ->label('Finalize Penempatan Asrama')
                ->icon('heroicon-o-bolt')
                ->color('success')
                ->requiresConfirmation()
                ->action(function (AsramaAllocator $allocator) {

                    $pelatihanId = $this->record->id;

                    $kamarConfig = session('kamars') ?? config('kamars');
                    $allocator->generateRoomsFromConfig($pelatihanId, $kamarConfig);

                    $result = $allocator->allocatePesertaPerPelatihan($pelatihanId);

                    $this->dispatch('$refresh');

                    Notification::make()
                        ->title('Penempatan selesai')
                        ->body("OK={$result['ok']} | skipped={$result['skipped_already_assigned']} | gagal={$result['failed_full']}")
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

    protected function exportCsv(): StreamedResponse
    {
        $pelatihan   = $this->record;
        $pelatihanId = $pelatihan->id;

        $clean = function ($v) {
            $v = (string) ($v ?? '');
            $v = trim($v);
            // hapus enter, tab, dan spasi dobel
            $v = preg_replace("/\r|\n|\t/", " ", $v);
            $v = preg_replace("/\s{2,}/", " ", $v);
            return $v === '' ? '-' : $v;
        };

        $rows = PendaftaranPelatihan::query()
            ->where('pelatihan_id', $pelatihanId)
            ->with([
                'peserta.instansi',
                'penempatanAsrama.kamars.asramas',
            ])
            ->orderBy('id')
            ->get()
            ->values()
            ->map(function ($pend, $i) use ($clean) {

                $peserta    = $pend->peserta;
                $penempatan = $pend->penempatanAsramaAktif();

                $asramaName = $penempatan?->kamars?->asrama?->name ?? null;
                $kamarNo    = $penempatan?->kamars?->nomor_kamar ?? null;
                $lantai     = $penempatan?->kamars?->lantai ?? null;

                return [
                    'no'           => $i + 1,
                    'kode_regis'   => $clean($pend->nomor_registrasi),
                    'nama'         => $clean($peserta?->nama),
                    'nik'          => $clean($peserta?->nik),
                    'jenis_kelamin'=> $clean($peserta?->jenis_kelamin),
                    'instansi'     => $clean($peserta?->instansi?->asal_instansi),
                    'asramas'       => $clean($asramaName),
                    'lantai'       => $clean($lantai),
                    'kamars'        => $clean($kamarNo),
                ];
            });

        $baseName = $pelatihan->slug ?? str($pelatihan->nama_pelatihan ?? 'pelatihan')
            ->slug()
            ->toString();

        $filename = 'penempatan-asrama-' . $baseName . '-' . now()->format('Ymd-His') . '.csv';

        return response()->streamDownload(function () use ($rows) {

            $handle = fopen('php://output', 'w');

            // UTF-8 BOM biar Excel aman
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

            // header pakai delimiter ;
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
                    $r['kamars'],
                ], ';');
            }

            fclose($handle);

        }, $filename, [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ]);
    }

}
