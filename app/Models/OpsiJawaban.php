<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OpsiJawaban extends Model
{
    /** @use HasFactory<\Database\Factories\TesOpsiJawabanFactory> */
    use HasFactory;
    protected $table = 'opsi_jawabans';
    protected $fillable = ['pertanyaaan_id', 'teks_opsi', 'apakah_benar','gambar'];

    public function pertanyaan(): BelongsTo
    {
        // Merujuk ke model Tes_Pertanyaan
        return $this->belongsTo(Pertanyaan::class, 'pertanyaan_id');
    }
}
