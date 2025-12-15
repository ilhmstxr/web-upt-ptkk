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
                $exists = DB::table('kamar_pelatihan')
                    ->where('kamar_id', $kamar->id)
                    ->where('pelatihan_id', $pelatihanId)
                    ->exists();

                // kalau sudah ada dan tidak reset -> skip (jaga edit manual)
                if ($exists && !$reset) {
                    // tetap sync status aktif mengikuti kamar global
                    DB::table('kamar_pelatihan')
                        ->where('kamar_id', $kamar->id)
                        ->where('pelatihan_id', $pelatihanId)
                        ->update([
                            'is_active'  => (bool) $kamar->is_active,
                            'updated_at' => now(),
                        ]);
                    continue;
                }

                // insert / update
                DB::table('kamar_pelatihan')->updateOrInsert(
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
    public function allocatePeserta(int $pelatihanId): array
    {
        $pendaftaranList = PendaftaranPelatihan::with('peserta')
            ->where('pelatihan_id', $pelatihanId)
            ->orderBy('id')
            ->get();

        $kamarTable = (new Kamar())->getTable(); // aman kalau tabel kamu 'kamar' / 'kamars'

        return DB::transaction(function () use ($pelatihanId, $pendaftaranList, $kamarTable) {

            $result = [
                'ok' => 0,
                'skipped_already_assigned' => 0,
                'failed_full' => 0,
                'details' => [],
            ];

            foreach ($pendaftaranList as $p) {

                // sudah ada penempatan?
                $already = PenempatanAsrama::query()
                    ->where('peserta_id', $p->peserta_id)
                    ->where('pelatihan_id', $pelatihanId)
                    ->exists();

                if ($already) {
                    $result['skipped_already_assigned']++;
                    continue;
                }

                $gender = $p->peserta->jenis_kelamin ?? null;
                // kolom penempatan_asrama.gender enum cuma Laki-laki/Perempuan -> validasi minimal
                if (!in_array($gender, ['Laki-laki', 'Perempuan'], true)) {
                    $result['failed_full']++;
                    $result['details'][] = [
                        'pendaftaran_id' => $p->id,
                        'reason' => 'gender peserta kosong/tidak valid',
                    ];
                    continue;
                }

                /**
                 * Ambil 1 kamar_pelatihan yang tersedia.
                 * Kalau mau urutan tertentu, join kamar + asrama untuk sorting.
                 */
                $kp = DB::table('kamar_pelatihan as kp')
                    ->join($kamarTable . ' as k', 'k.id', '=', 'kp.kamar_id')
                    ->join('asrama as a', 'a.id', '=', 'k.asrama_id')
                    ->where('kp.pelatihan_id', $pelatihanId)
                    ->where('kp.is_active', true)
                    ->where('k.is_active', true)
                    ->where('kp.available_beds', '>', 0)
                    ->orderBy('a.name')
                    ->orderBy('k.nomor_kamar')
                    ->lockForUpdate()
                    ->first();

                if (!$kp) {
                    $result['failed_full']++;
                    $result['details'][] = [
                        'pendaftaran_id' => $p->id,
                        'reason' => 'kamar penuh',
                    ];
                    continue;
                }

                PenempatanAsrama::create([
                    'peserta_id'         => $p->peserta_id,
                    'pelatihan_id'       => $pelatihanId,
                    'kamar_pelatihan_id' => $kp->id,
                    'gender'             => $gender,
                ]);

                DB::table('kamar_pelatihan')
                    ->where('id', $kp->id)
                    ->decrement('available_beds');

                $result['ok']++;
                $result['details'][] = [
                    'pendaftaran_id' => $p->id,
                    'kamar_pelatihan_id' => $kp->id,
                    'kamar_id' => $kp->kamar_id,
                ];
            }

            return $result;
        });
    }
}
