<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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

    // ✅ casting tanggal lahir cukup "date"
    protected $casts = [
        'tanggal_lahir' => 'date',
        'created_at'    => 'datetime',
        'updated_at'    => 'datetime',
    ];

    // 🔗 Relasi ke Pelatihan
    public function pelatihan(): BelongsTo
    {
        return $this->belongsTo(Pelatihan::class, 'pelatihan_id');
    }

    // 🔗 Relasi ke Instansi
    public function instansi(): BelongsTo
    {
        return $this->belongsTo(Instansi::class, 'instansi_id');
    }

    public function lampiran(): HasOne
    {
        return $this->hasOne(LampiranPeserta::class);
    }

    public function lampiranFolder(): string
    {
        return 'lampiran/' . Str::slug($this->nama);
    }

    public function pertanyaan()
    {
        return $this->hasMany(Pertanyaan::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function percobaan()
    {
        return $this->hasMany(Percobaan::class, 'peserta_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // ✅ SATU relasi saja (hapus duplikat)
    public function pendaftaranPelatihan()
    {
        return $this->hasMany(PendaftaranPelatihan::class, 'peserta_id');
    }

    public function penempatanAsrama()
    {
        return $this->hasMany(PenempatanAsrama::class, 'peserta_id');
    }
}
