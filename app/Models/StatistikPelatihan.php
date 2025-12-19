<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StatistikPelatihan extends Model
{
    protected $table = 'statistik_pelatihan';

    protected $fillable = [
        'batch',
        'pelatihan_id',
        'pre_avg',
        'post_avg',
        'praktek_avg',
        'rata_avg',
        'foto_galeri',
    ];

    protected $casts = [
        'foto_galeri' => 'array',
    ];

    public function pelatihan(): BelongsTo
    {
        return $this->belongsTo(Pelatihan::class, 'pelatihan_id', 'id');
    }
}
