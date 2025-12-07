<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PendaftaranPelatihan extends Model
{
    use HasFactory;

    protected $table = 'pendaftaran_pelatihan';

    protected $fillable = [
        'peserta_id',
        'pelatihan_id',
        'kompetensi_id',
        'kompetensi_pelatihan_id',
        'nomor_registrasi',
        'tanggal_pendaftaran',
        'kelas',
        'urutan_per_kompetensi',
        'nilai_pre_test',
        'nilai_post_test',
        'nilai_praktek',
        'rata_rata',
        'nilai_survey',
        'status',
        'status_pendaftaran',
        'assessment_token',
        'assessment_token_sent_at',
    ];

    protected $casts = [
        'tanggal_pendaftaran' => 'datetime',
        'assessment_token_sent_at' => 'datetime',
    ];

    // ======================
    // RELATIONS UTAMA
    // ======================
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

    /**
     * ✅ Relasi penempatan asrama TANPA filter.
     * Karena filter pelatihan harus di-query saat dipakai.
     */
    public function penempatanAsrama()
    {
        return $this->hasOne(PenempatanAsrama::class, 'peserta_id', 'peserta_id');
    }

    /**
     * ✅ Helper buat ambil penempatan asrama sesuai pelatihan pendaftaran ini.
     * Ini aman untuk peserta ikut banyak pelatihan.
     */
    public function penempatanAsramaAktif()
    {
        return PenempatanAsrama::query()
            ->where('peserta_id', $this->peserta_id)
            ->where('pelatihan_id', $this->pelatihan_id)
            ->first();
    }
}
