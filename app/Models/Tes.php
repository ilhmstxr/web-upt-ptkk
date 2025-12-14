<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Schema;

class Tes extends Model
{
    use HasFactory;

    protected $table = 'tes';

    protected $fillable = [
        'judul',
        'deskripsi',
        'tipe',
        'sub_tipe',
        'kompetensi_id',
        'pelatihan_id',
        'durasi_menit',
        'tanggal_mulai',
        'tanggal_selesai',
    ];

    // Relasi ke Kompetensi
    public function kompetensi()
    {
        return $this->belongsTo(Kompetensi::class, 'kompetensi_id');
    }

    // Relasi ke Pelatihan
    public function pelatihan()
    {
        return $this->belongsTo(Pelatihan::class, 'pelatihan_id');
    }

    public function pertanyaan()
    {
        // Fix: Pakai One-to-Many langsung karena data soal ada di tabel pertanyaan.
        // Pivot (many-to-many) diabaikan dulu karena kosong.
        return $this->hasMany(Pertanyaan::class, 'tes_id');

        /*
        if (Schema::hasTable('tes_pertanyaan')) {
            return $this->belongsToMany(
                    Pertanyaan::class,
                    'tes_pertanyaan',
                    'tes_id',
                    'pertanyaan_id'
                )
                ->withTimestamps();
        }
        */
    }

    // (Opsional) Relasi ke Percobaan / hasil tes peserta
    public function percobaan()
    {
        return $this->hasMany(Percobaan::class, 'tes_id');
    }

    public function tipeTes()
    {
        return $this->hasMany(TipeTes::class, 'tes_id');
    }
    public function kelompokPertanyaan()
    {
        return $this->hasMany(KelompokPertanyaan::class, 'tes_id');
    }
}
