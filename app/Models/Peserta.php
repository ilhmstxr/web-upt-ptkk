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

    protected $table = 'pesertas';

    protected $casts = [
        'tanggal_lahir' => 'date',
    ];
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

    public function pelatihan(): BelongsTo
    {
        return $this->belongsTo(Pelatihan::class, 'pelatihan_id');
    }

    public function bidang(): BelongsTo
    {
        return $this->belongsTo(Bidang::class, 'bidang_id');
    }

    public function lampiran(): HasOne
    {
        return $this->hasOne(Lampiran::class);
    }

    public function instansi(): BelongsTo
    {
        return $this->belongsTo(Instansi::class, 'instansi_id');
    }

    /**
     * Menghasilkan path folder unik untuk lampiran peserta.
     * Berguna saat proses upload file di controller.
     * Contoh penggunaan di Controller: $path = $file->store($peserta->lampiranFolder(), 'public');
     */
    public function lampiranFolder(): string
    {
        // Contoh: 'lampiran/ilham-bintang-herlambang'
        return 'lampiran/' . Str::slug($this->nama); 
    }
}
