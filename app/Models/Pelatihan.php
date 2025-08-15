<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pelatihan extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'judul',
        'slug',
        'gambar',
        'tanggal_mulai',
        'tanggal_selesai',
        'durasi',
        'waktu',
        'tujuan',
        'target_peserta',
        'materi',
        'galeri',
        'deskripsi',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
        'galeri' => 'array', // Casting kolom JSON ke array PHP
    ];

    /**
     * Get the route key for the model.
     * This allows using the 'slug' instead of 'id' in routes.
     *
     * @return string
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * Get the registrations for the training.
     * Assuming a pelatihan can have many registrations.
     *
     * @return HasMany
     */
    public function registrations(): HasMany
    {
        // Pastikan Anda sudah memiliki model Registration
        return $this->hasMany(Registration::class);
    }
}
