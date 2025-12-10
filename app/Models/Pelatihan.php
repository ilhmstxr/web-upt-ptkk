<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Pelatihan extends Model
{
    use HasFactory;

    protected $table = 'pelatihan';

    protected $fillable = [
        'instansi_id',
        'asrama_id',
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
    ];

    protected $casts = [
        'tanggal_mulai'   => 'date',
        'tanggal_selesai' => 'date',
    ];

    protected static function booted()
    {
        static::saving(function ($model) {
            $model->updateStatusBasedOnDate();
        });
    }

    public function updateStatusBasedOnDate()
    {
        // Jika tanggal tidak lengkap, jangan diubah otomatis (atau set ke draft/belum dimulai?)
        if (! $this->tanggal_mulai || ! $this->tanggal_selesai) {
            return;
        }

        $now = now()->startOfDay();
        $start = $this->tanggal_mulai instanceof \Carbon\Carbon ? $this->tanggal_mulai->startOfDay() : \Carbon\Carbon::parse($this->tanggal_mulai)->startOfDay();
        $end = $this->tanggal_selesai instanceof \Carbon\Carbon ? $this->tanggal_selesai->endOfDay() : \Carbon\Carbon::parse($this->tanggal_selesai)->endOfDay();

        if ($now->lt($start)) {
            $this->status = 'belum dimulai';
        } elseif ($now->between($start, $end)) {
            $this->status = 'aktif';
        } else {
            $this->status = 'selesai';
        }
    }

    // ======================
    // RELATIONS
    // ======================

    public function instansi(): BelongsTo
    {
        // versi 1 pakai FK eksplisit, versi 2 default.
        // aman pakai eksplisit biar jelas
        return $this->belongsTo(Instansi::class, 'instansi_id', 'id');
    }

    public function asrama(): BelongsTo
    {
        return $this->belongsTo(Asrama::class, 'asrama_id', 'id');
    }

    public function peserta(): HasMany
    {
        return $this->hasMany(Peserta::class, 'pelatihan_id', 'id');
    }

    public function tes(): HasMany
    {
        return $this->hasMany(Tes::class, 'pelatihan_id', 'id');
    }

    public function percobaans(): HasManyThrough
    {
        return $this->hasManyThrough(
            Percobaan::class,
            Tes::class,
            'pelatihan_id', // FK di tabel tes
            'tes_id',       // FK di tabel percobaan
            'id',           // PK pelatihan
            'id'            // PK tes
        );
    }

    /**
     * Semua sesi/jadwal (kompetensi_pelatihan) di bawah pelatihan ini.
     */
    public function kompetensiPelatihan(): HasMany
    {
        return $this->hasMany(KompetensiPelatihan::class, 'pelatihan_id', 'id');
    }

    public function pendaftaranPelatihan(): HasMany
    {
        return $this->hasMany(PendaftaranPelatihan::class, 'pelatihan_id', 'id');
    }

    public function materiPelatihan(): HasMany
    {
        return $this->hasMany(MateriPelatihan::class, 'pelatihan_id', 'id');
    }



    // ======================
    // ACCESSORS / ATTRIBUTES
    // ======================

    /**
     * Status pelatihan dinamis berdasarkan tanggal.
     * Jika tanggal belum lengkap, fallback ke kolom status / "Tidak Terjadwal".
     */
    protected function statusPelatihan(): Attribute
    {
        return Attribute::make(
            get: function (): string {
                if (! $this->tanggal_mulai || ! $this->tanggal_selesai) {
                    return $this->status ?? 'Tidak Terjadwal';
                }

                $now = now();

                if ($now->isBefore($this->tanggal_mulai)) {
                    return 'Mendatang';
                }

                if ($now->between($this->tanggal_mulai, $this->tanggal_selesai)) {
                    return 'Aktif';
                }

                return 'Selesai';
            }
        );
    }
}
