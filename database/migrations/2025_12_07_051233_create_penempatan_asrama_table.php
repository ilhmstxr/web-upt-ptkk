<?php

// app/Models/PenempatanAsrama.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PenempatanAsrama extends Model
{
    protected $table = 'penempatan_asrama';

    protected $fillable = [
        'pelatihan_id',
        'peserta_id',
        'asrama_id',
        'kamar_id',
        'periode',
    ];

    public function pelatihan()
    {
        return $this->belongsTo(Pelatihan::class);
    }

    public function peserta()
    {
        return $this->belongsTo(Peserta::class);
    }

    public function asrama()
    {
        return $this->belongsTo(Asrama::class);
    }

    public function kamar()
    {
        return $this->belongsTo(Kamar::class);
    }
}
