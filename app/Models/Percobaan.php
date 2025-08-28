<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Percobaan extends Model
{
    use HasFactory;

    protected $table = 'percobaans';

    protected $fillable = [
        'peserta_id',
        'tes_id',
        'waktu_mulai',
        'waktu_selesai',
        'skor',
        'pesan_kesan',
    ];

    // Relasi ke peserta
    public function peserta()
    {
        return $this->belongsTo(Peserta::class, 'peserta_id');
    }

    // Relasi ke tes
    public function tes()
    {
        return $this->belongsTo(Tes::class, 'tes_id');
    }

    // Relasi ke jawaban user (konsisten dengan controller)
    public function jawabanUser()
    {
        return $this->hasMany(JawabanUser::class, 'percobaan_id');
    }

    // Hitung jumlah jawaban benar
    public function hitungSkor()
    {
        return $this->jawabanUser()->whereHas('opsiJawabans', function($q) {
            $q->where('apakah_benar', true);
        })->count();
    }

    // Hitung skor dalam persen
    public function hitungSkorPersen()
    {
        $total = $this->jawabanUser()->count();
        if ($total == 0) return 0;
        return round($this->hitungSkor() / $total * 100, 2);
    }
}
