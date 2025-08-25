<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pertanyaan extends Model
{
    /** @use HasFactory<\Database\Factories\PertanyaanFactory> */
    use HasFactory;

    protected $fillable = ['survey_id', 'text', 'order'];

    public function surveySection()
    {
        return $this->belongsTo(Survey::class);
    }

    public function jawaban()
    {
        return $this->hasMany(Jawaban::class);
    }
}
