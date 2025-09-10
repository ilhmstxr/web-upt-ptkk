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
        'bidang_id',
        'pelatihan_id',
        'durasi_menit',
    ];

    // Relasi ke Bidang
    public function bidang()
    {
        return  $this->belongsTo(Bidang::class, 'bidang_id');
    }

    // Relasi ke Pelatihan
    public function pelatihan()
    {
        return $this->belongsTo(Pelatihan::class, 'pelatihan_id');
    }

    // Relasi ke Pertanyaan
    // Kalau tiap pertanyaan punya kolom tes_id, pakai hasMany
    public function pertanyaan()
    {
        return $this->hasMany(Pertanyaan::class, 'tes_id');
    }

    // Jika kamu memang pakai pivot table tes_pertanyaan, gunakan ini:
    /*
    public function pertanyaan()
    {
        return $this->belongsToMany(Pertanyaan::class, 'tes_pertanyaan', 'tes_id', 'pertanyaan_id');
    }
    */
}
