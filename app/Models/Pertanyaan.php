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

    // -------------------------
    // RELATIONS
    // -------------------------

    // Relasi ke Tes
    public function tes()
    {
        return $this->belongsTo(Tes::class, 'tes_id');
    }

    // Relasi ke Opsi Jawaban (plural: hasMany)
    public function opsiJawabans()
    {
        return $this->hasMany(OpsiJawaban::class, 'pertanyaan_id');
    }

    // Alias singular supaya kode lama tetap jalan
    public function opsiJawaban()
    {
        return $this->hasMany(OpsiJawaban::class, 'pertanyaan_id');
    }

    // Relasi ke Jawaban User
    public function jawabanUsers()
    {
        return $this->hasMany(JawabanUser::class, 'pertanyaan_id');
    }

    // Relasi ke template pertanyaan (self-referencing many-to-many)
    public function templates()
    {
        return $this->belongsToMany(
            self::class,
            'pivot_jawaban',           // nama tabel pivot
            'pertanyaan_id',           // FK ke pertanyaan ini
            'template_pertanyaan_id'   // FK ke pertanyaan template
        );
    }

    // -------------------------
    // ACCESSORS
    // -------------------------

    // URL gambar
    public function getGambarUrlAttribute()
    {
        return $this->gambar ? asset('storage/' . $this->gambar) : null;
    }

    /**
     * Ambil opsi jawaban final
     * - Pakai opsi sendiri kalau ada
     * - Kalau kosong, fallback ke template pertama
     */
    public function getOpsiJawabanFinalAttribute()
    {
        $own = $this->relationLoaded('opsiJawabans')
            ? $this->opsiJawabans
            : $this->opsiJawabans()->get();

        if ($own->isNotEmpty()) {
            return $own;
        }

        $templates = $this->relationLoaded('templates')
            ? $this->templates
            : $this->templates()->with('opsiJawabans')->get();

        return collect(optional($templates->first())->opsiJawabans);
    }

    /**
     * Helper: ambil jawaban yang benar dari collection jawaban user
     */
    public function hitungSkor(Percobaan $percobaan): int
    {
        $percobaan->loadMissing(['jawabanUser.opsiJawaban']);
        $jawabanCollection = $percobaan->jawabanUser ?? collect();

        $total = $jawabanCollection->count();
        if ($total === 0) {
            return 0;
        }

        $benar = $jawabanCollection->filter(fn($j) => $j->opsiJawaban && $j->opsiJawaban->apakah_benar)->count();
        return (int) round(($benar / $total) * 100);
    }
}
