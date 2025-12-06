<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KepalaUpt extends Model
{
    // Nama tabel (opsional, karena Laravel otomatis pakai 'kepala_upts')
    protected $table = 'kepala_upts';

    // Kolom yang bisa diisi mass-assignment
    protected $fillable = [
        'nama_kepala_upt',
        'foto',
        'sambutan',
    ];

}
