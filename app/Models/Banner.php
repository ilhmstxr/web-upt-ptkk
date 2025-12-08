<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Throwable; // <-- PENTING: Tambahkan ini agar try/catch berfungsi!

class Banner extends Model
{
    protected $table = 'banners'; // Tambahkan deklarasi tabel (Opsional, tapi praktik yang baik)

    protected $fillable = [
        'image',
        'is_active',
        'is_featured',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
    ];

    protected static function booted()
    {
        static::saving(function ($banner) {
            if ($banner->is_featured) {
                // Reset semua banner lain jadi tidak featured
                Banner::where('id', '!=', $banner->id)
                    ->update(['is_featured' => false]);
            }
        });
    }

}
