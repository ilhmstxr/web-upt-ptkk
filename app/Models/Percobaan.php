<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Percobaan extends Model
{
    /** @use HasFactory<\Database\Factories\TesPercobaanFactory> */
    use HasFactory;
    protected $table = 'percobaans';
    protected $fillable = ['peserta_id', 'kuis_id', 'waktu_mulai', 'waktu_selesai', 'skor', 'pesan_kesan'];

    public function pengguna(): BelongsTo
    {
        return $this->belongsTo(Peserta::class, 'peserta_id');
    }

    public function tes(): BelongsTo
    {
        return $this->belongsTo(Kuis::class, 'kuis_id');
    }

    public function jawabanUser(): HasMany
    {
        // Merujuk ke model Tes_JawabanUser
        return $this->hasMany(JawabanUser::class, 'percobaan_id');
    }
}
