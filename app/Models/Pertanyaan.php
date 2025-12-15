<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class Pertanyaan extends Model
{
    use HasFactory;

    protected $table = 'pertanyaan';

    protected $fillable = [
        'tes_id',
        'nomor',
        'teks_pertanyaan',
        'kategori',      // keep kalau kolom masih ada di DB
        'gambar',
        'tipe_jawaban',
    ];

    protected $casts = [
        'nomor' => 'integer',
    ];

    protected $hidden = [
        'templates',
    ];

    /**
     * AUTO nomor (per tes_id + kelompok_pertanyaan_id)
     * - Pre/Post: kelompok_pertanyaan_id = null -> nomor urut untuk soal tanpa kategori
     * - Survei: nomor urut per kategori
     */
    protected static function booted()
    {
        static::creating(function (self $pertanyaan) {
            if (! is_null($pertanyaan->nomor)) {
                return;
            }

            $query = static::where('tes_id', $pertanyaan->tes_id);

            if (is_null($pertanyaan->kelompok_pertanyaan_id)) {
                $query->whereNull('kelompok_pertanyaan_id');
            } else {
                $query->where('kelompok_pertanyaan_id', $pertanyaan->kelompok_pertanyaan_id);
            }

            $max = (int) ($query->max('nomor') ?? 0);
            $pertanyaan->nomor = $max + 1;
        });
    }

    // =========================
    // RELATIONS
    // =========================

    public function tes()
    {
        return $this->belongsTo(Tes::class, 'tes_id');
    }

    /**
     * Alias kompatibilitas lama: $pertanyaan->kelompok
     */
    public function kelompok()
    {
        return $this->kelompokPertanyaan();
    }

    /**
     * Relasi utama opsi (dipakai Filament: ->relationship('opsiJawabans'))
     */
    public function opsiJawabans()
    {
        return $this->hasMany(OpsiJawaban::class, 'pertanyaan_id')
            ->orderBy('id');
    }

    /**
     * Alias kompatibilitas lama
     */
    public function opsiJawaban()
    {
        return $this->opsiJawabans();
    }

    /**
     * Relasi pivot ke template (kalau pertanyaan ini turunan template)
     */
    public function pivotTemplate()
    {
        return $this->hasOne(PivotJawaban::class, 'pertanyaan_id');
    }

    /**
     * Pertanyaan template (parent) dari pertanyaan ini (self-referencing via pivot)
     */
    public function templatePertanyaan()
    {
        return $this->hasOneThrough(
            self::class,
            PivotJawaban::class,
            'pertanyaan_id',          // FK di pivot -> pertanyaan ini
            'id',                     // PK pertanyaan template
            'id',                     // PK pertanyaan ini
            'template_pertanyaan_id'  // FK di pivot -> pertanyaan template
        );
    }

    /**
     * Jawaban user untuk pertanyaan ini
     */
    public function jawabanUsers()
    {
        return $this->hasMany(JawabanUser::class, 'pertanyaan_id');
    }

    /**
     * Relasi many-to-many ke template pertanyaan lain (self referencing)
     */
    public function templates()
    {
        return $this->belongsToMany(
            self::class,
            'pivot_jawaban',
            'pertanyaan_id',
            'template_pertanyaan_id'
        );
    }

    // =========================
    // ACCESSORS / HELPERS
    // =========================

    public function getGambarUrlAttribute(): ?string
    {
        return $this->gambar ? asset('storage/' . $this->gambar) : null;
    }

    /**
     * ✅ Pengganti accessor getOpsiJawabansAttribute (yang rawan bentrok Filament).
     *
     * Pakai ini kalau kamu butuh:
     * - Jika opsi milik pertanyaan ini kosong -> ambil opsi dari template pertama.
     *
     * @return \Illuminate\Support\Collection<int, \App\Models\OpsiJawaban>
     */
    public function opsiJawabansFinal(): Collection
    {
        // pastikan relasi opsiJawabans sudah bisa dipakai
        $opsiMilikSendiri = $this->relationLoaded('opsiJawabans')
            ? $this->getRelation('opsiJawabans')
            : $this->opsiJawabans()->get();

        if ($opsiMilikSendiri && $opsiMilikSendiri->isNotEmpty()) {
            return $opsiMilikSendiri;
        }

        $templates = $this->relationLoaded('templates')
            ? $this->getRelation('templates')
            : $this->templates()->with('opsiJawabans')->get();

        $template = $templates->first();

        if (! $template) {
            return collect();
        }

        // ambil opsi template
        return $template->relationLoaded('opsiJawabans')
            ? $template->getRelation('opsiJawabans')
            : $template->opsiJawabans()->get();
    }

    public function hitungSkor(?int $jumlahBenar, ?int $jumlahTotal): int
    {
        $benar = max(0, (int) ($jumlahBenar ?? 0));
        $total = max(0, (int) ($jumlahTotal ?? 0));

        if ($total === 0) {
            return 0;
        }

        return (int) round(($benar / $total) * 100);
    }

    // =========================
    // ANALISIS KESUKARAN SOAL
    // =========================

    public function getIndeksKesukaranAttribute(): ?float
    {
        $total = $this->jawabanUsers()->count();

        if ($total === 0) {
            return null;
        }

        $benar = $this->jawabanUsers()
            ->whereHas('opsiJawaban', function ($q) {
                $q->where('apakah_benar', true);
            })
            ->count();

        return round($benar / $total, 2);
    }

    public function getKategoriKesukaranAttribute(): string
    {
        $p = $this->indeks_kesukaran;

        if ($p === null) return 'Belum Ada Data';
        if ($p <= 0.30) return 'Sulit';
        if ($p <= 0.70) return 'Sedang';
        return 'Mudah';
    }
}
