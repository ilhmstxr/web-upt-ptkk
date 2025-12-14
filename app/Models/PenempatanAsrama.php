<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
        return $this->belongsTo(Peserta::class, 'peserta_id');
    }

    public function pelatihan(): BelongsTo
    {
        return $this->belongsTo(Pelatihan::class, 'pelatihan_id');
    }

    public function kamarPelatihan(): BelongsTo
    {
        return $this->belongsTo(KamarPelatihan::class, 'kamar_pelatihan_id');
    }

    public function scopePenghuniAktif(Builder $query): Builder
    {
        return $query->whereHas('pelatihan', fn (Builder $q) => $q->where('status', 'aktif'));
    }
}
