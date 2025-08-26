<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PreTest extends Model
{
    protected $fillable = ['judul', 'deskripsi', 'soal', 'jawaban'];

    public function answers()
    {
        return $this->hasMany(PreTestAnswer::class, 'pre_test_id');
    }
}
