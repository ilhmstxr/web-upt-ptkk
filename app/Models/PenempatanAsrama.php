<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder; // Diperlukan untuk scope type-hinting

class PenempatanAsrama extends Model
{

    protected $table = 'penempatan_asrama';

    // Kolom yang dapat diisi secara massal
    protected $fillable = [
        'peserta_id',
        'asrama_id',
        'kamar_id',
        'pendaftaran_id',
        'pendaftaran_pelatihan_id',
        'pelatihan_id',
        // Pastikan kolom tanggal_selesai ada di sini jika Anda menggunakannya
       // 'tanggal_selesai', 
    ];

    // Hubungan: Penempatan ini milik satu Kamar
    public function kamar()
    {
        return $this->belongsTo(Kamar::class, 'kamar_id');
    }
    
    // Hubungan: Penempatan ini milik satu Pendaftaran Pelatihan
    public function pendaftaranPelatihan()
    {
        // Menggunakan pendaftaran_pelatihan_id (asumsi)
        return $this->belongsTo(PendaftaranPelatihan::class, 'pendaftaran_pelatihan_id');
    }

    /**
     * Scope untuk mengambil semua penempatan yang masih aktif.
     * Metode ini dipanggil sebagai PenempatanAsrama::penghuniAktif()
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopePenghuniAktif(Builder $query): Builder
    {
        // ASUMSI LOGIKA AKTIF: Tanggal selesai belum terlewati
        return $query->where('tanggal_selesai', '>=', now());
                     
        // Jika Anda memiliki kolom status, Anda bisa menggunakan ini:
        // return $query->where('tanggal_selesai', '>=', now())
        //              ->where('status_penempatan', 'Masuk');
    }


    
}