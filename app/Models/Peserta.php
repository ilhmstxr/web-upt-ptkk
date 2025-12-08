<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;

class Peserta extends Model
{
    use HasFactory;

    protected $table = 'peserta';

    protected $fillable = [
        'pelatihan_id',
        'instansi_id',
        'nama',
        'nik',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'agama',
        'alamat',
        'no_hp',
        'user_id',
    ];

    // ✅ cukup date biar gak ada jam/timezone ganggu login
    protected $casts = [
        'tanggal_lahir' => 'date',
        'created_at'    => 'datetime',
        'updated_at'    => 'datetime',
    ];

    public function pelatihan(): BelongsTo
    {
        return $this->belongsTo(Pelatihan::class, 'pelatihan_id');
    }

    public function instansi(): BelongsTo
    {
        return $this->belongsTo(Instansi::class, 'instansi_id');
    }

    public function lampiran(): HasOne
    {
        return $this->hasOne(LampiranPeserta::class);
    }

    public function pendaftaranPelatihan(): HasMany
    {
        return $this->hasMany(PendaftaranPelatihan::class, 'peserta_id');
    }

    public function percobaan(): HasMany
    {
        return $this->hasMany(Percobaan::class, 'peserta_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function lampiranFolder(): string
    {
        return 'lampiran/' . Str::slug($this->nama);
    }
}
