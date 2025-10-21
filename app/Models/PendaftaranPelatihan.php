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
        'nomor_registrasi',
        'tanggal_pendaftaran',

        'nilai_pre_test',
        'nilai_post_test',
        'nilai_praktek',
        'rata_rata',
        'nilai_survey',
        'status',
    ];

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
}
