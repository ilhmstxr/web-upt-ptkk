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
        // 'pesertaSurvei_id' // uncomment kalau kolom memang ada
    ];

    // -------------------------
    // RELATIONS
    // -------------------------

    public function percobaan()
    {
        return $this->belongsTo(Percobaan::class, 'percobaan_id', 'id');
    }

    public function pertanyaan()
    {
        return $this->belongsTo(Pertanyaan::class, 'pertanyaan_id', 'id');
    }

    /**
     * Singular relation → benar, karena 1 jawaban_user
     * hanya bisa punya 1 opsi jawaban.
     */
    public function opsiJawaban()
    {
        return $this->belongsTo(OpsiJawaban::class, 'opsi_jawaban_id', 'id');
    }

    /**
     * Ini agak rancu. 
     * Plural biasanya untuk hasMany, 
     * jadi kalau `opsiJawabans()` hanya memanggil `opsiJawaban()`,
     * sebaiknya dihapus biar nggak bikin bingung.
     */
    public function opsiJawabans()
    {
        return $this->opsiJawaban();
    }

    /**
     * Relasi opsional ke peserta survei.
     * Ini oke kalau memang ada kolom `pesertaSurvei_id`.
     */
    public function pesertaSurvei()
    {
        return $this->belongsTo(\App\Models\PesertaSurvei::class, 'pesertaSurvei_id', 'id');
    }
}
