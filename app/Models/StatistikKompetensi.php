<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StatistikKompetensi extends Model
{
    protected $table = 'statistik_kompetensi';

    protected $fillable = [
        'batch',
        'nama_program',
        'kompetensi_keahlian',
        'pre_avg',
        'post_avg',
        'praktek_avg',
        'rata_kelas',
    ];
}

