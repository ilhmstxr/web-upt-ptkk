<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CeritaKami extends Model
{
    protected $table = 'cerita_kamis'; // kalau nama tabel ini beda, sesuaikan

    protected $fillable = [
        'image',
        'slug',
        'content',
    ];

    // ✅ accessor untuk dipakai di Blade: $cerita->image_url
    public function getImageUrlAttribute(): ?string
    {
        if ($this->image) {
            // Jika link dari internet (https://...), langsung kembalikan
            if (Str::startsWith($this->image, ['http://', 'https://'])) {
                return $this->image;
            }
            // Jika file upload, bungkus dengan Storage::url
            return Storage::url($this->image);
        }
        
        // Fallback: Gambar default jika kosong (sesuaikan path asset kamu)
        return asset('images/cerita-kami.svg');
    }
}
