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

            $assign = function ($p, $assignedRoom) use ($pelatihanId, &$result) {
                $gender = $p->peserta->jenis_kelamin;

                $assignedRoom->available--;
                $assignedRoom->occupants_genders[] = $gender;

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

            // PASS 1: Pisah gender beda asrama (majority -> asrama capacity terbesar)
            if ($phase1Plan) {
                foreach ($toBeAllocated as $p) {
                    $gender = $p->peserta->jenis_kelamin;
                    $asramaId = $phase1Plan[$gender] ?? null;
                    $assignedRoom = $this->findRoom($rooms, $gender, $asramaId, strictGender: true);
                    if ($assignedRoom) {
                        $assign($p, $assignedRoom);
                    } else {
                        $remaining[] = $p;
                    }
                }
            } else {
                $remaining = $toBeAllocated;
            }

            // PASS 2: Boleh campur asrama, tetap beda kamar (strict gender per kamar)
            $remaining2 = [];
            foreach ($remaining as $p) {
                $gender = $p->peserta->jenis_kelamin;
                $assignedRoom = $this->findRoom($rooms, $gender, null, strictGender: true);
                if ($assignedRoom) {
                    $assign($p, $assignedRoom);
                } else {
                    $remaining2[] = $p;
                }
            }

            // PASS 3: Hanya 1 kamar beda gender, khusus total_beds=8
            if (!empty($remaining2)) {
                $mixedRoomId = $this->pickMixedRoomId($rooms);
                if (!$mixedRoomId) {
                    $result['failed_full'] += count($remaining2);
                    return $result;
                }

                $preferredGender = $this->preferredGenderForMixed($remaining2, $rooms, $mixedRoomId);
                usort($remaining2, function ($a, $b) use ($preferredGender) {
                    $ga = $a->peserta->jenis_kelamin === $preferredGender ? 0 : 1;
                    $gb = $b->peserta->jenis_kelamin === $preferredGender ? 0 : 1;
                    return $ga <=> $gb;
                });

                foreach ($remaining2 as $p) {
                    $assignedRoom = $this->findMixedRoom($rooms, $mixedRoomId);
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
