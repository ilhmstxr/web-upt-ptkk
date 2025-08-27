<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tes extends Model
{
    /** @use HasFactory<\Database\Factories\TesFactory> */
    use HasFactory;
    protected $fillable = ['judul', 'tipe', 'bidang', 'pelatihan', 'durasi_menit'];
    public function pertanyaan(): BelongsToMany
    {
        return $this->belongsToMany(Tes_Pertanyaan::class, 'pertanyaan_tes', 'tes_id', 'pertanyaan_id');
    }

    public function percobaanTes(): HasMany
    {
        return $this->hasMany(Tes_Percobaan::class, 'tes_id');
    }
}
