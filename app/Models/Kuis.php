<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kuis extends Model
{
    use HasFactory;

    protected $table = 'kuis'; // atau 'kuises' kalau pakai default plural Laravel

    protected $fillable = [
        'nama_tes',
        'deskripsi',
        'tanggal_mulai',
        'tanggal_selesai',
        'status',
    ];

    // Relasi Many-to-Many ke Pertanyaan (via pivot pertanyaan_tes)
    public function pertanyaans()
    {
        return $this->belongsToMany(Pertanyaan::class, 'pertanyaan_tes', 'kuis_id', 'pertanyaan_id');
    }

    // Relasi ke percobaan
    public function percobaans()
    {
        return $this->hasMany(Percobaan::class, 'kuis_id');
    }
}
