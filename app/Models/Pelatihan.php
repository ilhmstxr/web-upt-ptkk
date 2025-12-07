<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
    ];

    public function instansi()
    {
        return $this->belongsTo(Instansi::class);
    }

    public function peserta()
    {
        return $this->hasMany(Peserta::class);
    }

    public function tes()
    {
        return $this->hasMany(Tes::class);
    }

    public function percobaans()
    {
        return $this->hasManyThrough(
            Percobaan::class,
            Tes::class,
            'pelatihan_id',
            'tes_id'
        );
    }

    public function kompetensiPelatihan()
    {
        return $this->hasMany(KompetensiPelatihan::class, 'pelatihan_id');
    }

    public function pendaftaranPelatihan()
    {
        return $this->hasMany(PendaftaranPelatihan::class,'pelatihan_id');
    }

    // ✅ TAMBAHKAN INI
    public function materiPelatihan()
    {
        return $this->hasMany(\App\Models\MateriPelatihan::class, 'pelatihan_id');
    }
}
