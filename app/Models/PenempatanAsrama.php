<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

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

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

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

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    /**
     * Hanya penempatan yang masih AKTIF menginap.
     * Logika: hari ini di antara tanggal_mulai & tanggal_selesai pelatihan.
     */
    public function scopePenghuniAktif(Builder $query): void
    {
        $query->whereHas('pelatihan', function ($q) {
            // asumsi kolom di tabel pelatihan: tanggal_mulai, tanggal_selesai (DATE)
            $q->whereRaw('CURDATE() BETWEEN tanggal_mulai AND tanggal_selesai');
        });
    }

    /**
     * Hanya penempatan yang SUDAH BERLALU (riwayat).
     * Logika: hari ini > tanggal_selesai pelatihan.
     */
    public function scopeLogHistory(Builder $query): void
    {
        $query->whereHas('pelatihan', function ($q) {
            $q->whereRaw('CURDATE() > tanggal_selesai');
        });
    }
}
