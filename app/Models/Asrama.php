<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

use App\Models\Pelatihan;
use App\Models\Kamar;
use App\Services\AsramaAllocator;

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

    public function kamars(): HasMany
    {
        return $this->hasMany(Kamar::class, 'asrama_id');
    }

    /**
     * ✅ Sync Asrama + Kamar dari config/session ke DB PER PELATIHAN.
     *
     * - Kalau $pelatihanId diisi → sync hanya pelatihan itu.
     * - Kalau null → sync semua pelatihan (biar AsramaResource ngga error).
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
                $allocator->generateRoomsFromConfig($pid, $kamarConfig);
            }
            return;
        }

        // sync hanya pelatihan tertentu
        $allocator->generateRoomsFromConfig($pelatihanId, $kamarConfig);
    }
}
