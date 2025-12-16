<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Tes extends Model
{
    use HasFactory;

    protected $table = 'tes';

    protected $fillable = [
        'judul',
        'deskripsi',
        'tipe',
        'sub_tipe',
        'kompetensi_id',
        'pelatihan_id',
        'durasi_menit',
        'tanggal_mulai',
        'tanggal_selesai',
        'passing_score',
    ];

    // Relasi ke Kompetensi
    public function kompetensiPelatihan()
    {
        return $this->belongsTo(KompetensiPelatihan::class, 'kompetensi_pelatihan_id');
    }

    public function pelatihan()
    {
        return $this->belongsTo(Pelatihan::class);
    }

    public function kompetensi()
    {
        return $this->belongsTo(Kompetensi::class);
    }

    public function pertanyaan()
    {
        return $this->hasMany(Pertanyaan::class, 'tes_id');
    }

    public function percobaan()
    {
        return $this->hasMany(Percobaan::class, 'tes_id');
    }
}
