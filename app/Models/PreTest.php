<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PreTest extends Model
{
    protected $fillable = [
        'judul',
        'deskripsi',
        'soal',
        'jawaban',
        'pelatihan_id',
        'bidang_id', // relasi ke tabel Bidang
    ];

    public function answers()
    {
        return $this->hasMany(PreTestResult::class, 'pre_test_id');
    }

    public function pelatihan()
    {
        return $this->belongsTo(Pelatihan::class);
    }

    public function bidang()
    {
        return $this->belongsTo(Bidang::class);
    }
}