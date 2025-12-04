<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Banner extends Model
{
    protected $fillable = [
        'image',
        'title',
        'description',
        'is_active',
        'is_featured',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Accessor: image_url
     * Penggunaan di blade: {{ $banner->image_url }}
     *
     * Logika:
     * - jika kolom image kosong -> fallback ke public/images/beranda/slide1.jpg
     * - jika string adalah URL penuh -> dikembalikan langsung
     * - cek Storage::disk('public') (storage/app/public)
     * - cek public/storage/<path> (link storage)
     * - cek public/<path>
     * - fallback terakhir -> asset('images/beranda/slide1.jpg')
     */
    public function getImageUrlAttribute(): string
    {
        // fallback jika kosong
        if (empty($this->image)) {
            return asset('images/beranda/slide1.jpg');
        }

        // jika sudah URL lengkap
        if (preg_match('/^https?:\\/\\//i', $this->image)) {
            return $this->image;
        }

        // normalisasi (hapus leading "public/" bila ada)
        $normalized = preg_replace('#^public\/+#i', '', $this->image);

        // 1) cek Storage disk('public') (storage/app/public)
        try {
            if (!empty($normalized) && Storage::disk('public')->exists($normalized)) {
                return Storage::disk('public')->url($normalized); // -> /storage/...
            }
        } catch (\Throwable $e) {
            // abaikan error disk
        }

        // 2) cek public/storage/<normalized> (symlink)
        if (!empty($normalized) && file_exists(public_path('storage/' . $normalized))) {
            return asset('storage/' . $normalized);
        }

        // 3) cek public/<normalized> (jika file langsung di public/)
        if (!empty($normalized) && file_exists(public_path($normalized))) {
            return asset($normalized);
        }

        // 4) coba gabungkan ke folder images/ jika path mengandung subfolder
        if (!empty($normalized) && strpos($normalized, '/') !== false) {
            $maybe = 'images/' . ltrim($normalized, '/');
            if (file_exists(public_path($maybe))) {
                return asset($maybe);
            }
        }

        // fallback terakhir
        return asset('images/beranda/slide1.jpg');
    }
}
