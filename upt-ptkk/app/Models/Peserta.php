<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Peserta extends Model
{
    use HasFactory;

    protected $fillable = [
        'pelatihan_id',
        'nama',
        'nik',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'agama',
        'alamat',
        'no_hp',
        'email',
        'asal_instansi',
        'alamat_instansi',
        'bidang_keahlian',
        'kelas',
        'cabang_dinas_wilayah',
        'no_surat_tugas',
        'fc_ktp',
        'fc_ijazah',
        'fc_surat_tugas',
        'fc_surat_sehat',
        'pas_foto',
        ];
        
    public function pelatihan(): BelongsTo
    {
        return $this->belongsTo(Pelatihan::class);
    }
}
