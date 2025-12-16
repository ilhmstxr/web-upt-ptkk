<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;


class PenempatanAsrama extends Model
{
    use HasFactory;

    protected $table = 'penempatan_asramas';

    protected $fillable = [
        'peserta_id',
        'pelatihan_id',
        'kamar_pelatihan_id',
        'gender',
    ];

    protected $casts = [
        'peserta_id'         => 'integer',
        'pelatihan_id'       => 'integer',
        'kamar_pelatihan_id' => 'integer',
    ];

    public function peserta(): BelongsTo
    {
        return $this->belongsTo(Peserta::class);
    }

    public function pelatihan(): BelongsTo
    {
        return $this->belongsTo(Pelatihan::class);
    }

    /**
     * RELASI UTAMA (SATU-SATUNYA)
     */
    public function kamarPelatihan(): BelongsTo
    {
        return $this->belongsTo(KamarPelatihan::class);
    }

    public function scopePenghuniAktif(Builder $query): Builder
    {
        return $query->whereNotNull('kamar_pelatihan_id');
    }
}
