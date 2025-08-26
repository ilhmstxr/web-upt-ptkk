<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostTestResult extends Model
{
    protected $fillable = ['user_id', 'post_test_id', 'answer', 'is_correct'];

    // Relasi ke pertanyaan (PostTest)
    public function question()
    {
        return $this->belongsTo(PostTest::class, 'post_test_id');
    }

    // Relasi ke user
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
