<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pertanyaan extends Model
{
    use HasFactory;

    protected $table = 'pertanyaan';

    protected static function booted()
    {
        static::creating(function ($pertanyaan) {
            if (is_null($pertanyaan->nomor)) {
                $maxNomor = static::where('tes_id', $pertanyaan->tes_id)->max('nomor') ?? 0;
                $pertanyaan->nomor = $maxNomor + 1;
            }
        });
    }

    protected $fillable = [
        'tes_id',
        'nomor',
        'teks_pertanyaan',
        'kategori',
        'gambar',
        'tipe_jawaban',
    ];


    protected $casts = [
        'nomor' => 'integer',
    ];

    protected $hidden = ['templates'];

    // -------------------------
    // RELATIONS
    // -------------------------

    // Relasi ke Tes
    public function tes()
    {
        return $this->belongsTo(Tes::class, 'tes_id');
    }

    // Alias singular supaya kode lama tetap jalan
    public function opsiJawabans()
    {
        return $this->hasMany(OpsiJawaban::class, 'pertanyaan_id')->orderBy('id');
    }

    public function pivotTemplate()
    {
        return $this->hasOne(PivotJawaban::class, 'pertanyaan_id');
    }

    public function templatePertanyaan()
    {
        return $this->hasOneThrough(
            Pertanyaan::class,
            PivotJawaban::class,
            'pertanyaan_id',          // FK di pivot -> pertanyaan ini
            'id',                     // PK pertanyaan template
            'id',                     // PK pertanyaan ini
            'template_pertanyaan_id'  // FK di pivot -> pertanyaan template
        );
    }

    // Relasi ke Jawaban User
    public function jawabanUsers()
    {
        return $this->hasMany(JawabanUser::class, 'pertanyaan_id');
    }

    // Relasi ke template pertanyaan (self-referencing many-to-many)
    public function templates()
    {
        return $this->belongsToMany(self::class, 'pivot_jawaban', 'pertanyaan_id', 'template_pertanyaan_id');
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
    // Di dalam file: app/Models/Pertanyaan.php


    public function getOpsiJawabansAttribute()
    {
        // Mengambil data relasi 'opsiJawabans' yang sudah di-load oleh with()
        $opsiMilikSendiri = $this->getRelationValue('opsiJawabans');

        // Jika ada dan tidak kosong, langsung kembalikan
        if ($opsiMilikSendiri && $opsiMilikSendiri->isNotEmpty()) {
            return $opsiMilikSendiri;
        }

        // Jika tidak ada, ambil data 'templates' yang sudah di-load oleh with()
        $template = $this->getRelationValue('templates')->first();

        // Kembalikan opsi jawaban dari template
        return optional($template)->getRelationValue('opsiJawabans') ?? collect();
    }

    /**
     * Helper: ambil jawaban yang benar dari collection jawaban user
     */
    public function hitungSkor(?int $jumlahBenar, ?int $jumlahTotal): int
    {
        $benar = max(0, (int) ($jumlahBenar ?? 0));
        $total = max(0, (int) ($jumlahTotal ?? 0));
        if ($total === 0) {
            return 0;
        }
        return (int) round(($benar / $total) * 100);
    }
    // public function hitungSkor(Percobaan $percobaan): int
    // {
    //     $percobaan->loadMissing(['jawabanUser.opsiJawaban']);
    //     $jawabanCollection = $percobaan->jawabanUser ?? collect();

    //     $total = $jawabanCollection->count();
    //     if ($total === 0) {
    //         return 0;
    //     }

    //     $benar = $jawabanCollection->filter(fn($j) => $j->opsiJawaban && $j->opsiJawaban->apakah_benar)->count();
    //     return (int) round(($benar / $total) * 100);
    // }
}
