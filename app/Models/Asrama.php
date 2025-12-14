<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Asrama extends Model
{
    use HasFactory;

    protected $table = 'asrama';

    protected $fillable = [
        'pelatihan_id', // legacy / opsional
        'name',
        'gender',
    ];

    /* =====================
     * RELATIONS
     * ===================== */

    public function pelatihan(): BelongsTo
    {
        return $this->belongsTo(Pelatihan::class);
    }

    public function kamars(): HasMany
    {
        return $this->hasMany(Kamar::class);
    }

    /* =====================
     * DB-BASED COUNTER ✅
     * ===================== */

    public function totalKamar(): int
    {
        return $this->kamars()->count();
    }

    public function totalBeds(): int
    {
        return $this->kamars()->sum('total_beds');
    }

    public function totalBedTersedia(): int
    {
        return $this->kamars()->sum('available_beds');
    }

    public function totalKamarNonaktif(): int
    {
        return $this->kamars()->where('is_active', false)->count();
    }
}
