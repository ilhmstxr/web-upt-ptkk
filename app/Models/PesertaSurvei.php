<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PesertaSurvei extends Model
{
    use HasFactory;

    protected $table = 'peserta_survei';

    protected $fillable = [
        'nama',
        'pelatihan_id',
        'kompetensi_id',
        'tes_id',
    ];

    /**
     * Relasi ke jawaban user
     */
    public function jawabanUsers()
    {
        return $this->hasMany(JawabanUser::class, 'peserta_survei_id');
    }

    /**
     * Relasi ke percobaan
     */
    public function percobaan()
    {
        return $this->hasMany(Percobaan::class, 'peserta_survei_id');
    }

    /**
     * Relasi ke pelatihan
     */
    public function pelatihan()
    {
        return $this->belongsTo(Pelatihan::class, 'pelatihan_id');
    }

    /**
     * Relasi ke kompetensi
     */
    public function kompetensi()
    {
        return $this->belongsTo(Kompetensi::class, 'kompetensi_id');
    }

    /**
     * Relasi opsional ke tes
     */
    public function tes()
    {
        return $this->belongsTo(Tes::class, 'tes_id');
    }
}
