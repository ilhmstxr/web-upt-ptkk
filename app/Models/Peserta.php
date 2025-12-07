<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;
use Carbon\Carbon;

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

    // ✅ otomatis casting ke Carbon
    protected $casts = [
        'tanggal_lahir' => 'datetime',
        'created_at'    => 'datetime',
        'updated_at'    => 'datetime',
    ];

    // 🔗 Relasi ke Pelatihan
    public function pelatihan(): BelongsTo
    {
        return $this->belongsTo(Pelatihan::class, 'pelatihan_id');
    }


    public function lampiran(): HasOne
    {
        return $this->hasOne(LampiranPeserta::class);
    }

    // 🔗 Relasi ke Instansi
    public function instansi(): BelongsTo
    {
        return $this->belongsTo(Instansi::class, 'instansi_id');
    }

    public function lampiranFolder(): string
    {
        return 'lampiran/' . Str::slug($this->nama); // folder storage/app/public/lampiran/{nama}
    }

    // public function lampiran(): array
    // {
    //     $files = [];
    //     if ($this->lampiran) {
    //         foreach ($this->lampiran->getAttributes() as $key => $value) {
    //             if (in_array($key, ['id', 'peserta_id', 'created_at', 'updated_at'])) continue;
    //             if ($value) $files[$key] = asset('storage/' . $value);
    //         }
    //     }
    //     return $files;
    // }

    public function pertanyaan()
    {
        return $this->hasMany(Pertanyaan::class);
    }

    // Relasi ke komentar survei (sebelumnya ada di Participant)
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

    public function pendaftaranPelatihan()
    {
        // Terhubung ke PendaftaranPelatihan::class melalui 'peserta_id'
        return $this->hasMany(PendaftaranPelatihan::class, 'peserta_id');
    }

        public function penempatanAsrama()
    {
        return $this->hasMany(PenempatanAsrama::class, 'peserta_id');
    }


    /**
     * Mendapatkan semua sesi/jadwal (kompetensi_pelatihan) yang pernah diikuti peserta
     * (melalui tabel pendaftaran_pelatihan).
     */

}
