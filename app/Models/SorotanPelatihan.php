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
        'title',
        'description',
        'photos',        // Foto disimpan sebagai JSON (Array)
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

        if (is_string($files)) {
            $files = json_decode($files, true) ?? [];
        }

        if (!is_array($files)) {
            return [];
        }

        return collect($files)
            ->filter()
            ->map(function ($path) {
                if (!is_string($path)) {
                    return null;
                }

                if (Str::startsWith($path, ['http://', 'https://'])) {
                    return $path;
                }

                return Storage::url($path);
            })
            ->filter() 
            ->values()
            ->all();
    }
}