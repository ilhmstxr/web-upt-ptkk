<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SorotanPelatihan extends Model
{
    use HasFactory;

    protected $table = 'sorotan_pelatihans';

    protected $fillable = [
        'kelas',        // 'mtu', 'reguler', 'akselerasi'
        'title',
        'description',
        'photos',
        'is_published',
    ];

    protected $casts = [
        'photos'       => 'array',   // JSON -> array
        'is_published' => 'boolean',
    ];

    // biar di Blade enak: $row->photo_urls
    public function getPhotoUrlsAttribute(): array
    {
        $files = $this->photos ?? [];

        return collect($files)
            ->filter()
            ->map(function ($path) {
                // pastikan string dulu
                if (! is_string($path)) {
                    return null;
                }

                // pakai Str::startsWith (bisa array)
                if (Str::startsWith($path, ['http://', 'https://'])) {
                    return $path;
                }

                // anggap path relatif di disk public
                return Storage::url($path);
            })
            ->filter()   // buang hasil null kalau ada
            ->values()
            ->all();
    }
}
