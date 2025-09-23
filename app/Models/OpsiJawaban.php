<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class OpsiJawaban extends Model
{
    use HasFactory;

    protected $table = 'opsi_jawaban';

    protected $fillable = [
        'pertanyaan_id',
        'teks_opsi',
        'gambar',
        'apakah_benar',
    ];

    protected $casts = [
        'pertanyaan_id' => 'integer',
        'apakah_benar'  => 'boolean',
        'sort_order'    => 'integer',
    ];

    // Relasi ke Pertanyaan
    public function pertanyaan()
    {
        return $this->belongsTo(Pertanyaan::class, 'pertanyaan_id');
    }

    // Relasi ke JawabanUser
    public function jawabanUser()
    {
        return $this->hasMany(JawabanUser::class, 'opsi_jawaban_id');
    }

    // Accessor untuk URL gambar
    // public function getGambarUrlAttribute()
    // {
    //     return $this->gambar ? asset('storage/' . $this->gambar) : null;
    // }
    public function getGambarUrlAttribute(): ?string
    {
        if (! $this->gambar) {
            return null;
        }
        return Storage::disk('public')->url($this->gambar);
    }
}
