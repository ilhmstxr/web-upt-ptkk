<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kamar extends Model
{
    /** @use HasFactory<\Database\Factories\KamarFactory> */
    use HasFactory;

    protected $table = 'kamar';

    protected $fillable = [
        'nomor_kamar',
        'lantai',
        'asrama_id',
        'total_beds',
        'available_beds',
        'status',
    ];

    public function asrama()
    {
        return $this->belongsTo(Asrama::class, 'asrama_id');
    }
    
}
