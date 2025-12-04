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
        'pendaftaran_id',
        'kamar_id',
    ];

    public function pendaftaranPelatihan()
    {
        return $this->belongsTo(PendaftaranPelatihan::class, 'pendaftaran_id');
    }

    public function kamar()
    {
        return $this->belongsTo(Kamar::class, 'kamar_id');
    }

    /**
     * Scope a query to only include active residents.
     * Logic: Current date is between training start and end date.
     */
    public function scopePenghuniAktif(Builder $query): void
    {
        $query->whereHas('pendaftaranPelatihan.pelatihan', function ($q) {
            $q->whereRaw('CURDATE() BETWEEN tanggal_mulai AND tanggal_selesai');
        });
    }

    /**
     * Scope a query to only include history logs (checked out).
     * Logic: Current date is past the training end date.
     */
    public function scopeLogHistory(Builder $query): void
    {
        $query->whereHas('pendaftaranPelatihan.pelatihan', function ($q) {
            $q->whereRaw('CURDATE() > tanggal_selesai');
        });
    }
}
