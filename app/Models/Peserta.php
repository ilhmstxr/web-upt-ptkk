<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Peserta extends Model
{
    use HasFactory;

    protected $table = 'peserta';

    protected $fillable = [
        'user_id',
        'instansi_id',
        'nama',
        'nik',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'agama',
        'alamat',
        'no_hp',
    ];

    protected $casts = [
        'tanggal_lahir' => 'datetime',
        'created_at'    => 'datetime',
        'updated_at'    => 'datetime',
    ];

    // ✅ relasi ke users (bukan ke Pelatihan)
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // ✅ relasi lampiran PESERTA – pilih salah satu model yang dipakai
    // Di sini aku pakai LampiranPeserta (sesuai nama tabel migration yang tadi kamu kirim)
    public function lampiran(): HasOne
    {
        return $this->hasOne(LampiranPeserta::class, 'peserta_id');
    }

    public function instansi(): BelongsTo
    {
        return $this->belongsTo(Instansi::class, 'instansi_id');
    }

    public function pendaftarans(): HasMany
    {
        return $this->hasMany(PendaftaranPelatihan::class, 'peserta_id');
    }

    // (opsional) kalau ini mau dipertahankan biar alias dari pendaftarans()
    public function pendaftaranPelatihan(): HasMany
    {
        return $this->hasMany(PendaftaranPelatihan::class, 'peserta_id');
    }

    public function penempatanAsrama(): HasManyThrough
    {
        return $this->hasManyThrough(
            PenempatanAsrama::class,
            PendaftaranPelatihan::class,
            'peserta_id',        // FK di pendaftaran_pelatihan
            'pendaftaran_id',    // FK di penempatan_asrama
            'id',                // PK di peserta
            'id'                 // PK di pendaftaran_pelatihan
        );
    }
}
