<?php

namespace App\Services;

use App\Models\Kamar;
use App\Models\PenempatanAsrama;
use App\Models\PendaftaranPelatihan;
use Illuminate\Support\Facades\DB;

class AsramaAllocator
{
    /**
     * Attach semua Kamar (global) ke 1 Pelatihan via tabel kamar_pelatihan.
     *
     * - Default: TIDAK menimpa available_beds yang sudah ada (biar edit admin aman)
     * - Kalau $reset = true: available_beds di-reset = total_beds (fresh start pelatihan)
     */
    public function attachKamarToPelatihan(int $pelatihanId, bool $reset = false): void
    {
        $kamars = Kamar::query()->get();

        DB::transaction(function () use ($pelatihanId, $kamars, $reset) {

            foreach ($kamars as $kamar) {
                $exists = DB::table('kamar_pelatihans')
                    ->where('kamar_id', $kamar->id)
                    ->where('pelatihan_id', $pelatihanId)
                    ->exists();

                // kalau sudah ada dan tidak reset -> skip (jaga edit manual)
                if ($exists && !$reset) {
                    // tetap sync status aktif mengikuti kamar global
                    DB::table('kamar_pelatihans')
                        ->where('kamar_id', $kamar->id)
                        ->where('pelatihan_id', $pelatihanId)
                        ->update([
                            'is_active'  => (bool) $kamar->is_active,
                            'updated_at' => now(),
                        ]);
                    continue;
                }

                // insert / update
                DB::table('kamar_pelatihans')->updateOrInsert(
                    [
                        'kamar_id'     => $kamar->id,
                        'pelatihan_id' => $pelatihanId,
                    ],
                    [
                        'total_beds'     => (int) ($kamar->total_beds ?? 0),
                        'available_beds' => $reset ? (int) $kamar->total_beds : (int) ($kamar->total_beds ?? 0),
                        'is_active'      => (bool) $kamar->is_active,
                        'created_at'     => $exists ? DB::raw('created_at') : now(),
                        'updated_at'     => now(),
                    ]
                );
            }
        });
    }

    /**
     * Auto-allocate peserta untuk 1 pelatihan:
     * - Skip jika peserta sudah punya penempatan (unique peserta_id + pelatihan_id)
     * - Cari kamar_pelatihan yang aktif dan available_beds > 0
     * - Lock row (FOR UPDATE) biar tidak dobel ketika concurrent
     * - Simpan penempatan_asrama + decrement available_beds
     */
    /**
     * Auto-allocate peserta untuk 1 pelatihan dengan prioritas:
     * 1. Pisahkan gender beda asrama (gender terbanyak ke asrama dengan bed terbanyak).
     * 2. Kalau tdk cukup, satu asrama boleh beda gender tapi beda kamar.
     * 3. Kalau tdk cukup, hanya boleh 1 kamar beda gender (khusus kamar 8 bed).
     */
    public function allocatePeserta(int $pelatihanId): array
    {
        $kamarTable = (new Kamar())->getTable();

        return DB::transaction(function () use ($pelatihanId, $kamarTable) {
            $result = [
                'ok' => 0,
                'skipped_already_assigned' => 0,
                'failed_full' => 0,
                'details' => [],
            ];

            // 1. Ambil semua peserta yang belum dapat kamar
            //    (Existing placements di-skip di query biar efisien, tapi kita hitung buat stats)
            $allParticipants = PendaftaranPelatihan::with('peserta')
                ->where('pelatihan_id', $pelatihanId)
                ->orderBy('id')
                ->get();

            $toBeAllocated = [];
            foreach ($allParticipants as $p) {
                // cek existing placement db check
                $hasPlacement = PenempatanAsrama::where('peserta_id', $p->peserta_id)
                    ->where('pelatihan_id', $pelatihanId)
                    ->exists();

                if ($hasPlacement) {
                    $result['skipped_already_assigned']++;
                    continue;
                }

                $gender = $p->peserta->jenis_kelamin;
                if (!in_array($gender, ['Laki-laki', 'Perempuan'])) {
                    $result['failed_full']++; // anggap fail validation
                    continue;
                }
                $toBeAllocated[] = $p;
            }

            if (empty($toBeAllocated)) {
                return $result;
            }

            // 2. Load State Akhir Kamar (Snapshot in memory)
            //    Kita ambil semua kamar aktif untuk pelatihan ini with current occupancy info
            $roomsRaw = DB::table('kamar_pelatihans as kp')
                ->join($kamarTable . ' as k', 'k.id', '=', 'kp.kamar_id')
                ->join('asramas as a', 'a.id', '=', 'k.asrama_id')
                ->where('kp.pelatihan_id', $pelatihanId)
                ->where('kp.is_active', true)
                ->where('k.is_active', true)
                ->select([
                    'kp.id as kp_id',
                    'kp.kamar_id',
                    'kp.available_beds',
                    'k.total_beds',
                    'k.nomor_kamar',
                    'a.id as asrama_id',
                    'a.name as asrama_name',
                ])
                ->orderBy('a.name')
                ->orderBy('k.nomor_kamar')
                ->lockForUpdate() // Lock baris biar aman concurency
                ->get();

            // Transform ke array objects biar enak dimanispuasi
            // Structure: [kp_id => {available, genders_in_room: [], ...}]
            // Kita butuh tau siapa yg sudah ada di dalam kamar utk cek mixed gender
            $rooms = [];
            foreach ($roomsRaw as $r) {
                // Cek siapa yang sudah ada di kamar ini (untuk tau gender existing)
                $existingOccupants = PenempatanAsrama::where('kamar_pelatihan_id', $r->kp_id)->pluck('gender')->toArray();

                $rooms[$r->kp_id] = (object) [
                    'kp_id' => $r->kp_id,
                    'kamar_id' => $r->kamar_id,
                    'asrama_id' => $r->asrama_id,
                    'nomor_kamar' => (int) $r->nomor_kamar,
                    'total_beds' => $r->total_beds,
                    'available' => $r->available_beds,
                    'occupants_genders' => $existingOccupants, // ['Laki-laki', 'Perempuan']
                ];
            }

            // 3. Analisis Kapasitas per Asrama + gender existing
            $asramaStats = [];
            foreach ($rooms as $r) {
                if (!isset($asramaStats[$r->asrama_id])) {
                    $asramaStats[$r->asrama_id] = [
                        'available' => 0,
                        'genders' => [],
                    ];
                }
                $asramaStats[$r->asrama_id]['available'] += $r->available;
                $asramaStats[$r->asrama_id]['genders'] = array_values(array_unique(array_merge(
                    $asramaStats[$r->asrama_id]['genders'],
                    $r->occupants_genders
                )));
            }

            $genderCounts = [
                'Laki-laki' => 0,
                'Perempuan' => 0,
            ];
            foreach ($toBeAllocated as $p) {
                $genderCounts[$p->peserta->jenis_kelamin]++;
            }

            $phase1Plan = $this->pickSeparatedAsramaPlan($asramaStats, $genderCounts);
            $asramaOrder = [];
            foreach ($roomsRaw as $r) {
                if (!in_array($r->asrama_id, $asramaOrder, true)) {
                    $asramaOrder[] = $r->asrama_id;
                }
            }

            $assign = function ($p, $assignedRoom) use ($pelatihanId, &$result, &$asramaStats) {
                $gender = $p->peserta->jenis_kelamin;

                $assignedRoom->available--;
                $assignedRoom->occupants_genders[] = $gender;

                if (isset($asramaStats[$assignedRoom->asrama_id])) {
                    $asramaStats[$assignedRoom->asrama_id]['available'] = max(
                        0,
                        (int) $asramaStats[$assignedRoom->asrama_id]['available'] - 1
                    );
                    $asramaStats[$assignedRoom->asrama_id]['genders'] = array_values(array_unique(array_merge(
                        $asramaStats[$assignedRoom->asrama_id]['genders'] ?? [],
                        [$gender]
                    )));
                }

                PenempatanAsrama::create([
                    'peserta_id'         => $p->peserta_id,
                    'pelatihan_id'       => $pelatihanId,
                    'kamar_pelatihan_id' => $assignedRoom->kp_id,
                    'gender'             => $gender,
                ]);

                DB::table('kamar_pelatihans')
                    ->where('id', $assignedRoom->kp_id)
                    ->decrement('available_beds');

                $result['ok']++;
                $result['details'][] = ['pid' => $p->id, 'kamar' => $assignedRoom->kp_id];
            };

            // 4. Allocation Phase
            $remaining = [];

            // PASS 1: Pisah gender beda asrama dan habiskan satu asrama dulu per gender
            if ($phase1Plan) {
                $groupedByGender = [
                    'Laki-laki' => [],
                    'Perempuan' => [],
                ];
                foreach ($toBeAllocated as $p) {
                    $gender = $p->peserta->jenis_kelamin;
                    if (isset($groupedByGender[$gender])) {
                        $groupedByGender[$gender][] = $p;
                    }
                }

                foreach ($groupedByGender as $gender => $participants) {
                    if (empty($participants)) {
                        continue;
                    }
                    $asramaId = $phase1Plan[$gender] ?? null;
                    $direction = $gender === 'Perempuan' ? 'desc' : 'asc';

                    $totalParticipants = count($participants);
                    foreach ($participants as $idx => $p) {
                        $remainingForGender = $totalParticipants - $idx;
                        $assignedRoom = $this->findRoomByOrder(
                            $rooms,
                            $gender,
                            $asramaId,
                            $direction,
                            $remainingForGender,
                            strictGender: true
                        );
                        if ($assignedRoom) {
                            $assign($p, $assignedRoom);
                        } else {
                            $remaining[] = $p;
                        }
                    }
                }
            } else {
                $remaining = $toBeAllocated;
            }

            // PASS 2: Isi satu asrama sampai penuh baru pindah ke asrama lain (per gender, urutan tetap)
            $remaining2 = [];
            $groupedByGender = [
                'Laki-laki' => [],
                'Perempuan' => [],
            ];
            foreach ($remaining as $p) {
                $gender = $p->peserta->jenis_kelamin;
                if (isset($groupedByGender[$gender])) {
                    $groupedByGender[$gender][] = $p;
                }
            }

            foreach ($groupedByGender as $gender => $participants) {
                if (empty($participants)) {
                    continue;
                }
                $direction = $gender === 'Perempuan' ? 'desc' : 'asc';
                $eligibleAsramaIds = $this->orderedEligibleAsramaIds($asramaStats, $gender, $asramaOrder);
                $asramaIndex = 0;

                $totalParticipants = count($participants);
                foreach ($participants as $idx => $p) {
                    $remainingForGender = $totalParticipants - $idx;
                    $assignedRoom = null;
                    while ($asramaIndex < count($eligibleAsramaIds)) {
                        $asramaId = $eligibleAsramaIds[$asramaIndex];
                        $assignedRoom = $this->findRoomByOrder(
                            $rooms,
                            $gender,
                            $asramaId,
                            $direction,
                            $remainingForGender,
                            strictGender: true
                        );
                        if ($assignedRoom) {
                            break;
                        }
                        $asramaIndex++;
                    }

                    if ($assignedRoom) {
                        $assign($p, $assignedRoom);
                    } else {
                        $remaining2[] = $p;
                    }
                }
            }

            // PASS 3: Jika benar-benar tidak ada bed single-gender tersisa,
            //         hanya SATU asrama (kapasitas terbanyak) boleh campur gender.
            if (!empty($remaining2)) {
                $hasSingleGenderCapacity = false;
                foreach ($remaining2 as $p) {
                    $gender = $p->peserta->jenis_kelamin;
                    $eligibleAsramaIds = array_keys($this->eligibleAsramaForGender($asramaStats, $gender));
                    if (!empty($eligibleAsramaIds)) {
                        $hasSingleGenderCapacity = true;
                        break;
                    }
                }

                if ($hasSingleGenderCapacity) {
                    $result['failed_full'] += count($remaining2);
                    return $result;
                }

                $mixedAsramaId = $this->pickExistingMixedAsramaId($asramaStats)
                    ?? $this->pickMixedAsramaId($asramaStats);
                if (!$mixedAsramaId) {
                    $result['failed_full'] += count($remaining2);
                    return $result;
                }

                $totalRemaining = count($remaining2);
                foreach ($remaining2 as $idx => $p) {
                    $gender = $p->peserta->jenis_kelamin;
                    $direction = $gender === 'Perempuan' ? 'desc' : 'asc';
                    $assignedRoom = $this->findRoomByOrder(
                        $rooms,
                        $gender,
                        $mixedAsramaId,
                        $direction,
                        $totalRemaining - $idx,
                        strictGender: true
                    );
                    if ($assignedRoom) {
                        $assign($p, $assignedRoom);
                    } else {
                        $result['failed_full']++;
                    }
                }
            }

            return $result;
        });
    }

    /**
     * Helper cari kamar di memory list
     */
    private function findRoom(array $rooms, string $gender, ?int $asramaId, bool $strictGender, bool $allowMixed8Bed = false)
    {
        foreach ($rooms as $r) {
            if ($r->available <= 0) continue;

            // Filter Asrama (optional)
            if ($asramaId && $r->asrama_id != $asramaId) continue;

            // Cek Gender Rule
            $currentGenders = array_unique($r->occupants_genders);
            $isEmpty = empty($currentGenders);

            // STRICT GENDER:
            // - Kamar kosong: OK
            // - Kamar isi: Harus sama gendernya
            if ($strictGender) {
                if ($isEmpty) return $r;
                if (count($currentGenders) === 1 && $currentGenders[0] === $gender) return $r;
            }
            // MIXED RULE (Only for 8 beds):
            // - Kalau allowMixed8Bed = true, hanya boleh ambil kamar yg total_beds=8
            // - Note: Logic ini jalan kalau strictGender = false (fallback)
            else {
                if ($allowMixed8Bed && $r->total_beds == 8) {
                    return $r;
                }
            }
        }
        return null;
    }

    private function findRoomByOrder(
        array $rooms,
        string $gender,
        ?int $asramaId,
        string $direction,
        int $remainingForGender,
        bool $strictGender
    ): ?object
    {
        $list = array_values($rooms);
        if ($asramaId) {
            $list = array_values(array_filter($list, fn ($r) => $r->asrama_id === $asramaId));
        }

        usort($list, function ($a, $b) use ($direction) {
            $cmp = $a->nomor_kamar <=> $b->nomor_kamar;
            return $direction === 'desc' ? -$cmp : $cmp;
        });

        foreach ($list as $r) {
            if ($r->available <= 0) continue;

            $currentGenders = array_unique($r->occupants_genders);
            $isEmpty = empty($currentGenders);
            $currentOccupancy = count($r->occupants_genders);

            if ((int) $r->total_beds > 5 && $currentOccupancy < 4) {
                $neededToReachMin = 4 - $currentOccupancy;
                if ($remainingForGender < $neededToReachMin) {
                    continue;
                }
            }

            if ($strictGender) {
                if ($isEmpty) return $r;
                if (count($currentGenders) === 1 && $currentGenders[0] === $gender) return $r;
            } else {
                return $r;
            }
        }

        return null;
    }

    private function pickMixedAsramaId(array $asramaStats): ?int
    {
        if (empty($asramaStats)) {
            return null;
        }

        $bestId = null;
        $bestAvail = -1;
        foreach ($asramaStats as $asramaId => $stats) {
            $avail = (int) ($stats['available'] ?? 0);
            if ($avail > $bestAvail) {
                $bestAvail = $avail;
                $bestId = $asramaId;
            }
        }

        return $bestAvail > 0 ? $bestId : null;
    }

    private function pickExistingMixedAsramaId(array $asramaStats): ?int
    {
        $bestId = null;
        $bestAvail = -1;

        foreach ($asramaStats as $asramaId => $stats) {
            $genders = $stats['genders'] ?? [];
            if (count($genders) < 2) {
                continue;
            }
            $avail = (int) ($stats['available'] ?? 0);
            if ($avail > $bestAvail) {
                $bestAvail = $avail;
                $bestId = $asramaId;
            }
        }

        return $bestId;
    }

    private function pickSeparatedAsramaPlan(array $asramaStats, array $genderCounts): ?array
    {
        $genders = ['Laki-laki', 'Perempuan'];
        $major = $genderCounts['Laki-laki'] >= $genderCounts['Perempuan'] ? 'Laki-laki' : 'Perempuan';
        $minor = $major === 'Laki-laki' ? 'Perempuan' : 'Laki-laki';

        if (($genderCounts[$major] ?? 0) === 0 && ($genderCounts[$minor] ?? 0) === 0) {
            return null;
        }

        $eligibleMajor = $this->eligibleAsramaForGender($asramaStats, $major);
        $eligibleMinor = $this->eligibleAsramaForGender($asramaStats, $minor);

        foreach ($eligibleMajor as $majorId => $majorCap) {
            if ($genderCounts[$major] > $majorCap) continue;

            if ($genderCounts[$minor] === 0) {
                return [$major => $majorId];
            }

            foreach ($eligibleMinor as $minorId => $minorCap) {
                if ($minorId === $majorId) continue;
                if ($genderCounts[$minor] <= $minorCap) {
                    return [
                        $major => $majorId,
                        $minor => $minorId,
                    ];
                }
            }
        }

        return null;
    }

    private function eligibleAsramaForGender(array $asramaStats, string $gender): array
    {
        $eligible = [];
        foreach ($asramaStats as $asramaId => $stats) {
            $genders = $stats['genders'] ?? [];
            $isOk = empty($genders) || (count($genders) === 1 && $genders[0] === $gender);
            if ($isOk && ($stats['available'] ?? 0) > 0) {
                $eligible[$asramaId] = (int) $stats['available'];
            }
        }

        arsort($eligible);
        return $eligible;
    }

    private function orderedEligibleAsramaIds(array $asramaStats, string $gender, array $asramaOrder): array
    {
        $eligible = [];
        foreach ($asramaOrder as $asramaId) {
            $stats = $asramaStats[$asramaId] ?? null;
            if (!$stats) {
                continue;
            }
            $genders = $stats['genders'] ?? [];
            $isOk = empty($genders) || (count($genders) === 1 && $genders[0] === $gender);
            if ($isOk && ($stats['available'] ?? 0) > 0) {
                $eligible[] = $asramaId;
            }
        }

        return $eligible;
    }

    private function pickMixedRoomId(array $rooms): ?int
    {
        $mixed = [];
        $candidates = [];

        foreach ($rooms as $r) {
            if ($r->available <= 0 || (int) $r->total_beds !== 8) continue;
            $currentGenders = array_unique($r->occupants_genders);
            if (count($currentGenders) > 1) {
                $mixed[$r->kp_id] = $r->available;
            } else {
                $candidates[$r->kp_id] = $r->available;
            }
        }

        if (!empty($mixed)) {
            arsort($mixed);
            return (int) array_key_first($mixed);
        }

        if (!empty($candidates)) {
            arsort($candidates);
            return (int) array_key_first($candidates);
        }

        return null;
    }

    private function preferredGenderForMixed(array $participants, array $rooms, int $mixedRoomId): string
    {
        $preferred = null;
        if (isset($rooms[$mixedRoomId])) {
            $currentGenders = array_unique($rooms[$mixedRoomId]->occupants_genders);
            if (count($currentGenders) === 1) {
                $preferred = $currentGenders[0];
            }
        }

        if ($preferred) {
            return $preferred;
        }

        $counts = ['Laki-laki' => 0, 'Perempuan' => 0];
        foreach ($participants as $p) {
            $counts[$p->peserta->jenis_kelamin]++;
        }

        return $counts['Laki-laki'] >= $counts['Perempuan'] ? 'Laki-laki' : 'Perempuan';
    }

    private function findMixedRoom(array $rooms, int $mixedRoomId)
    {
        if (!isset($rooms[$mixedRoomId])) {
            return null;
        }

        $r = $rooms[$mixedRoomId];
        if ($r->available <= 0) return null;
        if ((int) $r->total_beds !== 8) return null;

        return $r;
    }
}
