<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tes_Percobaan extends Model
{
    /** @use HasFactory<\Database\Factories\TesPercobaanFactory> */
    use HasFactory;
    protected $fillable = ['pengguna_id', 'tes_id', 'waktu_mulai', 'waktu_selesai', 'skor'];

    public function pengguna(): BelongsTo
    {
        return $this->belongsTo(Peserta::class, 'pengguna_id');
    }

    public function tes(): BelongsTo
    {
        return $this->belongsTo(Tes::class, 'tes_id');
    }

    public function jawabanPengguna(): HasMany
    {
        // Merujuk ke model Tes_JawabanUser
        return $this->hasMany(Tes_JawabanUser::class, 'percobaan_tes_id');
    }
}
