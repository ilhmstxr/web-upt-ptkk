<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SorotanPelatihanFoto extends Model
{
    protected $fillable = [
        'sorotan_pelatihan_id',
        'path',
    ];

    // Relasi balik: foto milik satu sorotan
    public function sorotan()
    {
        return $this->belongsTo(SorotanPelatihan::class);
    }

}
