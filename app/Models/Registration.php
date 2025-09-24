<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Registration extends Model
{
    protected $fillable = [
        'name',
        'nik',
        'birth_place',
        'birth_date',
        'gender',
        'religion',
        'address',
        'phone',
        'email',
        'school_name',
        'school_address',
        'competence',
        'class',
        'dinas_branch',
        'ktp_path',
        'ijazah_path',
        'surat_tugas_path',
        'surat_tugas_nomor',
        'surat_sehat_path',
        'pas_foto_path',
    ];
}
