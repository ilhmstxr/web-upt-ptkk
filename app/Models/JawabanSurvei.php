<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JawabanSurvei extends Model
{
    use HasFactory;

    protected $table = 'jawaban_survei';

    protected $fillable = [
        'nama',
        'email',
        'jawaban',
        'pertanyaan_id',
        'opsi_jawaban_id',
        'peserta_id',
    ];

    /**
     * Relasi ke Pertanyaan
     */
    public function pertanyaan()
    {
        return $this->belongsTo(Pertanyaan::class);
    }

    /**
     * Relasi ke OpsiJawaban
     */
    public function opsiJawaban()
    {
        return $this->belongsTo(OpsiJawaban::class);
    }

    /**
     * Relasi ke Peserta
     */
    public function peserta()
    {
        return $this->belongsTo(Peserta::class);
    }
}
