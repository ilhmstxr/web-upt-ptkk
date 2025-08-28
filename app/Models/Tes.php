<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Tes extends Model
{
    use HasFactory;

    protected $table = 'tes'; // biar jelas

    protected $fillable = [
        'judul',
        'deskripsi',
        'tipe',
        'sub_tipe',
        'bidang_id',
        'pelatihan_id',
        'durasi_menit',
    ];

    // Relasi ke Bidang
    public function bidang()
    {
        return $this->belongsTo(Bidang::class, 'bidang_id');
    }

    // Relasi ke Pelatihan
    public function pelatihan()
    {
        return $this->belongsTo(Pelatihan::class, 'pelatihan_id');
    }

    // Relasi ke Pertanyaan (kalau ada pivot table tes_pertanyaan / tes_pertanyaan)
    public function pertanyaans()
    {
        return $this->belongsToMany(Pertanyaan::class, 'tes_pertanyaan', 'tes_id', 'pertanyaan_id');
    }
}
