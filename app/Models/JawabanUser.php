<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JawabanUser extends Model
{
    use HasFactory;

    protected $table = 'jawaban_user';

    protected $fillable = [
        'opsi_jawaban_id',
        'pertanyaan_id',
        'percobaan_id',
        'nilai_jawaban',
        'jawaban_teks',
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
     * Relasi ke Percobaan
     */
    public function percobaan()
    {
        return $this->belongsTo(Percobaan::class);
    }

    public function tes()
{
    return $this->belongsTo(\App\Models\Tes::class, 'tes_id');
}

}
