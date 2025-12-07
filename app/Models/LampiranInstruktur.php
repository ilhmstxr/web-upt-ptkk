<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LampiranInstruktur extends Model
{
    use HasFactory;

    protected $table = 'lampiran_instruktur';

    protected $fillable = [
        'instruktur_id',
        'cv',
        'ktp',
        'ijazah',
        'sertifikat_kompetensi',
    ];

    /**
     * Relasi ke model Instruktur.
     */
    public function instruktur(): BelongsTo
    {
        return $this->belongsTo(Instruktur::class);
    }
}
