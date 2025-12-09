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

    // Pastikan nama tabel benar
    protected $table = 'pelatihan';

    protected $fillable = [
        'instansi_id',
        'asrama_id', // DITAMBAHKAN (Dari migration)
        'angkatan',
        'jenis_program',
        'nama_pelatihan',
        'slug',
        'gambar',
        'status',
        'tanggal_mulai',
        'tanggal_selesai',
        'deskripsi',
        'syarat_ketentuan', // DITAMBAHKAN (Dari migration)
        'jadwal_text',       // DITAMBAHKAN (Dari migration)
        'lokasi_text',       // DITAMBAHKAN (Dari migration)
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

    // --- RELASI BELONGS TO ---

    public function instansi(): BelongsTo
    {
        return $this->belongsTo(Instansi::class);
    }

    /**
     * Relasi ke model Asrama (dari asrama_id).
     */
    public function asrama(): BelongsTo
    {
        return $this->belongsTo(Asrama::class);
    }

    // --- RELASI HAS MANY ---

    public function peserta(): HasMany
    {
        return $this->hasMany(Peserta::class);
    }

    public function tes(): HasMany
    {
        return $this->hasMany(Tes::class);
    }

    /**
     * Mendapatkan semua sesi/jadwal (BidangPelatihan) di bawah pelatihan ini.
     */
    public function bidangPelatihan(): HasMany
    {
        // Secara default sudah mencari 'pelatihan_id'
        return $this->hasMany(BidangPelatihan::class);
    }

    // --- RELASI HAS MANY THROUGH ---

    /**
     * Mendapatkan semua Percobaan (tests/quizzes results) yang terkait dengan pelatihan ini
     * melalui tabel 'tes'.
     */
    public function percobaans(): HasManyThrough
    {
        return $this->hasManyThrough(
            Percobaan::class,
            Tes::class,
            'pelatihan_id', // Foreign key di tabel 'tes' (perantara)
            'tes_id'        // Foreign key di tabel 'percobaan' (akhir)
        );
    }

        /**
     * Mendapatkan semua sesi/jadwal (kompetensi_pelatihan) di bawah pelatihan ini.
     */
    /**
     * Mendapatkan semua sesi/jadwal (kompetensi_pelatihan) di bawah pelatihan ini.
     */
    public function kompetensiPelatihan()
    {
        // Terhubung ke KompetensiPelatihan::class melalui 'pelatihan_id'
        return $this->hasMany(KompetensiPelatihan::class, 'pelatihan_id');
    }

    public function pendaftaranPelatihan()
    {
        return $this->hasMany(PendaftaranPelatihan::class,'pelatihan_id');
    }
    /**
     * Mendapatkan semua pendaftaran untuk pelatihan ini
     * (melalui tabel perantara kompetensi_pelatihan).
     */
    // public function pendaftaranPelatihan()
    // {
    //     return $this->hasManyThrough(
    //         PendaftaranPelatihan::class,      // Model Akhir
    //         KompetensiPelatihan::class,         // Model Perantara
    //         'pelatihan_id',                 // Foreign key di KompetensiPelatihan
    //         'kompetensi_pelatihan_id',          // Foreign key di PendaftaranPelatihan
    //         'id',                           // Local key di Pelatihan (model ini)
    //         'id'                            // Local key di KompetensiPelatihan
    //     );
    // }

    /**
     * Mendapatkan semua kompetensi (materi) yang ada di pelatihan ini
     * (melalui tabel perantara kompetensi_pelatihan).
     */
    // public function kompetensi()
    // {
    //     return $this->hasManyThrough(
    //         Kompetensi::class,
    //         KompetensiPelatihan::class,
    //         'pelatihan_id', // Foreign key di KompetensiPelatihan
    //         'id',           // Foreign key di Kompetensi
    //         'id',           // Local key di Pelatihan (model ini)
    //         'kompetensi_id'     // Local key di KompetensiPelatihan
    //     );
    // }

    /**
     * Mendefinisikan status pelatihan secara dinamis berdasarkan tanggal.
     */
    protected function statusPelatihan(): Attribute
    {
        return Attribute::make(
            get: function (): string {
                // Gunakan kolom tanggal_mulai dan tanggal_selesai yang sudah di-cast ke 'date'
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
            },
        );
    }
}
