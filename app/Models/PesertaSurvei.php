<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PesertaSurvei extends Model
{
    /** @use HasFactory<\Database\Factories\PesertaSurveiFactory> */
    use HasFactory;

    // protected $table = 'peserta_survei';
    protected $fillable = [
        'email',
        'nama',
        'pelatihan_id',
        'bidang_id',
        'tes_id',
        'angkatan'
    ];

    public function jawaban()
    {
        return $this->hasMany(JawabanUser::class);
    }

    public function percobaan()
    {
        return $this->hasMany(Percobaan::class);
    }
}
