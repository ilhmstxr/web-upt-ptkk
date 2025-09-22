<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PivotJawaban extends Model
{
    protected $table = 'pivot_jawaban';
    
    protected $fillable = [
        'pertanyaan_id',
        'template_pertanyaan_id',
    ];
    
    public function pertanyaan()
    {
        return $this->belongsTo(Pertanyaan::class, 'pertanyaan_id');
    }

    public function templatePertanyaan()
    {
        return $this->belongsTo(Pertanyaan::class, 'template_pertanyaan_id');
    }
}
