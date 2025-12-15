<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KelompokPertanyaan extends Model
{
    use HasFactory;

    protected $table = 'kelompok_pertanyaans';

    protected $fillable = [
        'tes_id',
        'nama_kategori',
    ];

    public function tes()
    {
        return $this->belongsTo(Tes::class, 'tes_id');
    }

    /**
     * Dipakai Filament: ->relationship('pertanyaan')
     */
    public function pertanyaan()
    {
        return $this->hasMany(Pertanyaan::class, 'kelompok_pertanyaan_id')
            ->orderBy('nomor');
    }
}
