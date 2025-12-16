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
     * 1. Cek apakah Cukup 1 Asrama (beda gender beda kamar).
     * 2. Kalau tdk cukup, Asrama Campur tapi Beda Kamar.
     * 3. Kalau tdk cukup, boleh campur gender di kamar 8 bed (urutan bed berdekatan).
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

            // 3. Analisis Kapasitas per Asrama (Strategy Phase)
            //    Hitung total available bed per asrama
            $asramaCapacity = [];
            foreach ($rooms as $r) {
                if (!isset($asramaCapacity[$r->asrama_id])) {
                    $asramaCapacity[$r->asrama_id] = 0;
                }
                $asramaCapacity[$r->asrama_id] += $r->available;
            }

            // Cari Asrama Prioritas (Single Dorm Strategy)
            // Asrama mana yg muat nampung SEMUA sisa peserta?
            $totalNeeded = count($toBeAllocated);
            $targetAsramaId = null;

            foreach ($asramaCapacity as $aid => $cap) {
                if ($cap >= $totalNeeded) {
                    $targetAsramaId = $aid; // Found candidate
                    break; // Ambil yang pertama ketemu (bs diimprove logicnya cari yg paling pas)
                }
            }

            // 4. Allocation Loop
            foreach ($toBeAllocated as $p) {
                $gender = $p->peserta->jenis_kelamin;
                $assignedRoom = null;

                // --- PASS 1: Strict Gender & Location ---
                // Try find room in Target Asrama (if any), strict gender
                if ($targetAsramaId) {
                    $assignedRoom = $this->findRoom($rooms, $gender, $targetAsramaId, strictGender: true);
                }

                // If not found in target (or no target), try finding room in ANY asrama, strict gender
                if (!$assignedRoom) {
                    $assignedRoom = $this->findRoom($rooms, $gender, null, strictGender: true);
                }

                // --- PASS 2: Falback Mixed Gender (8 Beds Only) ---
                // Hanya boleh jika kamar punya total_beds == 8
                if (!$assignedRoom) {
                    $assignedRoom = $this->findRoom($rooms, $gender, null, strictGender: false, allowMixed8Bed: true);
                }

                if ($assignedRoom) {
                    // Execute Allocation update in Memory
                    $assignedRoom->available--;
                    $assignedRoom->occupants_genders[] = $gender;

                    // Execute Database Insert
                    PenempatanAsrama::create([
                        'peserta_id'         => $p->peserta_id,
                        'pelatihan_id'       => $pelatihanId,
                        'kamar_pelatihan_id' => $assignedRoom->kp_id,
                        'gender'             => $gender,
                    ]);

                    // Update available counter di DB langsung (atau bulk nanti, tp biara aman per row dlu)
                    DB::table('kamar_pelatihans')
                        ->where('id', $assignedRoom->kp_id)
                        ->decrement('available_beds');

                    $result['ok']++;
                    $result['details'][] = ['pid' => $p->id, 'kamar' => $assignedRoom->kp_id];
                } else {
                    $result['failed_full']++;
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
}
