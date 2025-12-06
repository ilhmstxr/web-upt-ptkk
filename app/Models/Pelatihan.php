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
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
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
     * Mendapatkan semua pendaftaran (PendaftaranPelatihan) yang terkait dengan pelatihan ini
     * melalui tabel 'bidang_pelatihan'.
     * Catatan: Relasi ini sebelumnya dinonaktifkan, sekarang diaktifkan kembali.
     */
    public function semuaPendaftaran(): HasManyThrough
    {
        return $this->hasManyThrough(
            PendaftaranPelatihan::class,      // Model Akhir
            BidangPelatihan::class,         // Model Perantara
            'pelatihan_id',                 // Foreign key di BidangPelatihan
            'bidang_pelatihan_id',          // Foreign key di PendaftaranPelatihan
            'id',                           // Local key di Pelatihan (model ini)
            'id'                            // Local key di BidangPelatihan
        );
    }

        /**
     * Mendapatkan semua pendaftaran (PendaftaranPelatihan) yang terkait dengan pelatihan ini
     * melalui tabel 'bidang_pelatihan'.
     * Catatan: Relasi ini sebelumnya dinonaktifkan, sekarang diaktifkan kembali.
     */
    public function semuaPendaftaran(): HasManyThrough
    {
        return $this->hasManyThrough(
            PendaftaranPelatihan::class,   // Model Akhir
            BidangPelatihan::class,        // Model Perantara
            'pelatihan_id',                // Foreign key di BidangPelatihan
            'bidang_pelatihan_id',         // Foreign key di PendaftaranPelatihan
            'id',                          // Local key di Pelatihan (model ini)
            'id'                           // Local key di BidangPelatihan
        );
    }

    /**
     * Mendapatkan semua Bidang (materi) yang diajarkan dalam pelatihan ini
     * melalui tabel perantara 'bidang_pelatihan'.
     * Catatan: Relasi ini sebelumnya dinonaktifkan, sekarang diaktifkan kembali.
     */
    public function bidangMateri(): HasManyThrough
    {
        return $this->hasManyThrough(
            Bidang::class,
            BidangPelatihan::class,
            'pelatihan_id',  // Foreign key di BidangPelatihan
            'id',            // Foreign key di Bidang (field id di tabel 'bidang')
            'id',            // Local key di Pelatihan (model ini)
            'bidang_id'      // Local key (bidang_id) di BidangPelatihan
        );
    }

    // --- ACCESOR (Dulu dinonaktifkan, sekarang diaktifkan sebagai contoh) ---

    /**
     * Mendefinisikan status pelatihan secara dinamis berdasarkan tanggal.
     */
    protected function statusPelatihan(): Attribute
    {
        return Attribute::make(
            get: function (): string {
                // Gunakan kolom tanggal_mulai dan tanggal_selesai yang sudah di-cast ke 'date'
                if (!$this->tanggal_mulai || !$this->tanggal_selesai) {
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
