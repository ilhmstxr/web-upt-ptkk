<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BidangPelatihan extends Model
{
    use HasFactory;
    protected $table = 'bidang_pelatihan';
    
    protected $fillable = [
        'pelatihan_id',
        'bidang_id',
        'lokasi',
        'kota',
        'kode_bidang_pelatihan',
        'rata_rata_peningkatan',
        'status_performa',
    ];

    public function pelatihan()
    {
        return $this->belongsTo(Pelatihan::class);
    }


    public function bidang()
    {
        return $this->belongsTo(Bidang::class);
    }

    /**
     * Mendapatkan semua pendaftaran untuk sesi/jadwal ini.
     */
    public function pendaftaranPelatihan()
    {
        // Terhubung ke PendaftaranPelatihan::class melalui 'bidang_pelatihan_id'
        return $this->hasMany(PendaftaranPelatihan::class, 'bidang_pelatihan_id');
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
            'bidang_pelatihan_id', // Foreign key di PendaftaranPelatihan
            'id',                    // Foreign key di Peserta
            'id',                    // Local key di BidangPelatihan
            'peserta_id'             // Local key di PendaftaranPelatihan
        );
    }

}
