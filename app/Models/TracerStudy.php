<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TracerStudy extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'status',
        'company',
        'salary',
        'waiting_period',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
