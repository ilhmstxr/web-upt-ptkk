<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SorotanPelatihan extends Model
{
    // Kolom yang bisa diisi mass-assignment
    protected $fillable = [
        'title',
        'description',
        'is_published',
    ];

    protected $casts = [
        'is_published' => 'boolean',
    ];

    // Relasi: satu sorotan punya banyak foto
    public function fotos()
    {
        return $this->hasMany(SorotanPelatihanFoto::class);
    }

}
