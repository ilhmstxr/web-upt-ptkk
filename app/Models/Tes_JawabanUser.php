<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Tes_JawabanUser extends Model
{
    /** @use HasFactory<\Database\Factories\TesJawabanUserFactory> */
    use HasFactory;
    protected $fillable = ['percobaan_tes_id', 'pertanyaan_id', 'opsi_terpilih_id'];

    public function percobaanTes(): BelongsTo
    {
        // Merujuk ke model Tes_Percobaan
        return $this->belongsTo(Tes_Percobaan::class, 'percobaan_tes_id');
    }

    public function pertanyaan(): BelongsTo
    {
        // Merujuk ke model Tes_Pertanyaan
        return $this->belongsTo(Tes_Pertanyaan::class, 'pertanyaan_id');
    }

    public function opsiJawaban(): BelongsTo
    {
        // Merujuk ke model Tes_OpsiJawaban
        return $this->belongsTo(Tes_OpsiJawaban::class, 'opsi_terpilih_id');
    }
}
