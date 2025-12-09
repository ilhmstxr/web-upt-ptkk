<?php

namespace App\Services;

use App\Models\Asrama;
use App\Models\Kamar;
use App\Models\PenempatanAsrama;
use App\Models\PendaftaranPelatihan;
use Illuminate\Support\Facades\DB;

class AsramaAllocator
{
    /**
     * Mapping blok berdasarkan gender.
     * Boleh kamu ubah sesuai aturan.
     */
    protected array $blokByGender = [
        'Laki-laki' => ['Melati Bawah', 'Tulip Bawah'],
        'Perempuan' => ['Mawar', 'Melati Atas', 'Tulip Atas'],
    ];

    /**
     * STEP 1:
     * Generate struktur asrama/kamar di DB dari config, PER PELATIHAN.
     * Dipanggil sekali sebelum allocate.
     */
    public function generateRoomsFromConfig(int $pelatihanId, ?array $overrideConfig = null): void
    {
        $config = $overrideConfig ?? config('kamar');

        DB::transaction(function () use ($pelatihanId, $config) {

            foreach ($this->blokByGender as $gender => $blokList) {

                foreach ($blokList as $blok) {
                    $rooms = $config[$blok] ?? [];

                    // create/update Asrama
                    $asrama = Asrama::updateOrCreate(
                        [
                            'pelatihan_id' => $pelatihanId,
                            'name'         => $blok,
                            'gender'       => $gender,
                        ],
                        [
                            'total_kamar' => collect($rooms)
                                ->filter(fn ($r) => is_numeric($r['bed']) && (int)$r['bed'] > 0)
                                ->count(),
                        ]
                    );

                    // create/update each room
                    foreach ($rooms as $room) {
                        $no  = (int) ($room['no'] ?? 0);
                        $bed = $room['bed'] ?? null;

                        $isActive = is_numeric($bed) && (int)$bed > 0;

                        Kamar::updateOrCreate(
                            [
                                'asrama_id'   => $asrama->id,
                                'nomor_kamar' => $no,
                            ],
                            [
                                'pelatihan_id'   => $pelatihanId,
                                'total_beds'     => $isActive ? (int)$bed : 0,
                                'available_beds' => $isActive ? (int)$bed : 0,
                                'is_active'      => $isActive,
                            ]
                        );
                    }
                }
            }
        });
    }

    /**
     * STEP 2:
     * Allocate peserta ke kamar/bed secara permanen.
     * Hasil masuk penempatan_asrama.
     */
    public function allocatePesertaPerPelatihan(int $pelatihanId): array
    {
        return DB::transaction(function () use ($pelatihanId) {

            // ambil semua pendaftaran pelatihan ini
            $pendaftaranList = PendaftaranPelatihan::with('peserta')
                ->where('pelatihan_id', $pelatihanId)
                ->orderBy('id')
                ->get();

            $result = [
                'ok' => 0,
                'skipped_already_assigned' => 0,
                'failed_full' => 0,
                'details' => [],
            ];

            foreach ($pendaftaranList as $pend) {

                // skip kalau sudah pernah ditempatkan
                if (PenempatanAsrama::where('pendaftaran_id', $pend->id)->exists()) {
                    $result['skipped_already_assigned']++;
                    continue;
                }

                $gender = $pend->peserta->jenis_kelamin ?? null;
                if (!$gender || !isset($this->blokByGender[$gender])) {
                    $result['failed_full']++;
                    $result['details'][] = [
                        'pendaftaran_id' => $pend->id,
                        'reason' => 'gender tidak valid / kosong',
                    ];
                    continue;
                }

                // cari kamar aktif dengan available_beds > 0 sesuai gender
                $kamar = Kamar::where('pelatihan_id', $pelatihanId)
                    ->whereHas('asrama', fn($q) => $q->where('gender', $gender))
                    ->where('is_active', true)
                    ->where('available_beds', '>', 0)
                    ->orderBy('asrama_id')
                    ->orderBy('nomor_kamar')
                    ->lockForUpdate()
                    ->first();

                if (!$kamar) {
                    $result['failed_full']++;
                    $result['details'][] = [
                        'pendaftaran_id' => $pend->id,
                        'reason' => 'kamar penuh',
                    ];
                    continue;
                }

                // bed_no = total_beds - available_beds + 1
                $bedNo = ($kamar->total_beds - $kamar->available_beds) + 1;

                PenempatanAsrama::create([
                    'pendaftaran_id' => $pend->id,
                    'kamar_id'       => $kamar->id,
                    'bed_no'         => $bedNo,
                ]);

                $kamar->decrement('available_beds');

                $result['ok']++;
                $result['details'][] = [
                    'pendaftaran_id' => $pend->id,
                    'kamar_id' => $kamar->id,
                    'bed_no' => $bedNo,
                ];
            }

            return $result;
        });
    }

    /**
     * Helper ambil penempatan peserta (buat dashboard/email)
     */
    public function getPenempatanByPendaftaran(int $pendaftaranId)
    {
        return PenempatanAsrama::with('kamar.asrama')
            ->where('pendaftaran_id', $pendaftaranId)
            ->first();
    }
}
