<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class instruktur extends Model
{
    /** @use HasFactory<\Database\Factories\InstrukturFactory> */
    use HasFactory;

    protected $table = 'instrukturs';

    protected $fillable = [
        'bidang_id',
        'pelatihan_id',
        'nama_gelar',
        'tempat_lahir',
        'tgl_lahir',
        'jenis_kelamin',
        'agama',
        'alamat_rumah',
        'no_hp',
        'instansi',
        'npwp',
        'nik',
        'nama_bank',
        'no_rekening',
        'pendidikan_terakhir',
        'pengalaman_kerja'
    ];
    public function bidang()
    {
        return $this->belongsTo(Bidang::class, 'bidang_id');
    }

    public function pelatihan()
    {
        return $this->belongsTo(Pelatihan::class, 'pelatihan_id');
    }
}
