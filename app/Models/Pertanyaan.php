<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pertanyaan extends Model
{
    /** @use HasFactory<\Database\Factories\TesPertanyaanFactory> */
    use HasFactory;
    protected $fillable = ['nomor', 'teks_pertanyaan', 'tipe_jawaban', 'kuis_id','gambar'];

    public function kuis(): BelongsToMany
    {
        // Merujuk ke model Tes
        return $this->belongsToMany(Kuis::class, 'kuis_id');
    }

    public function opsiJawaban(): HasMany
    {
        // Merujuk ke model Tes_OpsiJawaban
        return $this->hasMany(OpsiJawaban::class, 'pertanyaan_id');
    }
}
