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
        'kategori',
        'gambar',
        'tipe_jawaban',
    ];

    protected $casts = [
        'nomor' => 'integer',
    ];

    protected $hidden = ['templates'];

    // ---------------------------------
    // BOOT: AUTO NOMOR PERTANYAAN
    // ---------------------------------
    protected static function booted()
    {
        static::creating(function ($pertanyaan) {
            if (is_null($pertanyaan->nomor)) {
                $maxNomor = static::where('tes_id', $pertanyaan->tes_id)->max('nomor') ?? 0;
                $pertanyaan->nomor = $maxNomor + 1;
            }
        });
    }

    // ---------------------------------
    // RELATIONS
    // ---------------------------------

    // Relasi ke Tes
    public function tes()
    {
        return $this->belongsTo(Tes::class, 'tes_id');
    }

    // Opsi jawaban milik pertanyaan ini
    public function opsiJawabans()
    {
        return $this->hasMany(OpsiJawaban::class, 'pertanyaan_id')
            ->orderBy('id');
    }

    // Relasi pivot ke template (kalau pertanyaan ini adalah "turunan" dari template)
    public function pivotTemplate()
    {
        return $this->hasOne(PivotJawaban::class, 'pertanyaan_id');
    }

    // Pertanyaan template (parent) dari pertanyaan ini (self-referencing via pivot)
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

    // Jawaban user untuk pertanyaan ini
    public function jawabanUsers()
    {
        return $this->hasMany(JawabanUser::class, 'pertanyaan_id');
    }

    // Relasi many-to-many ke template pertanyaan lain (self referencing)
    public function templates()
    {
        return $this->belongsToMany(
            self::class,
            'pivot_jawaban',
            'pertanyaan_id',
            'template_pertanyaan_id'
        );
    }

    // ---------------------------------
    // ACCESSORS / ATTRIBUTE HELPERS
    // ---------------------------------

    // URL gambar
    public function getGambarUrlAttribute(): ?string
    {
        return $this->gambar ? asset('storage/' . $this->gambar) : null;
    }

    /**
     * Ambil opsi jawaban final:
     * - Kalau pertanyaan ini punya opsi sendiri → pakai itu
     * - Kalau kosong → fallback ke opsi dari pertanyaan template pertama
     */
    public function getOpsiJawabansAttribute()
    {
        // Ambil opsi milik sendiri (relasi sudah di-load oleh with())
        $opsiMilikSendiri = $this->getRelationValue('opsiJawabans');

        if ($opsiMilikSendiri && $opsiMilikSendiri->isNotEmpty()) {
            return $opsiMilikSendiri;
        }

        // Kalau kosong, ambil template pertama (kalau ada)
        $templatesRelation = $this->getRelationValue('templates');

        $template = $templatesRelation && $templatesRelation->isNotEmpty()
            ? $templatesRelation->first()
            : null;

        return optional($template)->getRelationValue('opsiJawabans') ?? collect();
    }

    /**
     * Hitung skor (persentase benar) dari jumlah benar & total.
     * Dipakai kalau kamu mau hitung manual di luar model percobaan.
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

    // ---------------------------------
    // ANALISIS KESUKARAN SOAL
    // ---------------------------------

    /**
     * Indeks kesukaran (P) 0–1
     * P = jumlah jawaban benar / total jawaban
     * null kalau belum ada data jawaban.
     */
    public function getIndeksKesukaranAttribute(): ?float
    {
        $total = $this->jawabanUsers()->count();

        if ($total === 0) {
            return null; // belum ada data siswa
        }

        // hitung berapa jawaban user yang benar
        $benar = $this->jawabanUsers()
            ->whereHas('opsiJawaban', function ($q) {
                $q->where('apakah_benar', true);
            })
            ->count();

        return round($benar / $total, 2);
    }

    /**
     * Kategori kesukaran: Mudah / Sedang / Sulit / Belum Ada Data
     * Berdasarkan indeks kesukaran:
     * - P <= 0.30 → Sulit
     * - 0.30 < P <= 0.70 → Sedang
     * - P > 0.70 → Mudah
     */
    public function getKategoriKesukaranAttribute(): string
    {
        $p = $this->indeks_kesukaran;

        if ($p === null) {
            return 'Belum Ada Data';
        }

        if ($p <= 0.30) {
            return 'Sulit';
        }

        if ($p <= 0.70) {
            return 'Sedang';
        }

        return 'Mudah';
    }
}
