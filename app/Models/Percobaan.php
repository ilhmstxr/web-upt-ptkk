<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Percobaan extends Model
{
    use HasFactory;

    protected $table = 'percobaan';

    protected $fillable = [
        'peserta_id',
        'pesertaSurvei_id',
        'pelatihan_id',
        'tes_id',
        'tipe',
        'waktu_mulai',
        'waktu_selesai',
        'skor',
        'lulus',
        'pesan_kesan',
        // 'is_legacy' sengaja TIDAK di-fillable,
        // biar cuma diatur otomatis oleh model, bukan dari input user.
    ];

    protected $casts = [
        'waktu_mulai'   => 'datetime',
        'waktu_selesai' => 'datetime',
        'skor'          => 'decimal:2',
        'lulus'         => 'boolean',
        'is_legacy'     => 'boolean',
        'created_at'    => 'datetime',
        'updated_at'    => 'datetime',
    ];

    protected static function booted()
    {
        static::creating(function (Percobaan $model) {
            // Semua data baru yang dibuat lewat Eloquent = BUKAN legacy
            if ($model->is_legacy === null) {
                $model->is_legacy = false; // 0
            }
        });
    }

    // -------------------------
    // RELATIONS
    // -------------------------

    public function peserta()
    {
        return $this->belongsTo(\App\Models\Peserta::class, 'peserta_id');
    }

    public function pesertaSurvei()
    {
        return $this->belongsTo(\App\Models\PesertaSurvei::class, 'pesertaSurvei_id');
    }

    public function pelatihan()
    {
        return $this->belongsTo(\App\Models\Pelatihan::class, 'pelatihan_id');
    }

    public function tes()
    {
        return $this->belongsTo(\App\Models\Tes::class, 'tes_id');
    }

    public function jawabanUser()
    {
        return $this->hasMany(\App\Models\JawabanUser::class, 'percobaan_id', 'id');
    }

    public function tipeTes()
    {
        return $this->hasMany(\App\Models\TipeTes::class, 'tes_id', 'tes_id');
    }

    // -------------------------
    // HELPERS / SCORING
    // -------------------------

    public function hitungSkor(): int
    {
        $jawaban = $this->jawabanUser()->with('opsiJawaban')->get();
        if ($jawaban->isEmpty()) return 0;

        $benar = 0;
        foreach ($jawaban as $j) {
            $opsi = $j->opsiJawaban;
            if (!$opsi && $j->opsi_jawaban_id) {
                $opsi = \App\Models\OpsiJawaban::find($j->opsi_jawaban_id);
            }
            if (!$opsi) continue;

            if (($opsi->apakah_benar ?? false) || ($opsi->benar ?? false) || ($opsi->is_correct ?? false)) {
                $benar++;
            }
        }

        return (int) $benar;
    }

    public function hitungSkorPersen(): float
    {
        $totalSoal = 0;

        if ($this->tes && method_exists($this->tes, 'pertanyaan')) {
            $totalSoal = $this->tes->pertanyaan()->count();
        }

        if ($totalSoal <= 0) {
            $totalSoal = $this->jawabanUser()->count();
        }

        if ($totalSoal <= 0) {
            return 0.0;
        }

        $benar = $this->hitungSkor();
        return round(($benar / $totalSoal) * 100, 2);
    }

    public function hitungDanSimpanSkor(float $passingScore = null): void
    {
        $persen = $this->hitungSkorPersen();
        $this->skor = $persen;

        if ($passingScore === null && $this->tes && isset($this->tes->passing_score)) {
            $passingScore = $this->tes->passing_score;
        }

        $this->lulus = $passingScore !== null
            ? ($persen >= $passingScore)
            : ($persen >= 70);

        $this->waktu_selesai = $this->waktu_selesai ?? now();
        $this->save();
    }
}
