<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Percobaan extends Model
{
    use HasFactory;

    protected $table = 'percobaans';

    protected $fillable = [
        'peserta_id',
        'kuis_id',
        'waktu_mulai',
        'waktu_selesai',
        'skor',
        'pesan_kesan',
    ];

    public function peserta()
    {
        return $this->belongsTo(Peserta::class, 'peserta_id');
    }

    public function kuis()
    {
        return $this->belongsTo(Kuis::class, 'kuis_id');
    }

    public function jawabanUsers()
    {
        return $this->hasMany(JawabanUser::class, 'percobaan_id');
    }
}
