<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PreTestAnswer extends Model
{
    protected $fillable = ['user_id', 'pre_test_id', 'answer', 'is_correct'];

    // Relasi ke pertanyaan (PreTest)
    public function question()
    {
        return $this->belongsTo(PreTest::class, 'pre_test_id');
    }

    // Relasi ke user
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
