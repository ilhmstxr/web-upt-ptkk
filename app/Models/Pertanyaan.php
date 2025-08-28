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
    /** @use HasFactory<\Database\Factories\TesPertanyaanFactory> */
    use HasFactory;
    protected $fillable = ['nomor', 'teks_pertanyaan', 'tipe_jawaban', 'kuis_id', 'gambar'];

    public function kuis(): BelongsToMany
    {
        // Merujuk ke model Tes
        return $this->belongsToMany(Kuis::class, 'kuis_id');
    }

    public function opsiJawabans(): HasMany
    {
        return $this->hasMany(OpsiJawaban::class, 'pertanyaan_id');
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
