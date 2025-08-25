<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jawaban extends Model
{
    /** @use HasFactory<\Database\Factories\JawabanFactory> */
    use HasFactory;

    protected $fillable = [
        'peserta_id', // Diubah dari participant_id
        'pertanyaan_id',
        'value',
    ];

    public function peserta() // Diubah dari participant()
    {
        return $this->belongsTo(Peserta::class);
    }

    public function pertanyaan()
    {
        return $this->belongsTo(Pertanyaan::class);
    }
}
