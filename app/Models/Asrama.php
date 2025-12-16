<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Asrama extends Model
{
    use HasFactory;

    protected $table = 'asramas';

    protected $fillable = [
        'name',
        'alamat',
    ];

    public function kamars(): HasMany
    {
        return $this->hasMany(Kamar::class, 'asrama_id');
    }
    public function penempatanAsramasQuery(): Builder
    {
        return PenempatanAsrama::query()
            ->whereHas('kamarPelatihan.kamar', function (Builder $q) {
                $q->where('asrama_id', $this->getKey());
            });
    }

    public function penghuniAktifQuery(): Builder
    {
        return $this->penempatanAsramasQuery()->penghuniAktif();
    }

    // helper count (DB-based)
    public function totalKamar(): int
    {
        return $this->kamars()->count();
    }

    public function totalBedsGlobal(): int
    {
        return (int) $this->kamars()->sum('total_beds');
    }
}
