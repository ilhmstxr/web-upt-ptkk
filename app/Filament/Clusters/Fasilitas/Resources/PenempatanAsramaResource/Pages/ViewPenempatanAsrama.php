<?php

namespace App\Filament\Clusters\Fasilitas\Resources\PenempatanAsramaResource\Pages;

use App\Filament\Clusters\Fasilitas\Resources\PenempatanAsramaResource;
use App\Models\KamarPelatihan;
use App\Models\PenempatanAsrama;
use App\Models\PendaftaranPelatihan;
use App\Services\AsramaAllocator;
use App\Services\AsramaConfigSyncService;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ViewPenempatanAsrama extends ViewRecord
{
    protected static string $resource = PenempatanAsramaResource::class;

    protected static ?string $title = 'Penempatan Asrama';

    protected static string $view = 'filament.clusters.fasilitas.resources.penempatan-asrama-resource.pages.view-penempatan-asrama';

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

    protected function getViewData(): array
    {
        $pelatihanId = $this->record->id;

        $placements = PenempatanAsrama::query()
            ->where('pelatihan_id', $pelatihanId)
            ->with('kamarPelatihan.kamar.asrama')
            ->get();

        $placementsByPeserta = $placements->keyBy('peserta_id');
        $placementsByKp = $placements->groupBy('kamar_pelatihan_id');

        $kamarPelatihans = KamarPelatihan::query()
            ->where('pelatihan_id', $pelatihanId)
            ->with('kamar.asrama')
            ->get();

        $kamarMap = [];
        foreach ($kamarPelatihans as $kp) {
            $asramaName = $kp->kamar?->asrama?->name;
            $roomNo = $kp->kamar?->nomor_kamar;
            if (!$asramaName || !$roomNo) {
                continue;
            }
            $kamarMap[$asramaName][$roomNo] = $kp;
        }

        $asramaLayouts = $this->buildAsramaLayouts(
            config('kamar', []),
            $kamarMap,
            $placementsByKp
        );

        return [
            'asramaLayouts' => $asramaLayouts,
        ];
    }

    private function buildAsramaLayouts(array $config, array $kamarMap, Collection $placementsByKp): array
    {
        $layouts = [];

        foreach ($config as $asramaName => $rooms) {
            if (!is_array($rooms)) {
                continue;
            }

            $roomLayouts = [];
            $totalBeds = 0;
            $occupiedBeds = 0;
            $activeRooms = 0;

            foreach ($rooms as $room) {
                $roomNo = (int) ($room['no'] ?? 0);
                if ($roomNo <= 0) {
                    continue;
                }

                $bedRaw = $room['bed'] ?? null;
                $isNumericBed = is_numeric($bedRaw);
                $roomTotalBeds = $isNumericBed ? (int) $bedRaw : 0;

                $status = 'Tidak tersedia';
                if ($isNumericBed) {
                    $status = $roomTotalBeds > 0 ? 'Tersedia' : 'Perbaikan';
                } elseif (is_string($bedRaw) && strtolower($bedRaw) === 'rusak') {
                    $status = 'Rusak';
                }

                $kp = $kamarMap[$asramaName][$roomNo] ?? null;
                $availableBeds = $kp?->available_beds ?? $roomTotalBeds;
                $roomOccupiedBeds = max(0, $roomTotalBeds - $availableBeds);

                $genderList = [];
                if ($kp && $placementsByKp->has($kp->id)) {
                    $genderList = $placementsByKp[$kp->id]->pluck('gender')->values()->all();
                }

                $beds = [];
                for ($i = 0; $i < $roomTotalBeds; $i++) {
                    if ($i < count($genderList)) {
                        $beds[] = ['state' => 'occupied', 'gender' => $genderList[$i]];
                    } elseif ($i < $roomOccupiedBeds) {
                        $beds[] = ['state' => 'occupied', 'gender' => null];
                    } else {
                        $beds[] = ['state' => 'available', 'gender' => null];
                    }
                }

                $isActive = $roomTotalBeds > 0;
                if ($isActive) {
                    $activeRooms++;
                    $totalBeds += $roomTotalBeds;
                    $occupiedBeds += $roomOccupiedBeds;
                }

                $roomLayouts[] = [
                    'no' => $roomNo,
                    'total_beds' => $roomTotalBeds,
                    'available_beds' => $availableBeds,
                    'occupied_beds' => $roomOccupiedBeds,
                    'status' => $status,
                    'is_active' => $isActive,
                    'beds' => $beds,
                ];
            }

            $layouts[] = [
                'name' => $asramaName,
                'rooms' => $roomLayouts,
                'total_beds' => $totalBeds,
                'occupied_beds' => $occupiedBeds,
                'active_rooms' => $activeRooms,
            ];
        }

        return $layouts;
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
