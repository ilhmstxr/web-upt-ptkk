<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class LampiranPeserta extends Model
{
    use HasFactory;
    
    protected $table = 'lampiran_peserta';

    protected $fillable = [
        'peserta_id',
        'no_surat_tugas',
        'fc_ktp', 
        'fc_ijazah', 
        'fc_surat_tugas', 
        'fc_surat_sehat', 
        'pas_foto',
    ];

    /**
     * Relasi ke model Peserta.
     */
    public function peserta(): BelongsTo
    {
        return $this->belongsTo(Peserta::class); 
    }

    // --- ACCESSORS UNTUK MENGAMBIL URL FILE ---

    /**
     * Accessor untuk mendapatkan URL lengkap pas_foto.
     * Dipanggil di view dengan: $lampiran->pas_foto_url
     */
    protected function pasFotoUrl(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->pas_foto ? Storage::url($this->pas_foto) : null,
        );
    }

    /**
     * Accessor untuk mendapatkan URL lengkap fc_ktp.
     * Dipanggil di view dengan: $lampiran->fc_ktp_url
     */
    protected function fcKtpUrl(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->fc_ktp ? Storage::url($this->fc_ktp) : null,
        );
    }

    /**
     * Accessor untuk mendapatkan URL lengkap fc_ijazah.
     * Dipanggil di view dengan: $lampiran->fc_ijazah_url
     */
    protected function fcIjazahUrl(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->fc_ijazah ? Storage::url($this->fc_ijazah) : null,
        );
    }

    /**
     * Accessor untuk mendapatkan URL lengkap fc_surat_sehat.
     * Dipanggil di view dengan: $lampiran->fc_surat_sehat_url
     */
    protected function fcSuratSehatUrl(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->fc_surat_sehat ? Storage::url($this->fc_surat_sehat) : null,
        );
    }

    /**
     * Accessor untuk mendapatkan URL lengkap fc_surat_tugas.
     * Dipanggil di view dengan: $lampiran->fc_surat_tugas_url
     */
    protected function fcSuratTugasUrl(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->fc_surat_tugas ? Storage::url($this->fc_surat_tugas) : null,
        );
    }
}
