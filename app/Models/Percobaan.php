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
        'tes_id',
        'waktu_mulai',
        'waktu_selesai',
        'skor',
        'lulus',
        'pesan_kesan',
    ];

    protected $casts = [
        'waktu_mulai'   => 'datetime',
        'waktu_selesai' => 'datetime',
        'skor' => 'decimal:2',
        'lulus' => 'boolean',
        'created_at'    => 'datetime',
        'updated_at'    => 'datetime',
    ];


    // -------------------------
    // RELATIONS
    // -------------------------

    public function peserta()
    {
        return $this->belongsTo(\App\Models\Peserta::class, 'peserta_id', 'id')->withDefault();
    }

    public function tes()
    {
        return $this->belongsTo(\App\Models\Tes::class, 'tes_id', 'id')->withDefault();
    }

    public function jawabanUser()
    {
        return $this->hasMany(\App\Models\JawabanUser::class, 'percobaan_id', 'id');
    }

    public function tipeTes()
    {
        return $this->hasMany(TipeTes::class, 'tes_id', 'tes_id');
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
        if ($totalSoal <= 0) $totalSoal = $this->jawabanUser()->count();
        if ($totalSoal <= 0) return 0.0;

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
        $this->lulus = $passingScore !== null ? ($persen >= $passingScore) : ($persen >= 70);
        $this->waktu_selesai = $this->waktu_selesai ?? now();
        $this->save();
    }
}
