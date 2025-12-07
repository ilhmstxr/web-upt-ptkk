<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CeritaKami extends Model
{
    protected $table = 'cerita_kamis'; // kalau nama tabel ini beda, sesuaikan

    protected $fillable = [
        'title',        // judul internal (boleh dipakai / boleh diabaikan di landing)
        'slug',
        'image',        // path foto
        'excerpt',      // paragraf pendek untuk landing
        'content',      // isi lengkap (kalau mau)
        'is_published',
        'published_at',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'published_at' => 'datetime',
    ];

    // ✅ accessor untuk dipakai di Blade: $cerita->image_url
    public function getImageUrlAttribute(): ?string
    {
        if (! $this->image) {
            return null;
        }

        // kalau sudah full URL
        if (Str::startsWith($this->image, ['http://', 'https://'])) {
            return $this->image;
        }

        // coba ambil dari storage public
        if (Storage::disk('public')->exists($this->image)) {
            return Storage::url($this->image);
        }

        // fallback terakhir
        return asset($this->image);
    }
}
