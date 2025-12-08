<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Peserta extends Model
{
    use HasFactory;

    protected $table = 'peserta';

    protected $fillable = [
        'user_id',
        'instansi_id',
        'nama',
        'nik',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'agama',
        'alamat',
        'no_hp',
    ];

    protected $casts = [
        'tanggal_lahir' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(Pelatihan::class, 'pelatihan_id');
    }


    public function lampiran(): HasOne
    {
        return $this->hasOne(LampiranPeserta::class);
    }

    public function instansi()
    {
        return $this->belongsTo(Instansi::class, 'instansi_id');
    }

    public function pendaftarans()
    {
        return $this->hasMany(PendaftaranPelatihan::class, 'peserta_id');
    }

    public function lampiran()
    {
        return $this->hasOne(Lampiran::class, 'peserta_id');
    }

    public function penempatanAsrama()
    {
        return $this->hasManyThrough(
            PenempatanAsrama::class,
            PendaftaranPelatihan::class,
            'peserta_id',        
            'pendaftaran_id',   
            'id',                
            'id'               
        );
    }

    public function pendaftaranPelatihan()
    {
        // Terhubung ke PendaftaranPelatihan::class melalui 'peserta_id'
        return $this->hasMany(PendaftaranPelatihan::class, 'peserta_id');
    }

    /**
     * Mendapatkan semua sesi/jadwal (kompetensi_pelatihan) yang pernah diikuti peserta
     * (melalui tabel pendaftaran_pelatihan).
     */

}
