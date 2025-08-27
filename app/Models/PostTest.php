<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostTest extends Model
{
    protected $fillable = [
        'nomor',
        'question',
        'option_a',
        'option_b',
        'option_d',
        'correct_answer',
        'pelatihan_id',
        'bidang_id', // relasi ke tabel Bidang
    ];


    public function answers()
    {
        return $this->hasMany(PostTestResult::class);
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
