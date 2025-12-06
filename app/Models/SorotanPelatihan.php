<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SorotanPelatihan extends Model
{
    // Nama tabel default: sorotan_pelatihans (sesuaikan jika berbeda)
    protected $fillable = [
        'title',
        'description',
        'is_published',
    ];

    protected $casts = [
        'is_published' => 'boolean',
    ];

    /**
     * Relasi: satu SorotanPelatihan punya banyak foto.
     * Urutkan berdasarkan kolom 'order' jika ada.
     *
     * @return HasMany
     */
    public function fotos(): HasMany
    {
        return $this->hasMany(SorotanFoto::class, 'sorotan_pelatihan_id')->orderBy('order');
    }
}
