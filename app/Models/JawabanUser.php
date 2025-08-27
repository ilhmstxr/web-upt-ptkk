<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JawabanUser extends Model
{
    /** @use HasFactory<\Database\Factories\TesJawabanUserFactory> */
    use HasFactory;
    protected $table = 'jawaban_users';
    protected $fillable = ['percobaan_id', 'pertanyaan_id', 'opsi_jawaban_id'];

    public function percobaanTes(): BelongsTo
    {
        // Merujuk ke model Tes_Percobaan
        return $this->belongsTo(Percobaan::class, 'percobaan_id');
    }

    public function pertanyaan(): BelongsTo
    {
        // Merujuk ke model Tes_Pertanyaan
        return $this->belongsTo(Pertanyaan::class, 'pertanyaan_id');
    }

    public function opsiJawaban(): BelongsTo
    {
        // Merujuk ke model Tes_OpsiJawaban
        return $this->belongsTo(OpsiJawaban::class, 'opsi_jawaban_id');
    }
}
