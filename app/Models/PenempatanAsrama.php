<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PenempatanAsrama extends Model
{
    protected $table = 'penempatan_asrama';

    protected $fillable = [
        'pendaftaran_id', 'kamar_id', 'bed_no'
    ];

    public function kamar()
    {
        return $this->belongsTo(Kamar::class, 'kamar_id');
    }

    public function pendaftaran()
    {
        return $this->belongsTo(PendaftaranPelatihan::class, 'pendaftaran_id');
    }
}
