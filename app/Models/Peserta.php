<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Carbon\Carbon;

class Peserta extends Model
{
    use HasFactory;

    protected $table = 'pesertas';

    protected $fillable = [
        'pelatihan_id',
        'instansi_id',
        'bidang_id',
        'nama',
        'nik',  
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'agama',
        'alamat',
        'no_hp',
        'email',
    ];

    // ✅ Tambahkan ini biar tanggal otomatis jadi Carbon
    protected $casts = [
        'tanggal_lahir' => 'datetime',
        'created_at'    => 'datetime',
        'updated_at'    => 'datetime',
    ];
    
    public function pelatihan(): BelongsTo
    {
        return $this->belongsTo(Pelatihan::class, 'pelatihan_id');
    }

    public function lampiran(): HasOne
    {
        return $this->hasOne(Lampiran::class);
    }

    public function instansi(): BelongsTo
    {
        return $this->belongsTo(Instansi::class, 'instansi_id');
    }

    public function lampiranFolder(): string
    {
        return 'lampiran/' . \Str::slug($this->nama);
    }

    public function lampirans(): array
    {
        $files = [];
        if ($this->lampiran) {
            foreach ($this->lampiran->getAttributes() as $key => $value) {
                if (in_array($key, ['id', 'peserta_id', 'created_at', 'updated_at'])) continue;
                if ($value) $files[$key] = asset('storage/' . $value);
            }
        }
        return $files;
    }
}
