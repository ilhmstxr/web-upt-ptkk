<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Percobaan extends Model
{
    use HasFactory;

    protected $table = 'percobaan';

    protected $fillable = [
        'peserta_id',         // Kolom baru untuk data baru
        'pesertaSurvei_id',
        'tes_id',
        'waktu_mulai',
        'waktu_selesai',
        'skor',
        'lulus',
        'pesan_kesan',
    ];

    protected $casts = [
        'waktu_mulai' => 'datetime',
        'waktu_selesai' => 'datetime',
        'skor' => 'decimal:2',
        'lulus' => 'boolean',
        'created_at'    => 'datetime',
        'updated_at'    => 'datetime',
    ];


    // -------------------------
    // RELATIONS
    // -------------------------

    /**
     * Relasi utama: PesertaSurvei (sesuai kolom pesertaSurvei_id)
     */
    public function pesertaSurvei()
    {
        return $this->belongsTo(\App\Models\PesertaSurvei::class, 'pesertaSurvei_id', 'id')->withDefault();
    }

    /**
     * Convenience alias 'peserta' (Filament & kode lain sering memanggil peserta)
     * This will prefer Peserta model if present, otherwise fallback to PesertaSurvei.
     */
    public function peserta()
    {
        if (class_exists(\App\Models\Peserta::class)) {
            return $this->belongsTo(\App\Models\Peserta::class, 'peserta_id', 'id')->withDefault();
        }
        return $this->belongsTo(\App\Models\PesertaSurvei::class, 'peserta_id', 'id')->withDefault();
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

    /**
     * Hitung jumlah jawaban benar (integer).
     * - Aman dipanggil kapan saja.
     */
    public function hitungSkor(): int
    {
        // Ambil semua jawaban + opsi terkait
        $jawaban = $this->jawabanUser()->with('opsiJawaban')->get();

        if ($jawaban->isEmpty()) {
            return 0;
        }

        $benar = 0;
        foreach ($jawaban as $j) {
            $opsi = $j->opsiJawaban ?? null;

            if (!$opsi && $j->opsi_jawaban_id) {
                $opsi = \App\Models\OpsiJawaban::find($j->opsi_jawaban_id);
            }

            if (!$opsi) {
                continue;
            }

            // cek berbagai nama boolean di tabel opsi jawaban
            if (isset($opsi->apakah_benar) && $opsi->apakah_benar) {
                $benar++;
            } elseif (isset($opsi->benar) && $opsi->benar) {
                $benar++;
            } elseif (isset($opsi->is_correct) && $opsi->is_correct) {
                $benar++;
            }
        }

        return (int) $benar;
    }

    /**
     * Hitung persentase skor (2 desimal)
     */
    public function hitungSkorPersen(): float
    {
        // Usahakan pakai jumlah soal di tes jika tersedia
        $totalSoal = 0;
        if ($this->tes && method_exists($this->tes, 'pertanyaan')) {
            $totalSoal = $this->tes->pertanyaan()->count();
        }

        if ($totalSoal <= 0) {
            $totalSoal = $this->jawabanUser()->count();
        }

        if ($totalSoal <= 0)
            return 0.0;

        $benar = $this->hitungSkor();

        return round((($benar / $totalSoal) * 100), 2);
    }

    /**
     * Convenience: hitung lalu simpan skor + lulus flag
     */
    public function hitungDanSimpanSkor(float $passingScore = null): void
    {
        $benar = $this->hitungSkor();
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
