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

    // 🔗 Relasi ke Instansi
    public function instansi(): BelongsTo
    {
        return $this->belongsTo(Instansi::class, 'instansi_id');
    }

    // 🔗 Relasi ke Bidang
    public function bidang(): BelongsTo
    {
        return $this->belongsTo(Bidang::class, 'bidang_id');
    }

    // 🔗 Relasi ke Lampiran
    public function lampiran(): HasOne
    {
        return $this->hasOne(Lampiran::class, 'peserta_id');
    }

    // 📁 Folder Lampiran
    public function lampiranFolder(): string
    {
        return 'lampiran/' . Str::slug($this->nama);
    }

    // 📂 Ambil semua file lampiran
    public function getLampirans(): array
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
