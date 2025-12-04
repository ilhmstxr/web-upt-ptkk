<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kamar extends Model
{
    use HasFactory;

    protected $table = 'kamar';

    protected $fillable = [
        'asrama_id',
        'nomor_kamar',
        'lantai',
        'status',
        'total_beds',
        'available_beds',
    ];

    public function asrama()
    {
        return $this->belongsTo(Asrama::class, 'asrama_id');
    }

    /**
     * Semua riwayat penempatan penghuni pada kamar ini
     */
    public function penempatans()
    {
        return $this->hasMany(PenempatanAsrama::class, 'kamar_id');
    }

    /**
     * Penghuni yang masih aktif (belum checkout)
     */
    public function penghuniAktif()
    {
        return $this->hasMany(PenempatanAsrama::class, 'kamar_id')
            ->whereNull('checkout_at');
    }
}
