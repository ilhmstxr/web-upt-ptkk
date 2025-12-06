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
        'bidang_id',
        'bidang_pelatihan_id', // Dari versi pertama
        
        // Data Registrasi
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

    public function bidang()
    {
        return $this->belongsTo(Bidang::class, 'bidang_id');
    }

    // Relasi tambahan dari versi pertama (jika Anda menggunakan tabel pivot bidang_pelatihan)
    public function bidangPelatihan()
    {
        return $this->belongsTo(BidangPelatihan::class, 'bidang_pelatihan_id');
    }
}