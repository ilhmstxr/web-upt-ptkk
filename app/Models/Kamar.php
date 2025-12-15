<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Kamar extends Model
{
    use HasFactory;

    protected $table = 'kamars'; // ✅ sesuai migration kamu

    protected $fillable = [
        'asrama_id',
        'nomor_kamar',
        'total_beds',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'nomor_kamar' => 'integer',
        'total_beds' => 'integer',
    ];

    public function asrama(): BelongsTo
    {
        return $this->belongsTo(Asrama::class, 'asrama_id');
    }

    /**
     * Relasi kamar dipakai di banyak pelatihan (pivot: kamar_pelatihan)
     * available_beds dan is_active per pelatihan disimpan di pivot.
     */
    public function pelatihans(): BelongsToMany
    {
        return $this->belongsToMany(
            Pelatihan::class,
            'kamar_pelatihan',
            'kamar_id',
            'pelatihan_id'
        )
            ->withPivot(['id', 'available_beds', 'is_active'])
            ->withTimestamps();
    }
}
