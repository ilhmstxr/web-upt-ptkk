<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CeritaKami extends Model
{
    protected $table = 'cerita_kamis';

    protected $fillable = [
        'title', 'slug', 'excerpt', 'content', 'is_published'
    ];

    protected $casts = [
        'is_published' => 'boolean',
    ];
}
