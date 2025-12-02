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
        'kota',
        'kota_id',
        // 'kelas', // Removed
        'cabangDinas_id',
        'user_id',
        // Removed jenis_instansi and status_kerjasama
        'no_telepon',
        'email',
    ];

    public function cabangDinas(): BelongsTo
    {
        return $this->belongsTo(CabangDinas::class, 'cabangDinas_id');
    }

    public function peserta(): HasMany
    {
        return $this->hasMany(Peserta::class, 'instansi_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Opsional: scope pencarian sekolah
    public function scopeCariSekolah($query, string $q)
    {
        $q = mb_strtolower(trim($q));
        return $query->whereRaw('LOWER(asal_instansi) LIKE ?', ['%'.str_replace(' ', '%', $q).'%']);
    }
}
