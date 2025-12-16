<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tes extends Model
{
    use HasFactory;

    protected $table = 'tes';

    protected $fillable = [
        'judul',
        'deskripsi',
        'tipe',
        'kompetensi_pelatihan_id',
        'pelatihan_id',
        'durasi_menit',
        'tanggal_mulai',
        'tanggal_selesai',
    ];

    public function kompetensiPelatihan(): BelongsTo
    {
        return $this->belongsTo(KompetensiPelatihan::class, 'kompetensi_pelatihan_id');
    }

    public function pelatihan(): BelongsTo
    {
        return $this->belongsTo(Pelatihan::class, 'pelatihan_id');
    }

    public function pertanyaan(): HasMany
    {
        return $this->hasMany(Pertanyaan::class, 'tes_id');
    }

    public function percobaans(): HasMany
    {
        return $this->hasMany(Percobaan::class, 'tes_id');
    }

    public function tipeTes(): HasMany
    {
        return $this->hasMany(TipeTes::class, 'tes_id');
    }
}
