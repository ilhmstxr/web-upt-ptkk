<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pelatihan extends Model
{
    use HasFactory;

    protected $table = 'pelatihan';

    protected $fillable = [
        'instansi_id',
        'angkatan',
        'jenis_program',
        'nama_pelatihan',
        'slug',
        'gambar',
        'status',
        'tanggal_mulai',
        'tanggal_selesai',
        'deskripsi',
        'jumlah_peserta',
        'sasaran',
        'syarat_ketentuan',
        'jadwal_text',
        'lokasi_text',
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
    ];

    public function instansi()
    {
        return $this->belongsTo(Instansi::class);
    }

    public function peserta()
    {
        return $this->hasMany(Peserta::class);
    }

    public function tes()
    {
        return $this->hasMany(Tes::class);
    }

    public function percobaans()
    {
        return $this->hasManyThrough(
            Percobaan::class, // Model akhir yang ingin diakses
            Tes::class,       // Model perantara/jembatan
            'pelatihan_id', // Foreign key di tabel 'tes' (perantara)
            'tes_id'          // Foreign key di tabel 'percobaan' (akhir)
        );
    }

        /**
     * Mendapatkan semua sesi/jadwal (kompetensi_pelatihan) di bawah pelatihan ini.
     */
    /**
     * Mendapatkan semua sesi/jadwal (kompetensi_pelatihan) di bawah pelatihan ini.
     */
    public function kompetensiPelatihan()
    {
        // Terhubung ke KompetensiPelatihan::class melalui 'pelatihan_id'
        return $this->hasMany(KompetensiPelatihan::class, 'pelatihan_id');
    }

    public function pendaftaranPelatihan()
    {
        return $this->hasMany(PendaftaranPelatihan::class,'pelatihan_id');
    }
    /**
     * Mendapatkan semua pendaftaran untuk pelatihan ini
     * (melalui tabel perantara kompetensi_pelatihan).
     */
    // public function pendaftaranPelatihan()
    // {
    //     return $this->hasManyThrough(
    //         PendaftaranPelatihan::class,      // Model Akhir
    //         KompetensiPelatihan::class,         // Model Perantara
    //         'pelatihan_id',                 // Foreign key di KompetensiPelatihan
    //         'kompetensi_pelatihan_id',          // Foreign key di PendaftaranPelatihan
    //         'id',                           // Local key di Pelatihan (model ini)
    //         'id'                            // Local key di KompetensiPelatihan
    //     );
    // }

    /**
     * Mendapatkan semua kompetensi (materi) yang ada di pelatihan ini
     * (melalui tabel perantara kompetensi_pelatihan).
     */
    // public function kompetensi()
    // {
    //     return $this->hasManyThrough(
    //         Kompetensi::class,
    //         KompetensiPelatihan::class,
    //         'pelatihan_id', // Foreign key di KompetensiPelatihan
    //         'id',           // Foreign key di Kompetensi
    //         'id',           // Local key di Pelatihan (model ini)
    //         'kompetensi_id'     // Local key di KompetensiPelatihan
    //     );
    // }

    // protected function status(): Attribute
    // {
    //     return Attribute::make(
    //         get: function (): string {
    //             $now = now();
    //             if ($now->isBefore($this->tanggal_mulai)) {
    //                 return 'Mendatang';
    //             }
    //             if ($now->between($this->tanggal_mulai, $this->tanggal_selesai)) {
    //                 return 'Aktif';
    //             }
    //             return 'Selesai';
    //         },
    //     );
    // }
}
