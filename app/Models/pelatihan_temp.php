<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pelatihan extends Model
{
    use HasFactory;

    protected $table = 'pelatihan';

    protected $fillable = [
        'instansi_id',
        'nama_pelatihan',
        'jenis_program',
        'slug',
        'gambar',
        'status',
        'tanggal_mulai',
        'tanggal_selesai',
        'deskripsi',
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
    ];

    // 🔹 Relasi ke Instansi
    public function instansi()
    {
        return $this->belongsTo(Instansi::class);
    }

    // 🔹 Relasi many-to-many ke Bidang
    public function bidang()
    {
        return $this->belongsToMany(
            Bidang::class,
            'bidang_pelatihan',
            'pelatihan_id',   // foreign key di pivot untuk Pelatihan
            'bidang_id'       // foreign key di pivot untuk Bidang
        );
    }

    // 🔹 Relasi ke Peserta
    public function peserta(): HasMany
    {
        return $this->hasMany(Peserta::class, 'pelatihan_id');
    }

    // 🔹 Relasi ke PesertaSurvei
    public function pesertaSurveis(): HasMany
    {
        return $this->hasMany(PesertaSurvei::class, 'pelatihan_id');
    }
}
