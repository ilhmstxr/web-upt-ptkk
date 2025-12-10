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
        'tanggal_lahir' => 'date',
    ];

    // ======================
    // RELATIONS
    // ======================

    public function pelatihan(): BelongsTo
    {
        return $this->belongsTo(Pelatihan::class, 'pelatihan_id', 'id');
    }

    public function instansi(): BelongsTo
    {
        return $this->belongsTo(Instansi::class, 'instansi_id', 'id');
    }

    public function lampiran(): HasOne
    {
        return $this->hasOne(LampiranPeserta::class, 'peserta_id', 'id');
    }

    // plural utama
    public function pendaftaranPelatihans(): HasMany
    {
        return $this->hasMany(PendaftaranPelatihan::class, 'peserta_id', 'id');
    }

    // alias singular untuk kompatibilitas kode lama
    public function pendaftaranPelatihan(): HasMany
    {
        return $this->pendaftaranPelatihans();
    }

    // penempatan terbaru per peserta
    public function penempatanAsrama(): HasOne
    {
        return $this->hasOne(PenempatanAsrama::class, 'peserta_id', 'id')
            ->latestOfMany();
    }

    public function percobaans(): HasMany
    {
        return $this->hasMany(Percobaan::class, 'peserta_id', 'id');
    }

    // alias singular kalau ada kode lama
    public function percobaan(): HasMany
    {
        return $this->percobaans();
    }

    public function pertanyaan(): HasMany
    {
        return $this->hasMany(Pertanyaan::class, 'peserta_id', 'id');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class, 'peserta_id', 'id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    // ======================
    // ACCESSORS / HELPERS
    // ======================

    // alias accessor biar tetap bisa dipanggil $peserta->gender
    public function getGenderAttribute(): ?string
    {
        return $this->jenis_kelamin;
    }

    public function lampiranFolder(): string
    {
        return 'lampiran/' . Str::slug($this->nama);
        // folder: storage/app/public/lampiran/{nama}
    }
}
