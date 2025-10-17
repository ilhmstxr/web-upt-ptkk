<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pelatihan extends Model
{
    use HasFactory;

    protected $table = 'pelatihan';

    protected $guarded = [
        'nama_pelatihan',
        'jenis_program',
        'slug',
        'gambar',
        'status',
        'tanggal_mulai',
        'tanggal_selesai',
        'deskripsi',
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
            Percobaan::class, // Model akhir yang ingin diakses
            Tes::class,       // Model perantara/jembatan
            'pelatihan_id', // Foreign key di tabel 'tes' (perantara)
            'tes_id'          // Foreign key di tabel 'percobaan' (akhir)
        );
    }
    public function bidang()
    {
        return $this->belongsToMany(Bidang::class, 'bidang_pelatihan');
    }

    protected function status(): Attribute
    {
        return Attribute::make(
            get: function (): string {
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
