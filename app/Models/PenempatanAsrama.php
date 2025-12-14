<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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

    public function pendaftaran()
    {
        return $this->belongsTo(PendaftaranPelatihan::class, 'pendaftaran_id');
    }

    public function penempatanAsramaAktif()
    {
        return $this->penempatanAsrama()
            ->with('kamar.asrama')
            ->latest()
            ->first();
    }

    public function scopePenghuniAktif($query)
    {
        return $query->whereHas('pendaftaranPelatihan.pelatihan', function ($q) {
            $q->where('status', 'aktif');
        });
    }
}
