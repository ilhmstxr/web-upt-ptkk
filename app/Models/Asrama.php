<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;

class Asrama extends Model
{
    use HasFactory;

    protected $table = 'asrama';

    protected $fillable = [
        'pelatihan_id',
        'name',
        'gender',
        'total_kamar',
    ];

    public function kamars()
    {
        return $this->hasMany(Kamar::class, 'asrama_id');
    }

    /**
     * Sync Asrama + Kamar dari config/session ke DB.
     * Dipakai Filament sebelum list tampil.
     *
     * Struktur config kamar:
     * [
     *   "Mawar" => [
     *      ["no"=>1,"bed"=>4], ...
     *   ],
     *   "Melati Bawah" => [...]
     * ]
     */
    public static function syncFromConfig(?int $pelatihanId = null, ?array $kamarConfig = null): void
    {
        $pelatihanId = $pelatihanId ?? session('pelatihan_id');
        if (!$pelatihanId) return;

        $kamarConfig = $kamarConfig ?? (session('kamar') ?? config('kamar'));
        if (!$kamarConfig || !is_array($kamarConfig)) return;

        DB::transaction(function () use ($pelatihanId, $kamarConfig) {

            foreach ($kamarConfig as $blok => $rooms) {

                // tentukan gender blok (sesuaikan aturan kamu)
                $gender = in_array($blok, ['Melati Bawah', 'Tulip Bawah'])
                    ? 'Laki-laki'
                    : 'Perempuan';

                // create / update asrama per pelatihan
                $asrama = self::updateOrCreate(
                    [
                        'pelatihan_id' => $pelatihanId,
                        'name'         => $blok,
                    ],
                    [
                        'gender'      => $gender,
                        'total_kamar' => count($rooms),
                    ]
                );

                // sync kamar
                foreach ($rooms as $r) {
                    $bed = $r['bed'];

                    // kalau rusak / null -> skip allocator tapi kamar tetap ada di DB biar kebaca denah
                    $bedsNumeric = is_numeric($bed) ? (int)$bed : 0;

                    Kamar::updateOrCreate(
                        [
                            'asrama_id'    => $asrama->id,
                            'nomor_kamar'  => (string)$r['no'],
                        ],
                        [
                            'total_beds'     => $bedsNumeric,
                            'available_beds' => $bedsNumeric,
                            'status'         => is_numeric($bed) ? 'Aktif' : ( $bed === 'rusak' ? 'Rusak' : 'Perbaikan'),
                        ]
                    );
                }
            }
        });
    }
}
