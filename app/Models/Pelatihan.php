<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pelatihan extends Model
{
    use HasFactory;

    protected $table = 'pelatihan';

    protected $guarded = [
        'nama_pelatihan',
        'jenis_program',
        'slug',
        'gambar',
        'status',
        'tanggal_mulai',
        'tanggal_selesai',
        'deskripsi',
    ];


    public function instansi()
    {
        return $this->belongsTo(Instansi::class);
    }

    public function peserta()
    {
        return $this->hasMany(Peserta::class);
    }

    
}
