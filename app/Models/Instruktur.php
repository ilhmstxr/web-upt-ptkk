<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Instruktur extends Model
{
    /** @use HasFactory<\Database\Factories\InstrukturFactory> */
    use HasFactory;

    protected $table = 'instruktur';

    protected $fillable = [
        'user_id',
        'kompetensi_id',
        'nama',
        'tempat_lahir',
        'tgl_lahir',
        'jenis_kelamin',
        'agama',
        'no_hp',
    ];
    public function kompetensi()
    {
        return $this->belongsTo(Kompetensi::class, 'kompetensi_id');
    }

    public function pelatihan()
    {
        return $this->belongsToMany(Pelatihan::class, 'instruktur_pelatihan')->withPivot('kamar_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function lampiran()
    {
        return $this->hasOne(LampiranInstruktur::class);
    }
}
