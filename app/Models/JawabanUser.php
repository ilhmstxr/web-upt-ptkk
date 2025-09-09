<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JawabanUser extends Model
{
    use HasFactory;

    protected $table = 'jawaban_user';

    protected $fillable = [
        'opsi_jawabans_id', // untuk jawaban pilihan ganda
        'pertanyaan_id',    // pertanyaan terkait
        'percobaan_id',     // percobaan terkait (pre/post test)
        'nilai_jawaban',    // untuk skala likert 1-5
        'jawaban_teks',     // untuk jawaban esai / teks bebas
    ];

    /**
     * Relasi ke percobaan
     */
    public function percobaan()
    {
        return $this->belongsTo(Percobaan::class, 'percobaan_id');
    }

    /**
     * Relasi ke pertanyaan
     */
    public function pertanyaan()
    {
        return $this->belongsTo(Pertanyaan::class, 'pertanyaan_id');
    }

    /**
     * Relasi ke opsi jawaban (jika jawaban pilihan ganda)
     */
    public function opsiJawabans()
    {
        return $this->belongsTo(OpsiJawabans::class, 'opsi_jawabans_id');
    }

    public function pesertaSurvei(){
        return $this->belongsTo(PesertaSurvei::class, 'pesertaSurvei_id');
    }
}
