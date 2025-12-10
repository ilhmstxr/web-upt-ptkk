<?php

namespace App\Services;

use App\Models\Asrama;
use App\Models\Kamar;
use App\Models\PenempatanAsrama;
use App\Models\PendaftaranPelatihan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AsramaAllocator
{
    protected array $blokByGender = [
        'Laki-laki' => ['Melati Bawah', 'Tulip Bawah'],
        'Perempuan' => ['Mawar', 'Melati Atas', 'Tulip Atas'],
    ];

    public function generateRoomsFromConfig(int $pelatihanId, ?array $overrideConfig = null): void
    {
        $config = $overrideConfig ?? config('kamar');

        DB::transaction(function () use ($pelatihanId, $config) {
            foreach ($this->blokByGender as $gender => $blokList) {
                foreach ($blokList as $blok) {
                    $rooms = $config[$blok] ?? [];

                    $blokLower = strtolower($blok);
                    $lantai = str_contains($blokLower, 'atas') ? 'atas' : 'bawah';

                    $asrama = Asrama::updateOrCreate(
                        [
                            'pelatihan_id' => $pelatihanId,
                            'name'         => $blok,
                            'gender'       => $gender,
                        ],
                        [
                            'total_kamar' => collect($rooms)
                                ->filter(fn ($r) => is_numeric($r['bed']) && (int) $r['bed'] > 0)
                                ->count(),
                        ]
                    );

                    foreach ($rooms as $room) {
                        $no  = (int) ($room['no'] ?? 0);
                        $bed = $room['bed'] ?? null;

                        $isActive = is_numeric($bed) && (int) $bed > 0;

                        Kamar::updateOrCreate(
                            [
                                'pelatihan_id' => $pelatihanId,
                                'asrama_id'    => $asrama->id,
                                'nomor_kamar'  => $no,
                            ],
                            [
                                'lantai'         => $lantai,
                                'total_beds'     => $isActive ? (int) $bed : 0,
                                'available_beds' => $isActive ? (int) $bed : 0,
                                'is_active'      => $isActive,
                            ]
                        );
                    }
                }
            }
        });
    }

    public function allocatePesertaPerPelatihan(int $pelatihanId): array
    {
        return DB::transaction(function () use ($pelatihanId) {

            $pendaftaranList = PendaftaranPelatihan::with('peserta')
                ->where('pelatihan_id', $pelatihanId)
                ->orderBy('id')
                ->get();

            $hasPendaftaranId    = Schema::hasColumn('penempatan_asrama', 'pendaftaran_id');
            $hasPendaftaranPelId = Schema::hasColumn('penempatan_asrama', 'pendaftaran_pelatihan_id');
            $hasPesertaId        = Schema::hasColumn('penempatan_asrama', 'peserta_id');

            $result = [
                'ok' => 0,
                'skipped_already_assigned' => 0,
                'failed_full' => 0,
                'details' => [],
            ];

            foreach ($pendaftaranList as $pend) {

                $already = false;
                if ($hasPendaftaranId) {
                    $already = PenempatanAsrama::where('pendaftaran_id', $pend->id)->exists();
                } elseif ($hasPendaftaranPelId) {
                    $already = PenempatanAsrama::where('pendaftaran_pelatihan_id', $pend->id)->exists();
                } elseif ($hasPesertaId) {
                    $already = PenempatanAsrama::where('peserta_id', $pend->peserta_id)->exists();
                }

                if ($already) {
                    $result['skipped_already_assigned']++;
                    continue;
                }

                $gender = $pend->peserta->jenis_kelamin ?? null;
                if (!$gender || !isset($this->blokByGender[$gender])) {
                    $result['failed_full']++;
                    $result['details'][] = [
                        'pendaftaran_id' => $pend->id,
                        'reason' => 'gender kosong/tidak valid',
                    ];
                    continue;
                }

                $kamar = Kamar::where('pelatihan_id', $pelatihanId)
                    ->whereHas('asrama', fn ($q) => $q->where('gender', $gender))
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

                $payload = [
    'pelatihan_id' => $pelatihanId,
    'kamar_id'     => $kamar->id,
    'asrama_id'    => $kamar->asrama_id, // ✅ ini wajib biar gak error
];

// kalau kolom peserta_id ada → isi dari peserta yang bener
if ($hasPesertaId) {
    $payload['peserta_id'] = $pend->peserta_id ?? ($pend->peserta->id ?? null);
}

// kalau kolom pendaftaran_id ada → isi
if ($hasPendaftaranId) {
    $payload['pendaftaran_id'] = $pend->id;
}

// kalau kolom pendaftaran_pelatihan_id ada → isi juga
if ($hasPendaftaranPelId) {
    $payload['pendaftaran_pelatihan_id'] = $pend->id;
}

// fallback adaptif pakai $fk (kalau masih kamu pakai)
if (!empty($fk)) {
    if ($fk === 'peserta_id') {
        $payload['peserta_id'] = $payload['peserta_id']
            ?? ($pend->peserta_id ?? ($pend->peserta->id ?? null));
    } else {
        $payload[$fk] = $pend->id;
    }
}

if ($hasPesertaId && empty($payload['peserta_id'])) {
    throw new \Exception("peserta_id tidak ditemukan untuk pendaftaran id {$pend->id}");
}

PenempatanAsrama::create($payload);



                $kamar->decrement('available_beds');

                $result['ok']++;
                $result['details'][] = [
                    'pendaftaran_id' => $pend->id,
                    'kamar_id'       => $kamar->id,
                ];
            }

            return $result;
        });
    }

    public function getPenempatanByPendaftaran(int $pendaftaranId)
    {
        return PenempatanAsrama::with('kamar.asrama')
            ->where('pendaftaran_id', $pendaftaranId)
            ->first();
    }
}
