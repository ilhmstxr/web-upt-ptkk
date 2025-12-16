<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kompetensi extends Model
{
    use HasFactory;

    protected $table = 'kompetensi';

    protected $fillable = [
        'nama_kompetensi',
        'deskripsi',
        'kode',
        'kelas_keterampilan',
        'gambar',
    ];

    protected $casts = [
        'kelas_keterampilan' => 'integer',
    ];

    public function kompetensiPelatihan()
    {
        return $this->hasMany(KompetensiPelatihan::class, 'kompetensi_id');
    }

    public function pendaftaranPelatihan()
    {
        return $this->hasManyThrough(
            PendaftaranPelatihan::class,
            KompetensiPelatihan::class,
            'kompetensi_id',            // FK di tabel kompetensi_pelatihan
            'kompetensi_pelatihan_id',  // FK di tabel pendaftaran_pelatihan
            'id',                       // PK di tabel kompetensi
            'id'                        // PK di tabel kompetensi_pelatihan
        );
    }
}
