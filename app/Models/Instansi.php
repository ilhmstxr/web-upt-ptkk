<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Instansi extends Model
{
    use HasFactory;

    protected $table = 'instansis';

    protected $fillable = [
        'asal_instansi',
        'alamat_instansi',
        'bidang_keahlian',
        'kelas',
        'cabang_dinas_wilayah', 
    ];

    public function cabangDinas()
{
    return $this->belongsTo(CabangDinas::class, 'cabang_dinas_wilayah');
}

    public function pesertas(): HasMany
    {
        return $this->hasMany(Peserta::class, 'instansi_id');
    }
}
