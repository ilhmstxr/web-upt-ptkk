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
        // 'tes_id', // <-- UNCOMMENT kalau kolom tes_id benar-benar ada di tabel
    ];

    protected $casts = [
        'nilai_jawaban' => 'float',
    ];

    /**
     * Relasi ke Pertanyaan
     */
    public function pertanyaan()
    {
        return $this->belongsTo(Pertanyaan::class, 'pertanyaan_id');
    }

    /**
     * Relasi ke OpsiJawaban
     */
    public function opsiJawaban()
    {
        return $this->belongsTo(OpsiJawaban::class, 'opsi_jawaban_id');
    }

    /**
     * Relasi ke Percobaan (attempt pengerjaan tes)
     */
    public function percobaan()
    {
        return $this->belongsTo(Percobaan::class, 'percobaan_id');
    }

    /**
     * (Opsional) Relasi langsung ke Tes, hanya kalau kolom tes_id ada di tabel jawaban_user
     */
    public function tes()
    {
        return $this->belongsTo(\App\Models\Tes::class, 'tes_id');
    }

    /**
     * Helper: apakah jawaban ini benar?
     * - True kalau opsi terkait ditandai apakah_benar = 1
     * - False kalau tidak ada opsi atau salah
     */
    public function getIsBenarAttribute(): bool
    {
        return (bool) optional($this->opsiJawaban)->apakah_benar;
    }
}
