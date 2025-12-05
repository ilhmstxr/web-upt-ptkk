<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Throwable; // <-- PENTING: Tambahkan ini agar try/catch berfungsi!

class Banner extends Model
{
    protected $table = 'banners'; // Tambahkan deklarasi tabel (Opsional, tapi praktik yang baik)
    
    /**
     * The attributes that are mass assignable.
     * Termasuk 'is_featured' untuk kompatibilitas dengan form dan migrasi.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'image',
        'title',
        'description',
        'is_active',
        'is_featured',
        'sort_order',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Accessor: image_url
     * Menghasilkan URL gambar yang disederhanakan dan andal, dengan beberapa tingkat fallback.
     *
     * @return string
     */
    public function getImageUrlAttribute(): string
    {
        // Tentukan Fallback
        // Ganti 'images/fallback.jpg' dengan path gambar default Anda yang PASTI ada di folder public/images/
        $defaultFallback = asset('images/fallback.jpg'); 

        // 1. Cek jika kolom 'image' kosong di database
        if (empty($this->image)) {
            return $defaultFallback;
        }

        $path = $this->image;

        // 2. Jika sudah URL lengkap (http/https), kembalikan langsung
        if (filter_var($path, FILTER_VALIDATE_URL)) {
            return $path;
        }
        
        // 3. Normalisasi path: hapus awalan 'public/' jika ada 
        if (str_starts_with($path, 'public/')) {
            $path = str_replace('public/', '', $path);
        }

        // 4. Cek dan hasilkan URL dari Storage disk 'public'
        try {
            // Kita harus memastikan file ada sebelum memanggil Storage::url()
            if (Storage::disk('public')->exists($path)) {
                return Storage::disk('public')->url($path);
            }
        } catch (Throwable $e) {
            // Jika ada masalah I/O atau disk, kita abaikan dan lanjut ke fallback
        }

        // 5. Fallback Akhir: Coba ambil dari folder public/ langsung (seperti aset statis)
        // Ini adalah fallback kedua terbaik jika symlink Storage gagal, tapi file ada di public/
        if (file_exists(public_path($this->image))) {
             return asset($this->image);
        }
        
        // Final fallback jika file tidak ditemukan
        return $defaultFallback;
    }
}