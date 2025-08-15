<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pelatihan extends Model
{
    use HasFactory;

    protected $table = 'pelatihans';

    protected $fillable = [
        'nama_pelatihan',
        'deskripsi',
        'tanggal_mulai',
        'tanggal_selesai',
    ];

    protected $casts = [
    'tanggal_mulai' => 'date',
    'tanggal_selesai' => 'date',
    ];

    public function pesertas(): HasMany
    {
        return $this->hasMany(Peserta::class, 'pelatihan_id');
    }
}
