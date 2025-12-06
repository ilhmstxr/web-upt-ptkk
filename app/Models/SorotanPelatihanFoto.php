<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SorotanPelatihanFoto extends Model
{
    // Pastikan tabelnya sesuai migration: 'sorotan_fotos'
    protected $fillable = [
        'sorotan_pelatihan_id',
        'path',
        'order',
    ];

    /**
     * Relasi balik: foto milik satu sorotan.
     *
     * @return BelongsTo
     */
    public function sorotan(): BelongsTo
    {
        return $this->belongsTo(SorotanPelatihan::class, 'sorotan_pelatihan_id');
    }
}

/**
 * Alias compatibility class.
 *
 * Beberapa file sebelumnya menyebut class SorotanPelatihanFoto.
 * Untuk menghindari error "class not found", kita definisikan alias
 * yang extend SorotanFoto — sehingga kedua nama bisa dipakai.
 *
 * Jika kamu tidak butuh kompatibilitas, boleh hapus class alias ini.
 */
if (! class_exists(\App\Models\SorotanPelatihanFoto::class)) {
    class SorotanPelatihanFoto extends SorotanFoto {}
}
