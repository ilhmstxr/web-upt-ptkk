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
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ViewPenempatanAsrama extends ViewRecord
{
    protected static string $resource = PenempatanAsramaResource::class;

    protected static ?string $title = 'Penempatan Asrama';

    protected static string $view = 'filament.clusters.fasilitas.resources.penempatan-asrama-resource.pages.view-penempatan-asrama';

    protected $listeners = [
        'select-peserta' => 'selectPeserta',
        'select-bed' => 'selectBed',
        'assign-peserta-to-bed' => 'assignPesertaToBed',
        'move-peserta-bed' => 'movePesertaBed',
        'unassign-peserta-from-bed' => 'unassignPesertaFromBed',
        'unassign-peserta' => 'unassignPeserta',
        'swap-peserta-bed' => 'swapPesertaBed',
        'unassignBed' => 'unassignBed',
    ];

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

                    // reset penempatan agar dibagi ulang dari awal
                    PenempatanAsrama::query()
                        ->where('pelatihan_id', $pelatihanId)
                        ->delete();

                    // sinkron kamar global -> kamar_pelatihan
                    $allocator->attachKamarToPelatihan($pelatihanId, reset: true);

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

        $pesertaRows = PendaftaranPelatihan::query()
            ->where('pelatihan_id', $pelatihanId)
            ->with([
                'peserta.instansi',
                'kompetensi',
                'kompetensiPelatihan.kompetensi',
                'penempatanAsrama.kamarPelatihan.kamar.asrama',
            ])
            ->orderBy('nomor_registrasi')
            ->get()
            ->map(function (PendaftaranPelatihan $record) {
                $kompetensi = $record->kompetensi?->nama_kompetensi
                    ?? $record->kompetensiPelatihan?->kompetensi?->nama_kompetensi;
                $penempatan = $record->penempatanAsramaAktif();
                $kamar = $penempatan?->kamarPelatihan?->kamar;
                $kpId = $penempatan?->kamar_pelatihan_id;
                $bedNumber = '-';
                if ($kpId) {
                    static $bedCache = [];
                    if (!array_key_exists($kpId, $bedCache)) {
                        $bedCache[$kpId] = PenempatanAsrama::query()
                            ->where('pelatihan_id', $record->pelatihan_id)
                            ->where('kamar_pelatihan_id', $kpId)
                            ->orderBy('id')
                            ->pluck('peserta_id')
                            ->values()
                            ->all();
                    }
                    $idx = array_search($record->peserta_id, $bedCache[$kpId], true);
                    $bedNumber = $idx === false ? '-' : $idx + 1;
                }
                $asramaName = $kamar?->asrama?->name;
                $asramaKey = strtolower((string) ($asramaName ?? ''));
                $kamarNo = $kamar?->nomor_kamar;
                $bedId = ($asramaKey !== '' && $kamarNo && $bedNumber !== '-')
                    ? ('bed-' . $asramaKey . '-' . $kamarNo . '-' . $bedNumber)
                    : null;

                return [
                    'peserta_id' => $record->peserta_id,
                    'nomor_registrasi' => $record->nomor_registrasi,
                    'nama' => $record->peserta?->nama,
                    'instansi' => $record->peserta?->instansi?->asal_instansi,
                    'kompetensi' => $kompetensi,
                    'gender' => $record->peserta?->jenis_kelamin,
                    'asrama' => $asramaName,
                    'kamar' => $kamarNo,
                    'bed' => $bedNumber,
                    'bed_id' => $bedId,
                ];
            })
            ->values()
            ->all();

        return [
            'asramaLayouts' => $asramaLayouts,
            'pesertaRows' => $pesertaRows,
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

                $placementList = [];
                if ($kp && $placementsByKp->has($kp->id)) {
                    $placementList = $placementsByKp[$kp->id]
                        ->map(function (PenempatanAsrama $placement) {
                            return [
                                'gender' => $placement->gender,
                                'peserta_id' => $placement->peserta_id,
                            ];
                        })
                        ->values()
                        ->all();
                }

                $beds = [];
                for ($i = 0; $i < $roomTotalBeds; $i++) {
                    if ($i < count($placementList)) {
                        $beds[] = [
                            'state' => 'occupied',
                            'gender' => $placementList[$i]['gender'] ?? null,
                            'peserta_id' => $placementList[$i]['peserta_id'] ?? null,
                        ];
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
            ->map(function ($pend, $i) use ($clean) {

                $peserta    = $pend->peserta;
                $penempatan = $pend->penempatanAsramaAktif();

                $kamar      = $penempatan?->kamarPelatihan?->kamar;
                $asrama     = $kamar?->asrama;

                return [
                    'no'            => $i + 1,
                    'kode_regis'    => $clean($pend->nomor_registrasi),
                    'nama'          => $clean($peserta?->nama),
                    'nik'           => $clean($peserta?->nik),
                    'jenis_kelamin' => $clean($peserta?->jenis_kelamin),
                    'instansi'      => $clean($peserta?->instansi?->asal_instansi),
                    'asrama'        => $clean($asrama?->name),
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
                    $r['kamar'],
                ], ';');
            }

            fclose($handle);

        }, $filename, [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }

    public function selectPeserta(int $pesertaId): void
    {
        $this->dispatch('$refresh');
    }

    public function selectBed(string $bedId): void
    {
        $this->dispatch('$refresh');
    }

    public function assignPesertaToBed(int $pesertaId, string $bedId): void
    {
        DB::transaction(function () use ($pesertaId, $bedId) {
            $pelatihanId = $this->record->id;
            $bedInfo = $this->parseBedId($bedId);
            if (!$bedInfo) {
                return;
            }

            $kp = $this->findKamarPelatihan($pelatihanId, $bedInfo['asrama_key'], $bedInfo['room_no']);
            if (!$kp) {
                return;
            }

            PenempatanAsrama::query()
                ->where('pelatihan_id', $pelatihanId)
                ->where('peserta_id', $pesertaId)
                ->delete();

            $ordered = $this->getRoomPlacements($pelatihanId, $kp->id);
            $ordered = array_values(array_filter($ordered, fn ($id) => $id !== $pesertaId));

            if (count($ordered) >= (int) $kp->total_beds) {
                return;
            }

            $insertAt = max(0, (int) $bedInfo['bed_slot'] - 1);
            array_splice($ordered, $insertAt, 0, [$pesertaId]);

            $this->writePlacements($pelatihanId, $kp->id, $ordered);
            $this->updateAvailableBeds($kp->id, (int) $kp->total_beds, count($ordered));
        });

        $this->dispatch('$refresh');
    }

    public function movePesertaBed(string $fromBedId, string $toBedId): void
    {
        DB::transaction(function () use ($fromBedId, $toBedId) {
            $pelatihanId = $this->record->id;
            $from = $this->parseBedId($fromBedId);
            $to = $this->parseBedId($toBedId);
            if (!$from || !$to) {
                return;
            }

            $fromKp = $this->findKamarPelatihan($pelatihanId, $from['asrama_key'], $from['room_no']);
            $toKp = $this->findKamarPelatihan($pelatihanId, $to['asrama_key'], $to['room_no']);
            if (!$fromKp || !$toKp) {
                return;
            }

            $fromList = $this->getRoomPlacements($pelatihanId, $fromKp->id);
            $fromIdx = (int) $from['bed_slot'] - 1;
            if (!isset($fromList[$fromIdx])) {
                return;
            }
            $pesertaId = $fromList[$fromIdx];
            unset($fromList[$fromIdx]);
            $fromList = array_values($fromList);

            PenempatanAsrama::query()
                ->where('pelatihan_id', $pelatihanId)
                ->where('peserta_id', $pesertaId)
                ->delete();

            $toList = $this->getRoomPlacements($pelatihanId, $toKp->id);
            if (count($toList) >= (int) $toKp->total_beds) {
                return;
            }
            $insertAt = max(0, (int) $to['bed_slot'] - 1);
            array_splice($toList, $insertAt, 0, [$pesertaId]);

            $this->writePlacements($pelatihanId, $fromKp->id, $fromList);
            $this->writePlacements($pelatihanId, $toKp->id, $toList);
            $this->updateAvailableBeds($fromKp->id, (int) $fromKp->total_beds, count($fromList));
            $this->updateAvailableBeds($toKp->id, (int) $toKp->total_beds, count($toList));
        });

        $this->dispatch('$refresh');
    }

    public function unassignPesertaFromBed(string $bedId): void
    {
        DB::transaction(function () use ($bedId) {
            $pelatihanId = $this->record->id;
            $bedInfo = $this->parseBedId($bedId);
            if (!$bedInfo) {
                return;
            }

            $kp = $this->findKamarPelatihan($pelatihanId, $bedInfo['asrama_key'], $bedInfo['room_no']);
            if (!$kp) {
                return;
            }

            $ordered = $this->getRoomPlacements($pelatihanId, $kp->id);
            $idx = (int) $bedInfo['bed_slot'] - 1;
            if (!isset($ordered[$idx])) {
                return;
            }
            unset($ordered[$idx]);
            $ordered = array_values($ordered);

            $this->writePlacements($pelatihanId, $kp->id, $ordered);
            $this->updateAvailableBeds($kp->id, (int) $kp->total_beds, count($ordered));
        });

        $this->dispatch('$refresh');
    }

    public function unassignBed(string $bedId): void
    {
        $this->unassignPesertaFromBed($bedId);
    }

    public function unassignPeserta(int $pesertaId): void
    {
        DB::transaction(function () use ($pesertaId) {
            $pelatihanId = $this->record->id;
            $placement = PenempatanAsrama::query()
                ->where('pelatihan_id', $pelatihanId)
                ->where('peserta_id', $pesertaId)
                ->first();
            if (!$placement) {
                return;
            }

            $kpId = $placement->kamar_pelatihan_id;
            $totalBeds = (int) (KamarPelatihan::find($kpId)?->total_beds ?? 0);
            $placement->delete();

            $ordered = $this->getRoomPlacements($pelatihanId, $kpId);
            $this->writePlacements($pelatihanId, $kpId, $ordered);
            $this->updateAvailableBeds($kpId, $totalBeds, count($ordered));
        });

        $this->dispatch('$refresh');
    }

    public function swapPesertaBed(string $bedIdA, string $bedIdB): void
    {
        DB::transaction(function () use ($bedIdA, $bedIdB) {
            $pelatihanId = $this->record->id;
            $a = $this->parseBedId($bedIdA);
            $b = $this->parseBedId($bedIdB);
            if (!$a || !$b) {
                return;
            }

            $kpA = $this->findKamarPelatihan($pelatihanId, $a['asrama_key'], $a['room_no']);
            $kpB = $this->findKamarPelatihan($pelatihanId, $b['asrama_key'], $b['room_no']);
            if (!$kpA || !$kpB) {
                return;
            }

            $listA = $this->getRoomPlacements($pelatihanId, $kpA->id);
            $listB = $this->getRoomPlacements($pelatihanId, $kpB->id);
            $idxA = (int) $a['bed_slot'] - 1;
            $idxB = (int) $b['bed_slot'] - 1;

            $pesertaA = $listA[$idxA] ?? null;
            $pesertaB = $listB[$idxB] ?? null;
            if (!$pesertaA && !$pesertaB) {
                return;
            }

            if ($pesertaA !== null) {
                PenempatanAsrama::query()
                    ->where('pelatihan_id', $pelatihanId)
                    ->where('peserta_id', $pesertaA)
                    ->delete();
            }
            if ($pesertaB !== null) {
                PenempatanAsrama::query()
                    ->where('pelatihan_id', $pelatihanId)
                    ->where('peserta_id', $pesertaB)
                    ->delete();
            }

            if ($kpA->id === $kpB->id) {
                if ($pesertaA !== null && $pesertaB !== null) {
                    $listA[$idxA] = $pesertaB;
                    $listA[$idxB] = $pesertaA;
                } elseif ($pesertaA !== null) {
                    unset($listA[$idxA]);
                    array_splice($listA, $idxB, 0, [$pesertaA]);
                } elseif ($pesertaB !== null) {
                    unset($listA[$idxB]);
                    array_splice($listA, $idxA, 0, [$pesertaB]);
                }
                $listA = array_values($listA);
                $this->writePlacements($pelatihanId, $kpA->id, $listA);
                $this->updateAvailableBeds($kpA->id, (int) $kpA->total_beds, count($listA));
                return;
            }

            if ($pesertaA !== null) {
                $listA[$idxA] = $pesertaB;
            }
            if ($pesertaB !== null) {
                $listB[$idxB] = $pesertaA;
            }
            $listA = array_values(array_filter($listA, fn ($v) => $v !== null));
            $listB = array_values(array_filter($listB, fn ($v) => $v !== null));

            $this->writePlacements($pelatihanId, $kpA->id, $listA);
            $this->writePlacements($pelatihanId, $kpB->id, $listB);
            $this->updateAvailableBeds($kpA->id, (int) $kpA->total_beds, count($listA));
            $this->updateAvailableBeds($kpB->id, (int) $kpB->total_beds, count($listB));
        });

        $this->dispatch('$refresh');
    }

    private function parseBedId(string $bedId): ?array
    {
        if (!preg_match('/^bed-(.+)-(\d+)-(\d+)$/', $bedId, $m)) {
            return null;
        }

        return [
            'asrama_key' => $m[1],
            'room_no' => (int) $m[2],
            'bed_slot' => (int) $m[3],
        ];
    }

    private function findKamarPelatihan(int $pelatihanId, string $asramaKey, int $roomNo): ?KamarPelatihan
    {
        return KamarPelatihan::query()
            ->select('kamar_pelatihans.*')
            ->join('kamars as k', 'k.id', '=', 'kamar_pelatihans.kamar_id')
            ->join('asramas as a', 'a.id', '=', 'k.asrama_id')
            ->where('kamar_pelatihans.pelatihan_id', $pelatihanId)
            ->where('k.nomor_kamar', $roomNo)
            ->whereRaw('LOWER(a.name) = ?', [strtolower($asramaKey)])
            ->first();
    }

    private function getRoomPlacements(int $pelatihanId, int $kpId): array
    {
        return PenempatanAsrama::query()
            ->where('pelatihan_id', $pelatihanId)
            ->where('kamar_pelatihan_id', $kpId)
            ->orderBy('id')
            ->pluck('peserta_id')
            ->values()
            ->all();
    }

    private function writePlacements(int $pelatihanId, int $kpId, array $pesertaIds): void
    {
        $pesertaIds = array_values(array_unique(array_filter($pesertaIds)));

        PenempatanAsrama::query()
            ->where('pelatihan_id', $pelatihanId)
            ->where('kamar_pelatihan_id', $kpId)
            ->delete();

        if (!empty($pesertaIds)) {
            PenempatanAsrama::query()
                ->where('pelatihan_id', $pelatihanId)
                ->whereIn('peserta_id', $pesertaIds)
                ->delete();
        }

        if (empty($pesertaIds)) {
            return;
        }

        $genderMap = PendaftaranPelatihan::query()
            ->where('pelatihan_id', $pelatihanId)
            ->whereIn('peserta_id', $pesertaIds)
            ->with('peserta')
            ->get()
            ->mapWithKeys(function (PendaftaranPelatihan $p) {
                return [$p->peserta_id => $p->peserta?->jenis_kelamin];
            })
            ->all();

        foreach ($pesertaIds as $pesertaId) {
            PenempatanAsrama::create([
                'peserta_id' => $pesertaId,
                'pelatihan_id' => $pelatihanId,
                'kamar_pelatihan_id' => $kpId,
                'gender' => $genderMap[$pesertaId] ?? null,
            ]);
        }
    }

    private function updateAvailableBeds(int $kpId, int $totalBeds, int $occupied): void
    {
        KamarPelatihan::query()
            ->where('id', $kpId)
            ->update([
                'available_beds' => max(0, $totalBeds - $occupied),
            ]);
    }
}
