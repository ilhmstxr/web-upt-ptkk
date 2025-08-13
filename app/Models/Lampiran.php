<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lampiran extends Model
{
    /** @use HasFactory<\Database\Factories\LampiranFactory> */
    use HasFactory;
    protected $fillable = [
        'no_surat_tugas',
        'fc_ktp', // Path untuk file KTP
        'fc_ijazah', // Path untuk file Ijazah
        'fc_surat_tugas', // Path untuk file Surat Tugas
        'fc_surat_sehat', // Path untuk file Surat Sehat
        'pas_foto', // Path untuk pas foto
    ];
}
