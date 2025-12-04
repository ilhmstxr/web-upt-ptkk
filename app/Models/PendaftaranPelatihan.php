<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PendaftaranPelatihan extends Model
{
    use HasFactory;
    protected $table = 'pendaftaran_pelatihan';
    protected $guarded = ['id'];
    protected $fillable = [
        'peserta_id',
        'pelatihan_id',
        'bidang_id',
        'bidang_pelatihan_id', // Added missing field from usage
        'nomor_registrasi',
        'tanggal_pendaftaran',
        'kelas', // Added

        'nilai_pre_test',
        'nilai_post_test',
        'nilai_praktek',
        'rata_rata',
        'nilai_survey',
        'status',
        'status_pendaftaran',
        'urutan_per_bidang', // Added missing field from usage
    ];

    protected $casts = [
        'tanggal_pendaftaran' => 'datetime',
    ];

    public function peserta()
    {
        return $this->belongsTo(Peserta::class, 'peserta_id');
    }
    public function pelatihan()
    {
        return $this->belongsTo(Pelatihan::class, 'pelatihan_id');
    }
    public function bidangPelatihan()
    {
        return $this->belongsTo(BidangPelatihan::class, 'bidang_pelatihan_id');
    }
    public function bidang()
    {
        return $this->belongsTo(Bidang::class, 'bidang_id');
    }
}
