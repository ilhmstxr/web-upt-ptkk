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
        // 'pesertaSurvei_id' // uncomment jika kolom ada di tabel
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
     * Singular relation to opsi jawaban (most likely used)
     */
    public function opsiJawaban()
    {
        return $this->belongsTo(OpsiJawaban::class, 'opsi_jawaban_id', 'id');
    }

    /**
     * Plural alias kept for backward compatibility if some code uses opsiJawabans()
     */
    public function opsiJawabans()
    {
        return $this->opsiJawaban();
    }

    /**
     * Optional relation to pesertaSurvei (if jawaban_user table stores pesertaSurvei_id)
     */
    public function pesertaSurvei()
    {
        // If PesertaSurvei model exists, use it; otherwise try Peserta
        if (class_exists(\App\Models\PesertaSurvei::class)) {
            return $this->belongsTo(\App\Models\PesertaSurvei::class, 'pesertaSurvei_id', 'id');
        }
        if (class_exists(\App\Models\Peserta::class)) {
            return $this->belongsTo(\App\Models\Peserta::class, 'pesertaSurvei_id', 'id');
        }
        // fallback (will error if neither model exists but method must return relation)
        return $this->belongsTo(\App\Models\PesertaSurvei::class, 'pesertaSurvei_id', 'id');
    }
}
