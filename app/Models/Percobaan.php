<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Percobaan extends Model
{
    use HasFactory;

    protected $table = 'percobaan'; // pastikan sesuai dengan tabel di database

    protected $fillable = [
        'pesertaSurvei_id',
        'tes_id',
        'waktu_mulai',
        'waktu_selesai',
        'skor',
        'pesan_kesan',
    ];

    /**
     * Relasi ke PesertaSurvei
     */
    public function pesertaSurvei()
    {
        return $this->belongsTo(PesertaSurvei::class, 'pesertaSurvei_id', 'id');
    }

    /**
     * Relasi ke Tes
     */
    public function tes()
    {
        return $this->belongsTo(Tes::class, 'tes_id', 'id');
    }

    /**
     * Relasi ke JawabanUser
     */
    public function jawabanUser()
    {
        return $this->hasMany(JawabanUser::class, 'percobaan_id', 'id');
    }

    /**
     * Hitung jumlah jawaban benar
     */
    public function hitungSkor()
    {
        return $this->jawabanUser()
            ->whereHas('opsiJawabans', function ($q) {
                $q->where('apakah_benar', true);
            })
            ->count();
    }

    /**
     * Hitung skor dalam persen
     */
    public function hitungSkorPersen()
    {
        $total = $this->jawabanUser()->count();
        if ($total == 0) {
            return 0;
        }

        return round(($this->hitungSkor() / $total) * 100, 2);
    }
}
