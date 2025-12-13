<?php

namespace App\Models;

use Carbon\Carbon; // Import Carbon secara eksplisit
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
        // 'asrama_id', // Dihapus, karena Asrama seharusnya HasMany (anak) dari Pelatihan
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
        // Panggil metode updateStatusBasedOnDate sebelum menyimpan model
        static::saving(function ($model) {
            $model->updateStatusBasedOnDate();
        });
    }

    /**
     * Mengubah nilai kolom 'status' di database berdasarkan tanggal hari ini.
     */
    public function updateStatusBasedOnDate()
    {
        // Karena sudah di-cast, properti ini adalah instance Carbon atau null
        if (! $this->tanggal_mulai || ! $this->tanggal_selesai) {
            return;
        }

        $now = now()->startOfDay();
        $start = $this->tanggal_mulai->startOfDay();
        // Menggunakan endOfDay() untuk memastikan hari terakhir pelatihan terhitung
        $end = $this->tanggal_selesai->endOfDay(); 

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
        return $this->belongsTo(\App\Models\Instansi::class, 'instansi_id', 'id');
    }

    // 🔥 KOREKSI: Relasi Asrama seharusnya HasMany (1 Pelatihan punya banyak Asrama)
    public function asramas(): HasMany
    {
        return $this->hasMany(\App\Models\Asrama::class, 'pelatihan_id', 'id');
    }

    // Relasi Peserta langsung (jika kolom pelatihan_id ada di tabel 'peserta')
    // Jika Peserta dihubungkan melalui PendaftaranPelatihan, gunakan HasManyThrough atau BelongsToMany
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
            'pelatihan_id', // FK di tabel Tes
            'tes_id',       // FK di tabel Percobaan
            'id',           // PK Pelatihan
            'id'            // PK Tes
        );
    }

    /**
     * Semua sesi/jadwal (kompetensi_pelatihan) di bawah pelatihan ini.
     */
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
                if (! $this->tanggal_mulai || ! $this->tanggal_selesai) {
                    return $this->status ?? 'Tidak Terjadwal';
                }

                $now = now();
                // Menggunakan endOfDay() untuk mencakup hari terakhir
                $end = $this->tanggal_selesai->endOfDay(); 

                if ($now->isBefore($this->tanggal_mulai)) {
                    return 'Mendatang';
                }

                // Gunakan tanggal_mulai dan tanggal_selesai (endOfDay)
                if ($now->between($this->tanggal_mulai, $end)) {
                    return 'Aktif';
                }

                return 'Selesai';
            }
        );
    }
}