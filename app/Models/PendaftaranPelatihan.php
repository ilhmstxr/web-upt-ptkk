<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PendaftaranPelatihan extends Model
{
    use HasFactory;

    protected $table = 'pendaftaran_pelatihan';

    /**
     * ======================
     * MASS ASSIGNMENT
     * ======================
     */
    protected $fillable = [
        'peserta_id',
        'pelatihan_id',
        'kompetensi_id',
        'kompetensi_pelatihan_id',

        'nomor_registrasi',
        'tanggal_pendaftaran',
        'kelas',

        'status',
        'status_pendaftaran',

        'assessment_token',
        'assessment_token_sent_at',
        'token_expires_at',

        'nilai_pre_test',
        'nilai_post_test',
        'nilai_praktek',
        'rata_rata',
        'nilai_survey',
    ];

    /**
     * ======================
     * CASTS
     * ======================
     */
    protected $casts = [
        'tanggal_pendaftaran'      => 'datetime',
        'assessment_token_sent_at' => 'datetime',
        'token_expires_at'         => 'datetime',

        'nilai_pre_test'   => 'float',
        'nilai_post_test'  => 'float',
        'nilai_praktek'    => 'float',
        'rata_rata'        => 'float',
        'nilai_survey'     => 'float',
    ];

    /**
     * ======================
     * RELASI UTAMA
     * ======================
     */

    public function peserta(): BelongsTo
    {
        return $this->belongsTo(Peserta::class, 'peserta_id');
    }

    public function pelatihan(): BelongsTo
    {
        return $this->belongsTo(Pelatihan::class, 'pelatihan_id');
    }

    public function kompetensi(): BelongsTo
    {
        return $this->belongsTo(Kompetensi::class, 'kompetensi_id');
    }

    public function kompetensiPelatihan(): BelongsTo
    {
        return $this->belongsTo(
            KompetensiPelatihan::class,
            'kompetensi_pelatihan_id'
        );
    }

    /**
     * ======================
     * RELASI ASRAMA (BERSIH)
     * ======================
     */

    /**
     * Semua penempatan asrama peserta ini
     * (bisa lebih dari satu jika ikut banyak pelatihan).
     */
    public function penempatanAsramas(): HasMany
    {
        return $this->hasMany(
            PenempatanAsrama::class,
            'peserta_id',
            'peserta_id'
        );
    }

    /**
     * ======================
     * ACCESSOR (LOGIKA AKTIF)
     * ======================
     */

    /**
     * Penempatan asrama AKTIF untuk pendaftaran ini.
     *
     * ❗ BUKAN relasi
     * ❗ TIDAK dipakai di with()
     * ✅ Aman & jelas
     */
    public function getPenempatanAsramaAktifAttribute(): ?PenempatanAsrama
    {
        if (! $this->relationLoaded('penempatanAsramas')) {
            $this->load('penempatanAsramas');
        }

        return $this->penempatanAsramas
            ->where('pelatihan_id', $this->pelatihan_id)
            ->sortByDesc('id')
            ->first();
    }

    /**
     * ======================
     * HELPER STATUS
     * ======================
     */

    public function isPending(): bool
    {
        return strtolower((string) $this->status_pendaftaran) === 'pending';
    }

    public function isVerified(): bool
    {
        return in_array(
            strtolower((string) $this->status_pendaftaran),
            ['verifikasi', 'diterima'],
            true
        );
    }
}
