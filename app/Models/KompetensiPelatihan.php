<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KompetensiPelatihan extends Model
{
    use HasFactory;
    protected $table = 'kompetensi_pelatihan';
    
    protected $fillable = [
        'pelatihan_id',
        'kompetensi_id',
        'lokasi',
        'kota',
        'kode_kompetensi_pelatihan',
        'rata_rata_peningkatan',
        'status_performa',
        'metode',
        'file_modul',
        'tanggal',
        'jam_mulai',
        'jam_selesai',
        'instruktur_id',
    ];

    public function pelatihan()
    {
        return $this->belongsTo(Pelatihan::class);
    }


    public function kompetensi()
    {
        return $this->belongsTo(Kompetensi::class);
    }

    /**
     * Mendapatkan semua pendaftaran untuk sesi/jadwal ini.
     */
    public function pendaftaranPelatihan()
    {
        // Terhubung ke PendaftaranPelatihan::class melalui 'kompetensi_pelatihan_id'
        return $this->hasMany(PendaftaranPelatihan::class, 'kompetensi_pelatihan_id');
    }

    /**
     * Mendapatkan semua peserta yang mendaftar di sesi ini
     * (melalui tabel pendaftaran_pelatihan).
     */
    public function peserta()
    {
        return $this->hasManyThrough(
            Peserta::class,
            PendaftaranPelatihan::class,
            'kompetensi_pelatihan_id', // Foreign key di PendaftaranPelatihan
            'id',                    // Foreign key di Peserta
            'id',                    // Local key di KompetensiPelatihan
            'peserta_id'             // Local key di PendaftaranPelatihan
        );
    }

    public function instruktur()
    {
        return $this->belongsTo(Instruktur::class);
    }
}
