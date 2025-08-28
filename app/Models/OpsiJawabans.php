<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OpsiJawabans extends Model
{
    use HasFactory;

    protected $table = 'opsi_jawabans';

    protected $fillable = [
        'pertanyaan_id',
        'teks_opsi',
        'gambar',
        'apakah_benar',
    ];

    // Relasi ke Pertanyaan
    public function pertanyaan()
    {
        return $this->belongsTo(Pertanyaan::class, 'pertanyaan_id');
    }

    // Relasi ke JawabanUser
    public function jawabanUser()
    {
        return $this->hasMany(JawabanUser::class, 'opsi_jawabans_id');
    }

    // Accessor untuk URL gambar
    public function getGambarUrlAttribute()
    {
        return $this->gambar ? asset('storage/' . $this->gambar) : null;
    }
}
