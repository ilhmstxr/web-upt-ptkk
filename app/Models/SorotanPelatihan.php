<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SorotanPelatihan extends Model
{
    protected $table = 'sorotan_pelatihans';

    protected $fillable = [
        'kelas',        // 'mtu', 'reguler', 'akselerasi'
        'title',
        'description',
        'is_published',
        'photos',       // json array path foto
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'photos'       => 'array',
    ];

    public function getPhotoUrlsAttribute(): array
    {
        $photos = $this->photos ?? [];

        return collect($photos)->map(function ($path) {
            if (!$path) {
                return asset('images/placeholder_kunjungan.jpg');
            }

            $p = ltrim($path, '/');
            // asumsi pakai disk public
            return asset('storage/' . $p);
        })->all();
    }
}
