<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MateriProgress extends Model
{
    use HasFactory;

    protected $table = 'materi_progress';

    protected $fillable = [
        'pendaftaran_pelatihan_id',
        'materi_id',
        'is_completed',
        'completed_at',
    ];

    protected $casts = [
        'is_completed' => 'boolean',
        'completed_at' => 'datetime',
    ];

    public function pendaftaran()
    {
        return $this->belongsTo(PendaftaranPelatihan::class, 'pendaftaran_pelatihan_id');
    }

    public function materi()
    {
        return $this->belongsTo(\App\Models\MateriPelatihan::class, 'materi_id');
    }
}
