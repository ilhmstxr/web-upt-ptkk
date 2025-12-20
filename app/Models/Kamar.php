<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Kamar extends Model
{
    use HasFactory;

    protected $table = 'kamars';

    protected $fillable = [
        'asrama_id',
        'nomor_kamar',
        'lantai',
        'total_beds',
        'available_beds',
        'status',
        'is_active',
    ];

    protected $casts = [
        'is_active'   => 'boolean',
        'nomor_kamar' => 'integer',
        'lantai'      => 'integer',
        'total_beds'  => 'integer',
        'available_beds' => 'integer',
    ];

    public function asrama(): BelongsTo
    {
        return $this->belongsTo(Asrama::class, 'asrama_id');
    }

    public function kamarPelatihans(): HasMany
    {
        return $this->hasMany(KamarPelatihan::class, 'kamar_id');
    }

    public function pelatihans(): BelongsToMany
    {
        return $this->belongsToMany(
                Pelatihan::class,
                'kamar_pelatihans',
                'kamar_id',
                'pelatihan_id'
            )
            ->withPivot(['id', 'available_beds', 'is_active'])
            ->withTimestamps();
    }
}
