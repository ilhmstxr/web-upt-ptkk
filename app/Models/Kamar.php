<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Kamar extends Model
{
    use HasFactory;

    protected $table = 'kamar';

    protected $fillable = [
        'asrama_id',
        'nomor_kamar',
        'total_beds',
        'available_beds',
        'status', // kalau nggak punya kolom ini, hapus
    ];

    public function asrama()
    {
        return $this->belongsTo(Asrama::class, 'asrama_id');
    }

    public function penempatanAsramas()
    {
        return $this->hasMany(PenempatanAsrama::class, 'kamar_id');
    }
}
