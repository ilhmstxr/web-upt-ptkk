<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KamarPelatihan extends Model
{
    use HasFactory;

    protected $table = 'kamar_pelatihans';

    protected $fillable = [
        'kamar_id',
        'pelatihan_id',
        'available_beds',
        'is_active',
    ];

    protected $casts = [
        'available_beds' => 'integer',
        'is_active'      => 'boolean',
    ];


    public function kamar(): BelongsTo
    {
        return $this->belongsTo(Kamar::class, 'kamar_id');
    }

    public function pelatihan(): BelongsTo
    {
        return $this->belongsTo(Pelatihan::class, 'pelatihan_id');
    }

    public function penempatanAsramas(): HasMany
    {
        return $this->hasMany(PenempatanAsrama::class, 'kamar_pelatihan_id');
    }
}
