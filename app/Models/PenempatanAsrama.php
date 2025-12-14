<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder; // Diperlukan untuk scope type-hinting
use App\Models\Pelatihan; // Diperlukan untuk relasi

class PenempatanAsrama extends Model
{
    protected $table = 'penempatan_asrama';

    protected $fillable = [
        'peserta_id',
        'asrama_id',
        'kamar_id',
        'pendaftaran_id',
        'pendaftaran_pelatihan_id',
        'pelatihan_id',
    ];

    public function kamar()
    {
        return $this->belongsTo(Kamar::class, 'kamar_id');
    }
    
    public function pendaftaranPelatihan()
    {
        return $this->belongsTo(PendaftaranPelatihan::class, 'pendaftaran_pelatihan_id');
    }

    public function pelatihan()
    {
        return $this->belongsTo(Pelatihan::class, 'pelatihan_id');
    }

    /**
     * Scope untuk mengambil semua penempatan yang masih aktif.
     * Logika aktif diambil dari tanggal berakhir pelatihan yang diikuti peserta.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopePenghuniAktif(Builder $query): Builder
    {
        return $query->whereHas('pelatihan', function (Builder $pelatihanQuery) {
            $pelatihanQuery->where('tanggal_selesai', '>=', now()->toDateString());
        });
    }
}