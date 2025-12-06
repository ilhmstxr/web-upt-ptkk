<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bidang extends Model
{
    use HasFactory;

    protected $table = 'bidang';

    protected $fillable = [
        'nama_bidang',
        'deskripsi',
        'kode',
        'kelas_keterampilan', // 0 = MJC, 1 = Keterampilan & Teknik
        'gambar',
    ];

    // Cast ke boolean agar kondisi di Frontend lebih konsisten
    protected $casts = [
        'kelas_keterampilan' => 'boolean',
    ];

    public function bidangPelatihan()
    {
        return $this->hasMany(BidangPelatihan::class);
    }
}
