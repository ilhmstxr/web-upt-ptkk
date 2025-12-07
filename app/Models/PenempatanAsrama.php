<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Models\PendaftaranPelatihan;

class PenempatanAsrama extends Model
{
    use HasFactory;

    protected $table = 'penempatan_asrama';

    protected $fillable = [
        'pelatihan_id',
        'peserta_id',
        'asrama_id',
        'kamar_id',
        'periode',
    ];

    // ✅ RELATIONS
    public function pelatihan()
    {
        return $this->belongsTo(Pelatihan::class);
    }

    public function peserta()
    {
        return $this->belongsTo(Peserta::class);
    }

    public function asrama()
    {
        return $this->belongsTo(Asrama::class);
    }

    public function kamar()
    {
        return $this->belongsTo(Kamar::class);
    }

    /**
     * ✅ RELASI YANG DIBUTUHKAN FILAMENT
     * Cari pendaftaran_pelatihan yang match peserta_id + pelatihan_id
     */
    public function pendaftaranPelatihan()
    {
        return $this->hasOne(PendaftaranPelatihan::class, 'peserta_id', 'peserta_id')
            ->whereColumn(
                'pendaftaran_pelatihan.pelatihan_id',
                'penempatan_asrama.pelatihan_id'
            );
    }

    // ✅ SCOPES (return Builder biar chainable)
    public function scopePenghuniAktif(Builder $query): Builder
    {
        return $query->whereHas('pelatihan', function ($q) {
            $q->whereRaw('CURDATE() BETWEEN tanggal_mulai AND tanggal_selesai');
        });
    }

    public function scopeLogHistory(Builder $query): Builder
    {
        return $query->whereHas('pelatihan', function ($q) {
            $q->whereRaw('CURDATE() > tanggal_selesai');
        });
    }
}
