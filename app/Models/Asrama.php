<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\Relation;

class Asrama extends Model
{
    use HasFactory;

    // Ditambahkan: Menentukan nama tabel secara eksplisit (dari snippet Anda)
    protected $table = 'asrama';

    protected $fillable = [
        'nama',
        'gender',
        'kapasitas', // Dipertahankan dari versi sebelumnya
        'kontak_pic', // Dipertahankan dari versi sebelumnya
        'alamat',
        'is_active', // Dipertahankan dari versi sebelumnya
    ];

    /**
     * Relasi One-to-Many: Asrama memiliki banyak Pelatihan.
     * Diasumsikan tabel 'pelatihans' memiliki kolom 'asrama_id'.
     */
    public function pelatihans(): HasMany
    {
        return $this->hasMany(Pelatihan::class);
    }

    /**
     * Relasi One-to-Many: Asrama memiliki banyak Kamar.
     * Menggunakan kunci asing eksplisit 'asrama_id' (dari snippet Anda).
     */
    public function kamars(): HasMany
    {
        return $this->hasMany(Kamar::class, 'asrama_id');
    }

    /**
     * Relasi HasManyThrough: Mendapatkan semua penghuni yang saat ini berada di kamar asrama ini
     * melalui model Kamar (dari snippet Anda).
     */
    public function penghuni(): HasManyThrough
    {
        return $this->hasManyThrough(
            PenempatanAsrama::class, // Model akhir
            Kamar::class,            // Model perantara
            'asrama_id',             // Foreign key pada model perantara (Kamar)
            'kamar_id',              // Foreign key pada model akhir (PenempatanAsrama)
            'id',                    // Local key pada model ini (Asrama)
            'id'                     // Local key pada model perantara (Kamar)
        );
    }

    /**
     * Scope relasi untuk mendapatkan penghuni yang statusnya masih aktif (belum check-out).
     */
    public function penghuniAktif(): Relation
    {
        // Menggunakan relasi penghuni() dan menambahkan kondisi 'checkout_at' null.
        return $this->penghuni()->whereNull('checkout_at');
    }

    /**
     * Atribut untuk menghitung ketersediaan (Opsional, tapi berguna).
     */
    public function getKetersediaanAttribute(): int
    {
        // Contoh sederhana: Kapasitas - Jumlah penghuni saat ini
        // (Anda mungkin perlu menyesuaikan ini berdasarkan model penghuni/peserta)
        return $this->kapasitas - $this->kamars()->sum('jumlah_penghuni');
    }
}