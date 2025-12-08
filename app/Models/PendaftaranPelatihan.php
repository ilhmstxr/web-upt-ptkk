<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PendaftaranPelatihan extends Model
{
    use HasFactory;

    protected $table = 'pendaftaran_pelatihan';

    // Menggabungkan semua field dari kedua versi agar aman saat mass assignment
    protected $fillable = [
        // Identitas & Relasi
        'peserta_id',
        'pelatihan_id',
        'kompetensi_id',
        'kompetensi_pelatihan_id', // Added missing field from usage
        'nomor_registrasi',
        'tanggal_pendaftaran',
        'urutan_per_bidang',
        'kelas',
        
        // Status
        'status',              // Status umum (dari versi pertama)
        'status_pendaftaran',  // Status spesifik pendaftaran
        
        // Token Assessment (Dari versi kedua)
        'assessment_token',
        'token_expires_at',

        // Nilai & Survey (Dari versi pertama)
        'nilai_pre_test',
        'nilai_post_test',
        'nilai_praktek',
        'rata_rata',
        'nilai_survey',
        'status',
        'status_pendaftaran',
        'urutan_per_kompetensi', // Added missing field from usage
    ];

    protected $casts = [
        'tanggal_pendaftaran' => 'datetime',
        'token_expires_at'    => 'datetime', // Casting untuk expiry token
    ];

    /**
     * RELASI
     */

    public function peserta()
    {
        return $this->belongsTo(Peserta::class, 'peserta_id');
    }

    public function pelatihan()
    {
        return $this->belongsTo(Pelatihan::class, 'pelatihan_id');
    }
    public function kompetensiPelatihan()
    {
        return $this->belongsTo(KompetensiPelatihan::class, 'kompetensi_pelatihan_id');
    }
    public function kompetensi()
    {
        return $this->belongsTo(Kompetensi::class, 'kompetensi_id');
    }
}