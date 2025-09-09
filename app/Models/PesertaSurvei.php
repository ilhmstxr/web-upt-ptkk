<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany; // <-- Tambahkan ini

class PesertaSurvei extends Model
{
    /** @use HasFactory<\Database\Factories\PesertaSurveiFactory> */
    use HasFactory;

    protected $table = 'peserta_survei';
    protected $fillable = [
        'email',
        'nama',
        'pelatihan_id',
        'bidang_id',
        'tes_id',
        'angkatan'
    ];


    public function jawabanUsers()
    {
        return $this->hasMany(JawabanUser::class, 'pesertaSurvei_id');
    }

    public function percobaans()
    {
        return $this->hasMany(Percobaan::class, 'pesertaSurvei_id');
    }

    public function pelatihan()
    {
        return $this->belongsTo(Pelatihan::class, 'pelatihan_id');
    }

    public function bidang()
    {
        return $this->belongsTo(Bidang::class);
    }
}
