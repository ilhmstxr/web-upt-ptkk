<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Lampiran extends Model
{
    use HasFactory;
    
    protected $table = 'lampirans';

    protected $fillable = [
        'peserta_id',
        'no_surat_tugas',
        'fc_ktp', 
        'fc_ijazah', 
        'fc_surat_tugas', 
        'fc_surat_sehat', 
        'pas_foto',
    ];

    /**
     * Mendefinisikan relasi bahwa satu Lampiran milik satu Peserta.
     */
    public function peserta(): BelongsTo // Nama fungsi diubah menjadi tunggal
    {
        // Nama model diperbaiki menjadi 'Peserta' (kapital)
        return $this->belongsTo(Peserta::class); 
    }
}