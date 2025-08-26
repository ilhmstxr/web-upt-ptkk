<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostTest extends Model
{
    protected $fillable = [
        'question', 'option_a', 'option_b', 'option_c', 'option_d', 'correct_answer'
    ];

    public function answers()
    {
        return $this->hasMany(PostTestAnswer::class);
    }
}

