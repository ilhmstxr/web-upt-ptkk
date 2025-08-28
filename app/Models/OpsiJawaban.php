<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OpsiJawaban extends Model
{
    use HasFactory;

    protected $table = 'opsi_jawabans';

    protected $fillable = [
        'pertanyaan_id',
        'teks_opsi',
        'gambar',
        'apakah_benar',
    ];

    public function pertanyaan()
    {
        return $this->belongsTo(Pertanyaan::class, 'pertanyaan_id');
    }

    public function jawabanUsers()
    {
        return $this->hasMany(JawabanUser::class, 'opsi_jawaban_id');
    }

    // Accessor gambar
    public function getGambarUrlAttribute()
    {
        return $this->gambar ? asset('storage/' . $this->gambar) : null;
    }
}
