<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

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

        // Urutan / grouping
        // 'urutan_per_bidang',
        // 'urutan_per_kompetensi',

        // Status
        'status',
        'status_pendaftaran',

        // Token assessment
        'assessment_token',
        'assessment_token_sent_at',
        'token_expires_at',

        // Nilai & Survei
        'nilai_pre_test',
        'nilai_post_test',
        'nilai_praktek',
        'rata_rata',
        'nilai_survei',
    ];

    protected $casts = [
        'tanggal_pendaftaran'      => 'datetime',
        'assessment_token_sent_at' => 'datetime',
        'token_expires_at'         => 'datetime',
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
}
