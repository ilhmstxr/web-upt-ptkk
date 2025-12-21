<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

use Illuminate\Database\Eloquent\Casts\Attribute;

class Pelatihan extends Model
{
    use HasFactory;

    protected $table = 'pelatihan';

    protected $fillable = [
        'instansi_id',
        'angkatan',
        'jenis_program',
        'nama_pelatihan',
        'slug',
        'gambar',
        'status',
        'tanggal_mulai',
        'tanggal_selesai',
        'deskripsi',
        'jumlah_peserta',
        'sasaran',
        'syarat_ketentuan',
        'jadwal_text',
        'lokasi_text',
        'lokasi', // [NEW] Kolom lokasi fisik pelatihan (kota/gedung)
        'nama_cp',
        'no_cp',
    ];

    protected $casts = [
        'tanggal_mulai'   => 'date',
        'tanggal_selesai' => 'date',
    ];

    protected static function booted()
    {
        static::saving(function (self $model) {
            $model->updateStatusBasedOnDate();
        });
    }

    /**
     * Update nilai kolom status berdasarkan tanggal
     */
    public function updateStatusBasedOnDate(): void
    {
        if (!$this->tanggal_mulai || !$this->tanggal_selesai) {
            return;
        }

        $now   = now()->startOfDay();
        $start = $this->tanggal_mulai->startOfDay();
        $end   = $this->tanggal_selesai->endOfDay();

        if ($now->lt($start)) {
            $this->status = 'belum dimulai';
            return;
        }

        if ($now->between($start, $end)) {
            $this->status = 'aktif';
            return;
        }

        $this->status = 'selesai';
    }

    // ======================
    // RELATIONS
    // ======================

    public function instansi(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Instansi::class, 'instansi_id', 'id');
    }

    /**
     * ✅ 1 Pelatihan punya banyak Asrama
     * FK: asrama.pelatihan_id -> pelatihan.id
     */
    public function asramas(): HasMany
    {
        return $this->hasMany(\App\Models\Asrama::class, 'pelatihan_id', 'id');
    }

    /**
     * Peserta (kalau tabel peserta punya pelatihan_id)
     */
    public function peserta(): HasMany
    {
        return $this->hasMany(\App\Models\Peserta::class, 'pelatihan_id', 'id');
    }

    public function tes(): HasMany
    {
        return $this->hasMany(\App\Models\Tes::class, 'pelatihan_id', 'id');
    }

    public function percobaans(): HasManyThrough
    {
        return $this->hasManyThrough(
            \App\Models\Percobaan::class,
            \App\Models\Tes::class,
            'pelatihan_id', // FK di tabel tes
            'tes_id',       // FK di tabel percobaan
            'id',           // PK pelatihan
            'id'            // PK tes
        );
    }

    public function kompetensiPelatihan(): HasMany
    {
        return $this->hasMany(\App\Models\KompetensiPelatihan::class, 'pelatihan_id', 'id');
    }

    public function pendaftaranPelatihan(): HasMany
    {
        return $this->hasMany(\App\Models\PendaftaranPelatihan::class, 'pelatihan_id', 'id');
    }

    public function materiPelatihan(): HasMany
    {
        return $this->hasMany(\App\Models\MateriPelatihan::class, 'pelatihan_id', 'id');
    }

    // ======================
    // ACCESSORS / ATTRIBUTES
    // ======================

    /**
     * Status pelatihan dinamis berdasarkan tanggal.
     */
    protected function statusPelatihan(): Attribute
    {
        return Attribute::make(
            get: function (): string {
                if (!$this->tanggal_mulai || !$this->tanggal_selesai) {
                    return $this->status ?? 'Tidak Terjadwal';
                }

                $now = now();
                $end = $this->tanggal_selesai->endOfDay();

                if ($now->isBefore($this->tanggal_mulai)) {
                    return 'Mendatang';
                }

                if ($now->between($this->tanggal_mulai, $end)) {
                    return 'Aktif';
                }

                return 'Selesai';
            }
        );
    }
}
