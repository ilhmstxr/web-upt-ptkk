<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kompetensi extends Model
{
    use HasFactory;

    protected $table = 'kompetensi';

    protected $fillable = [
        'nama_kompetensi',
        'deskripsi',
        'kode',
        'kelas_keterampilan',
        'gambar',
    ];

    public function kompetensiPelatihan()
    {
        return $this->hasMany(KompetensiPelatihan::class);
    }

    // public function pelatihans()
    // {
    //     return $this->belongsToMany(
    //         Pelatihan::class,       // Model yang dihubungkan
    //         'kompetensi_pelatihan',     // Nama tabel pivot
    //         'kompetensi_id',            // Foreign key di tabel pivot untuk model ini (Kompetensi)
    //         'pelatihan_id'          // Foreign key di tabel pivot untuk model Pelatihan
    //     )
    //         // <-- TAMBAHAN: Beritahu Eloquent untuk menggunakan Model Pivot kustom Anda
    //         ->using(KompetensiPelatihan::class)

    //         // <-- TAMBAHAN: Tentukan kolom ekstra di pivot yang ingin Anda akses
    //         ->withPivot([
    //             'lokasi',
    //             'kota',
    //             'kode_kompetensi_pelatihan',
    //             'rata_rata_peningkatan',
    //             'status_performa',
    //         ]);
    // }

    // public function pendaftaranPelatihan()
    // {
    //     return $this->hasManyThrough(
    //         PendaftaranPelatihan::class, // Model akhir yang dituju
    //         KompetensiPelatihan::class,      // Model perantara

    //         // Kunci relasi Kompetensi -> KompetensiPelatihan
    //         'kompetensi_pelatihan_id', // Foreign key di tabel 'kompetensi_pelatihan'
    //         'id',        // Local key di tabel 'kompetensi'

    //         // Kunci relasi KompetensiPelatihan -> PendaftaranPelatihan
    //         'kompetensi_id', // Foreign key di tabel 'pendaftaran_pelatihan' (yang merujuk ke kompetensi_pelatihan.id)
    //         'id'         // Local key di tabel 'kompetensi_pelatihan'
    //     );
    // }
}
