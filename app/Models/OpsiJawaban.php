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
        // 'sort_order', // aktifkan ini kalau kamu benar-benar punya kolom sort_order di DB
    ];

    protected $casts = [
        'pertanyaan_id' => 'integer',
        'apakah_benar'  => 'boolean',
        'sort_order'    => 'integer', // aman walau kolom belum ada
    ];

    // =========================
    // RELATIONS
    // =========================

    public function pertanyaan()
    {
        return $this->belongsTo(Pertanyaan::class, 'pertanyaan_id');
    }

    public function jawabanUsers()
    {
        return $this->hasMany(JawabanUser::class, 'opsi_jawaban_id');
    }

    // =========================
    // ACCESSORS
    // =========================

    public function getGambarUrlAttribute(): ?string
    {
        if (! $this->gambar) {
            return null;
        }

        return Storage::disk('public')->url($this->gambar);
    }
}
