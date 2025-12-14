<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KelompokPertanyaan extends Model
{
    use HasFactory;

    protected $fillable = [
        'tes_id',
        'nama_kategori',
    ];

    public function tes(): BelongsTo
    {
        return $this->belongsTo(Tes::class);
    }

    public function pertanyaan(): HasMany
    {
        return $this->hasMany(Pertanyaan::class);
    }
}
