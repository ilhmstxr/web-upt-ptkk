<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Instansi extends Model
{
    use HasFactory;

    protected $table = 'instansi';

    protected $fillable = [
        'asal_instansi',
        'alamat_instansi',
        'bidang_keahlian',
        'kelas',
        'cabangDinas_id',
    ];

    public function cabangDinas()
    {
        return $this->belongsTo(CabangDinas::class, 'cabangDinas_id');
    }

    public function pesertas(): HasMany
    {
        return $this->hasMany(Peserta::class, 'instansi_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
