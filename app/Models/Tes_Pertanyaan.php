<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tes_Pertanyaan extends Model
{
    /** @use HasFactory<\Database\Factories\TesPertanyaanFactory> */
    use HasFactory;
    protected $fillable = ['teks_pertanyaan'];

    public function tes(): BelongsToMany
    {
        // Merujuk ke model Tes
        return $this->belongsToMany(Tes::class, 'pertanyaan_tes', 'pertanyaan_id', 'tes_id');
    }

    public function opsiJawaban(): HasMany
    {
        // Merujuk ke model Tes_OpsiJawaban
        return $this->hasMany(Tes_OpsiJawaban::class, 'pertanyaan_id');
    }
}
