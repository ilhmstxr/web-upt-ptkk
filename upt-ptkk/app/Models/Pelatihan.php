<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pelatihan extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_pelatihan',
        'tanggal_mulai',
        'tanggal_selesai',
    ];

    public function pesertas(): HasMany
    {
        return $this->hasMany(Peserta::class);
    }
}
