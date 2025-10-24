<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bidang extends Model
{
    use HasFactory;

    protected $table = 'bidang';

    protected $fillable = [
        'nama_bidang',
        'deskripsi',
    ];

    public function bidangPelatihan()
    {
        return $this->hasMany(BidangPelatihan::class);
    }

    // public function pelatihans()
    // {
    //     return $this->belongsToMany(
    //         Pelatihan::class,       // Model yang dihubungkan
    //         'bidang_pelatihan',     // Nama tabel pivot
    //         'bidang_id',            // Foreign key di tabel pivot untuk model ini (Bidang)
    //         'pelatihan_id'          // Foreign key di tabel pivot untuk model Pelatihan
    //     )
    //         // <-- TAMBAHAN: Beritahu Eloquent untuk menggunakan Model Pivot kustom Anda
    //         ->using(BidangPelatihan::class)

    //         // <-- TAMBAHAN: Tentukan kolom ekstra di pivot yang ingin Anda akses
    //         ->withPivot([
    //             'lokasi',
    //             'kota',
    //             'kode_bidang_pelatihan',
    //             'rata_rata_peningkatan',
    //             'status_performa',
    //         ]);
    // }

    // public function pendaftaranPelatihan()
    // {
    //     return $this->hasManyThrough(
    //         PendaftaranPelatihan::class, // Model akhir yang dituju
    //         BidangPelatihan::class,      // Model perantara

    //         // Kunci relasi Bidang -> BidangPelatihan
    //         'bidang_pelatihan_id', // Foreign key di tabel 'bidang_pelatihan'
    //         'id',        // Local key di tabel 'bidang'

    //         // Kunci relasi BidangPelatihan -> PendaftaranPelatihan
    //         'bidang_id', // Foreign key di tabel 'pendaftaran_pelatihan' (yang merujuk ke bidang_pelatihan.id)
    //         'id'         // Local key di tabel 'bidang_pelatihan'
    //     );
    // }
}
