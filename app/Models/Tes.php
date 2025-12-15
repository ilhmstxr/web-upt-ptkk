<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tes extends Model
{
    use HasFactory;

    protected $table = 'tes';

    protected $fillable = [
        'judul',
        'deskripsi',
        'tipe',
        'kompetensi_pelatihan_id',
        'pelatihan_id',
        'durasi_menit',
        'tanggal_mulai',
        'tanggal_selesai',
    ];

    public function kompetensiPelatihan()
    {
        return $this->belongsTo(Kompetensi::class, 'kompetensi_id');
    }

    public function pelatihan()
    {
        return $this->belongsTo(Pelatihan::class, 'pelatihan_id');
    }

    /**
     * Semua pertanyaan ada di tabel pertanyaan (one-to-many).
     * Pre/post: kelompok_pertanyaan_id NULL
     * Survei  : masuk kelompok
     */
    public function pertanyaan()
    {
        return $this->hasMany(Pertanyaan::class, 'tes_id');
    }

    public function percobaan()
    {
        return $this->hasMany(Percobaan::class, 'tes_id');
    }

    public function tipeTes()
    {
        return $this->hasMany(TipeTes::class, 'tes_id');
    }
}
