<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Casts\Attribute;


class Pertanyaan extends Model
{
    use HasFactory;

    protected $table = 'pertanyaans';

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

    /**
     * Mendapatkan link ke pertanyaan template.
     * Digunakan untuk soal tipe survei.
     */
    public function opsiLink(): HasOne
    {
        return $this->hasOne(PivotJawaban::class, 'pertanyaan_id');
    }

    /**
     * Accessor untuk mendapatkan koleksi opsi jawaban final.
     *
     * Accessor ini secara cerdas akan memeriksa apakah pertanyaan ini
     * memiliki link ke template. Jika ya, ia akan mengembalikan opsi jawaban
     * dari template tersebut. Jika tidak, ia akan mengembalikan opsi jawaban
     * miliknya sendiri.
     *
     * @return Attribute
     */
    protected function opsiJawabanFinal(): Attribute
    {
        return Attribute::make(
            get: function () {
                // Cek apakah ada link ke template
                if ($this->opsiLink) {
                    // Jika ada, ambil opsi jawaban dari pertanyaan template
                    return $this->opsiLink->templatePertanyaan->opsiJawabans;
                }

                // Jika tidak ada link, ambil opsi jawaban langsung milik pertanyaan ini
                return $this->opsiJawabans;
            }
        );
    }
}
