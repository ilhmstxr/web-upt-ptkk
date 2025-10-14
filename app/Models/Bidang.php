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
    ];

    // 🔹 Relasi many-to-many ke Pelatihan melalui pivot table bidang_pelatihan
    public function pelatihans()
    {
        return $this->belongsToMany(
            Pelatihan::class,       // Model yang dihubungkan
            'bidang_pelatihan',     // Nama tabel pivot
            'bidang_id',            // Foreign key di tabel pivot untuk model ini (Bidang)
            'pelatihan_id'          // Foreign key di tabel pivot untuk model Pelatihan
        );
    }

    public function pelatihan(){
        return $this->belongsToMany(Pelatihan::class, 'bidang_pelatihan');
    }
}
