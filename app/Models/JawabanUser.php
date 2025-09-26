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
        // TIDAK perlu peserta_id di sini
    ];

    // --- RELATIONS ---

    public function percobaan()
    {
        return $this->belongsTo(Percobaan::class, 'percobaan_id', 'id');
    }

    public function pertanyaan()
    {
        return $this->belongsTo(Pertanyaan::class, 'pertanyaan_id', 'id');
    }

    public function opsiJawaban()
    {
        return $this->belongsTo(OpsiJawaban::class, 'opsi_jawaban_id', 'id');
    }

    // Hapus ini karena membingungkan (plural tapi relasi belongsTo)
    // public function opsiJawabans() { return $this->opsiJawaban(); }

    // Hapus relasi pesertaSurvei lama karena sudah diganti arsitekturnya
    // public function pesertaSurvei() { ... }
}
