<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Survey extends Model
{
    /** @use HasFactory<\Database\Factories\SurveyFactory> */
    use HasFactory;

    protected $fillable = ['title', 'slug', 'order', 'description'];

    public function pertanyaan()
    {
        return $this->hasMany(Pertanyaan::class);
    }
}
