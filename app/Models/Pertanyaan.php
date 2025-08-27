<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pertanyaan extends Model
{
    use HasFactory;

    protected $table = 'pertanyaans';

    protected $fillable = [
        'kuis_id',
        'nomor',
        'teks_pertanyaan',
        'gambar',
        'tipe_jawaban',
    ];

    // Relasi ke kuis
    public function kuis()
    {
        return $this->belongsTo(Kuis::class, 'kuis_id');
    }

    // Relasi ke opsi jawaban
    public function opsiJawabans()
    {
        return $this->hasMany(OpsiJawaban::class, 'pertanyaan_id');
    }

    // Relasi ke jawaban user
    public function jawabanUsers()
    {
        return $this->hasMany(JawabanUser::class, 'pertanyaan_id');
    }

    // Accessor gambar
    public function getGambarUrlAttribute()
    {
        return $this->gambar ? asset('storage/' . $this->gambar) : null;
    }
}
