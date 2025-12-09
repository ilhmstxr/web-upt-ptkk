<?php

namespace App\Models;

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

    // ======================
    // RELATIONS
    // ======================

    public function instansi(): BelongsTo
    {
        return $this->belongsTo(Instansi::class, 'instansi_id', 'id');
    }

    public function peserta(): HasMany
    {
        return $this->hasMany(Peserta::class, 'pelatihan_id', 'id');
        // kalau tabel peserta TIDAK punya pelatihan_id, hapus relasi ini.
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

    public function kompetensiPelatihan(): HasMany
    {
        return $this->hasMany(KompetensiPelatihan::class, 'pelatihan_id', 'id');
    }

    public function pendaftaranPelatihan()
    {
        return $this->hasMany(PendaftaranPelatihan::class, 'pelatihan_id');
    }


    public function materiPelatihan(): HasMany
    {
        return $this->hasMany(MateriPelatihan::class, 'pelatihan_id', 'id');
    }
}
