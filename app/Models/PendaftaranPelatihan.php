<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Schema;

class PendaftaranPelatihan extends Model
{
    use HasFactory;

    protected $table = 'pendaftaran_pelatihan';

    protected $fillable = [
        // Identitas & Relasi
        'peserta_id',
        'pelatihan_id',
        'kompetensi_id',
        'kompetensi_pelatihan_id',
        'nomor_registrasi',
        'tanggal_pendaftaran',
        'kelas',

        // Status
        'status',
        'status_pendaftaran',

        // Nilai & Survei
        'nilai_pre_test',
        'nilai_post_test',
        'nilai_praktek',
        'rata_rata',
        'nilai_survey',
    ];

    protected $casts = [
        'tanggal_pendaftaran'      => 'datetime',
    ];

    // ======================
    // RELATIONS UTAMA
    // ======================

    public function peserta(): BelongsTo
    {
        return $this->belongsTo(Peserta::class, 'peserta_id');
    }

    public function pelatihan(): BelongsTo
    {
        return $this->belongsTo(Pelatihan::class, 'pelatihan_id');
    }

    public function kompetensiPelatihan(): BelongsTo
    {
        return $this->belongsTo(KompetensiPelatihan::class, 'kompetensi_pelatihan_id');
    }

    public function kompetensi(): BelongsTo
    {
        return $this->belongsTo(Kompetensi::class, 'kompetensi_id');
    }

    // ======================
    // RELASI ASRAMA
    // ======================

    public function penempatanAsrama(): HasOne
    {
        return $this->hasOne(PenempatanAsrama::class, 'peserta_id', 'peserta_id');
    }

    public function penempatanAsramaAktif(): ?PenempatanAsrama
    {
        return $this->penempatanAsrama()
            ->where('pelatihan_id', $this->pelatihan_id)
            ->latest()
            ->first();
    }

    // ======================
    // RELASI PERCOBAAN (AMBIL NILAI DARI SINI)
    // ======================

    /**
     * Semua percobaan peserta untuk pelatihan ini.
     */
    public function percobaan(): HasMany
    {
        return $this->hasMany(Percobaan::class, 'peserta_id', 'peserta_id')
            ->where('pelatihan_id', $this->pelatihan_id);
    }

    /**
     * Ambil percobaan terbaru yang sudah selesai untuk tipe tertentu (pre-test/post-test/survei).
     */
    public function latestPercobaanDone(string $tipe): ?Percobaan
    {
        return $this->percobaan()
            ->where('tipe', $tipe)
            ->whereNotNull('waktu_selesai')
            ->latest('waktu_selesai')
            ->first();
    }

    // ======================
    // HELPER: SINKRON NILAI
    // ======================

    /**
     * Sinkron nilai dari tabel percobaan ke kolom pendaftaran:
     * - nilai_pre_test
     * - nilai_post_test
     * - nilai_survey
     * - rata_rata (avg dari yang tersedia)
     *
     * Aman jika kolom tidak ada (cek via Schema::hasColumn).
     */
    public function syncNilaiDariPercobaan(): void
    {
        // Safety: kalau tabel percobaan tidak ada, langsung stop
        if (!Schema::hasTable('percobaan')) {
            return;
        }

        $pre  = $this->latestPercobaanDone('pre-test');
        $post = $this->latestPercobaanDone('post-test');
        $surv = $this->latestPercobaanDone('survei');

        // Kalau skor belum dihitung, hitung dulu (aman)
        if ($pre && $pre->skor === null) {
            $pre->hitungDanSimpanSkor($pre->tes?->passing_score);
        }
        if ($post && $post->skor === null) {
            $post->hitungDanSimpanSkor($post->tes?->passing_score);
        }
        // Survei kadang tidak perlu skor persen, tapi kalau kamu pakai skor, ini tetap aman
        if ($surv && $surv->skor === null && method_exists($surv, 'hitungDanSimpanSkor')) {
            $surv->hitungDanSimpanSkor($surv->tes?->passing_score);
        }

        $table = $this->getTable();

        // Set hanya kalau kolomnya ada
        if (Schema::hasColumn($table, 'nilai_pre_test')) {
            $this->nilai_pre_test = $pre?->skor;
        }
        if (Schema::hasColumn($table, 'nilai_post_test')) {
            $this->nilai_post_test = $post?->skor;
        }
        if (Schema::hasColumn($table, 'nilai_survey')) {
            $this->nilai_survey = $surv?->skor;
        }

        // Rata-rata: dari yang ada nilainya
        if (Schema::hasColumn($table, 'rata_rata')) {
            $vals = collect([
                Schema::hasColumn($table, 'nilai_pre_test') ? $this->nilai_pre_test : null,
                Schema::hasColumn($table, 'nilai_post_test') ? $this->nilai_post_test : null,
                Schema::hasColumn($table, 'nilai_praktek') ? $this->nilai_praktek : null,
                Schema::hasColumn($table, 'nilai_survey') ? $this->nilai_survey : null,
            ])->filter(fn ($v) => $v !== null);

            $this->rata_rata = $vals->isNotEmpty()
                ? round((float) $vals->avg(), 2)
                : null;
        }

        $this->save();
    }

    /**
     * Versi batch: sync semua pendaftaran milik peserta ini (opsional utility).
     */
    public static function syncNilaiSemuaUntukPeserta(int $pesertaId): void
    {
        if (!Schema::hasTable('pendaftaran_pelatihan')) return;

        static::query()
            ->where('peserta_id', $pesertaId)
            ->orderByDesc('id')
            ->get()
            ->each(function (self $p) {
                $p->syncNilaiDariPercobaan();
            });
    }
}
