<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JawabanUser extends Model
{
    use HasFactory;

    protected $table = 'jawaban_users';

    protected $fillable = [
        'opsi_jawabans_id',
        'pertanyaan_id',
        'percobaan_id',
        'nilai_jawaban',
    ];

    public function percobaan()
    {
        return $this->belongsTo(Percobaan::class, 'percobaan_id');
    }

    public function pertanyaan()
    {
        return $this->belongsTo(Pertanyaan::class, 'pertanyaan_id');
    }

    public function opsiJawabans()
    {
        return $this->belongsTo(OpsiJawabans::class, 'opsi_jawabans_id');
    }
}
