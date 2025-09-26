<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipeTes extends Model
{
    protected $table = 'tes_pertanyaan';

    protected $fillable = [
        'tes_id',
        'pertanyaan_id',
        'is_pre_test',
        'is_post_test',
    ];

    public function tes()
    {
        return $this->belongsTo(Tes::class, 'tes_id');
    }
}
