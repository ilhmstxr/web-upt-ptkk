<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pelatihan extends Model
{
    use HasFactory;

    protected $table = 'pelatihan';

    protected $fillable = [
        'instansi_id', // diganti dari bidang_id agar sesuai migration
        'nama_pelatihan',
        'slug',
        'gambar',
        'tanggal_mulai',
        'tanggal_selesai',
        'deskripsi',
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
    ];

    // Relasi ke instansi
    public function instansi(): BelongsTo
    {
        return $this->belongsTo(Instansi::class);
    }

    // Relasi ke peserta
    public function pesertas(): HasMany
    {
        return $this->hasMany(Peserta::class, 'pelatihan_id');
    }

    public function pesertaSurveis(): HasMany
    {
        // GANTI Peserta::class menjadi PesertaSurvei::class
        return $this->hasMany(PesertaSurvei::class, 'pelatihan_id');
    }   
}
