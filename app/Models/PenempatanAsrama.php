<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenempatanAsrama extends Model
{
    use HasFactory;

    protected $table = 'penempatan_asrama';

    protected $fillable = [
        'pendaftaran_id',
        'kamar_id',
        'checkin_at',
        'checkout_at',
    ];

    protected $casts = [
        'checkin_at' => 'datetime',
        'checkout_at' => 'datetime',
    ];

    public function kamar()
    {
        return $this->belongsTo(Kamar::class, 'kamar_id');
    }

    public function pendaftaran()
    {
        return $this->belongsTo(PendaftaranPelatihan::class, 'pendaftaran_id');
    }

    public function scopePenghuniAktif($q)
    {
        return $q->whereNull('checkout_at');
    }

    public function peserta()
    {
        return $this->hasOneThrough(
            Peserta::class,
            PendaftaranPelatihan::class,
            'id', 
            'id', 
            'pendaftaran_id', 
            'peserta_id'
        );
    }
}
