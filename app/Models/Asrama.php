<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

use App\Models\Pelatihan;
use App\Models\Kamar;
use App\Services\AsramaAllocator;

class Asrama extends Model
{
    use HasFactory;

    /**
     * Nama tabel sesuai yang kamu pakai.
     */
    protected $table = 'asrama';

    /**
     * Kolom yang boleh diisi.
     */
    protected $fillable = [
        'pelatihan_id',
        'name',
        'gender',
        'total_kamar',
    ];

    /**
     * RELATION: 1 Asrama milik 1 Pelatihan
     * FK: asrama.pelatihan_id -> pelatihan.id
     */
    public function pelatihan(): BelongsTo
    {
        return $this->belongsTo(Pelatihan::class, 'pelatihan_id', 'id');
    }

    /**
     * RELATION: 1 Asrama punya banyak Kamar
     * FK: kamar.asrama_id -> asrama.id
     */
    public function kamars(): HasMany
    {
        return $this->hasMany(Kamar::class, 'asrama_id', 'id');
    }

    /**
     * Sync Asrama + Kamar dari config/session ke DB per pelatihan.
     *
     * - Kalau $pelatihanId diisi → sync hanya pelatihan itu.
     * - Kalau null → sync semua pelatihan (biar resource aman).
     *
     * sumber data:
     * - session('kamar') kalau admin pernah ubah
     * - fallback config('kamar')
     */
    public static function syncFromConfig(?int $pelatihanId = null, ?array $kamarConfig = null): void
    {
        $allocator = app(AsramaAllocator::class);
        $kamarConfig = $kamarConfig ?? (session('kamar') ?? config('kamar'));

        if (!is_array($kamarConfig)) {
            return;
        }

        // kalau tidak dikasih pelatihan → sync semua pelatihan
        if (!$pelatihanId) {
            $pelatihanIds = Pelatihan::query()->pluck('id');
            foreach ($pelatihanIds as $pid) {
                $allocator->generateRoomsFromConfig((int) $pid, $kamarConfig);
            }
            return;
        }

        // sync hanya pelatihan tertentu
        $allocator->generateRoomsFromConfig($pelatihanId, $kamarConfig);
    }
}
