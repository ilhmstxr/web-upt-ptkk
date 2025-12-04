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
    'nama_pelatihan',
    'jenis_program',
    'slug',
    'gambar',
    'status',
    'tanggal_mulai',
    'tanggal_selesai',
    'deskripsi',
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
            Percobaan::class, // Model akhir yang ingin diakses
            Tes::class,       // Model perantara/jembatan
            'pelatihan_id', // Foreign key di tabel 'tes' (perantara)
            'tes_id'          // Foreign key di tabel 'percobaan' (akhir)
        );
    }

        /**
     * Mendapatkan semua sesi/jadwal (bidang_pelatihan) di bawah pelatihan ini.
     */
    public function bidangPelatihan()
    {
        // Terhubung ke BidangPelatihan::class melalui 'pelatihan_id'
        return $this->hasMany(BidangPelatihan::class, 'pelatihan_id');
    }

    public function pendaftaranPelatihan()
    {
        return $this->hasMany(PendaftaranPelatihan::class,'pelatihan_id');
    }
    /**
     * Mendapatkan semua pendaftaran untuk pelatihan ini
     * (melalui tabel perantara bidang_pelatihan).
     */
    // public function pendaftaranPelatihan()
    // {
    //     return $this->hasManyThrough(
    //         PendaftaranPelatihan::class,      // Model Akhir
    //         BidangPelatihan::class,         // Model Perantara
    //         'pelatihan_id',                 // Foreign key di BidangPelatihan
    //         'bidang_pelatihan_id',          // Foreign key di PendaftaranPelatihan
    //         'id',                           // Local key di Pelatihan (model ini)
    //         'id'                            // Local key di BidangPelatihan
    //     );
    // }

    /**
     * Mendapatkan semua bidang (materi) yang ada di pelatihan ini
     * (melalui tabel perantara bidang_pelatihan).
     */
    // public function bidang()
    // {
    //     return $this->hasManyThrough(
    //         Bidang::class,
    //         BidangPelatihan::class,
    //         'pelatihan_id', // Foreign key di BidangPelatihan
    //         'id',           // Foreign key di Bidang
    //         'id',           // Local key di Pelatihan (model ini)
    //         'bidang_id'     // Local key di BidangPelatihan
    //     );
    // }

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
