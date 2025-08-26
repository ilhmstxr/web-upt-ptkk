<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BiodataDokumen extends Model
{
    protected $table = 'biodata_dokumen';

    protected $fillable = [
        'user_id',
        'ktp_path',
        'ijazah_path',
        'surat_tugas_path',
        'surat_tugas_nomor',
        'surat_sehat_path',
        'pas_foto_path',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
