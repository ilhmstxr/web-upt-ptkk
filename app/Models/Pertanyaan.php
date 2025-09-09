<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pertanyaan extends Model
{
    use HasFactory;

    protected $table = 'pertanyaan';

    protected $fillable = [
        'tes_id',
        'nomor',
        'teks_pertanyaan',
        'gambar',
        'tipe_jawaban',
    ];

    // Relasi ke Tes
    public function tes()
    {
        return $this->belongsTo(Tes::class, 'tes_id');
    }

    // Relasi ke Opsi Jawaban (plural karena hasMany)
    public function opsiJawabans()
    {
        return $this->hasMany(OpsiJawabans::class, 'pertanyaan_id');
    }

    // Relasi ke Jawaban User
    public function jawabanUser()
    {
        return $this->hasMany(JawabanUser::class, 'pertanyaan_id');
    }

    // Accessor untuk URL gambar
    public function getGambarUrlAttribute()
    {
        return $this->gambar ? asset('storage/' . $this->gambar) : null;
    }
}
