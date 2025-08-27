<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Tes_OpsiJawaban extends Model
{
    /** @use HasFactory<\Database\Factories\TesOpsiJawabanFactory> */
    use HasFactory;
    protected $fillable = ['pertanyaaan_id', 'teks_opsi', 'apakah_benar'];

    public function pertanyaan(): BelongsTo
    {
        // Merujuk ke model Tes_Pertanyaan
        return $this->belongsTo(Tes_Pertanyaan::class, 'pertanyaan_id');
    }
}
