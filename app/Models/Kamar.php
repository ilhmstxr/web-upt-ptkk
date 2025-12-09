<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kamar extends Model
{
    use HasFactory;

    protected $table = 'kamar';

    protected $fillable = [
        'pelatihan_id',
        'asrama_id',
        'nomor_kamar',
        'lantai',          // atas / bawah
        'total_beds',
        'available_beds',
        'is_active',
        'status',          // kalau kamu punya kolom status
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function asrama()
    {
        return $this->belongsTo(Asrama::class, 'asrama_id');
    }

    public function penempatanAsrama()
    {
        return $this->hasMany(PenempatanAsrama::class, 'kamar_id');
    }
}
