<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KompetensiPelatihan extends Model
{
    use HasFactory;

    protected $table = 'kompetensi_pelatihan';

    protected $fillable = [
        'pelatihan_id',
        'kompetensi_id',
        'lokasi',
        'kota',
        'kode_kompetensi_pelatihan',
        'rata_rata_peningkatan',
        'status_performa',
        'metode',
        'file_modul',
        'tanggal',
        'jam_mulai',
        'jam_selesai',
        // 'instruktur_id' DIHAPUS karena sudah pivot
    ];

    public function pelatihan()
    {
        return $this->belongsTo(Pelatihan::class);
    }

    public function kompetensi()
    {
        return $this->belongsTo(Kompetensi::class);
    }

    public function pendaftaranPelatihan()
    {
        return $this->hasMany(PendaftaranPelatihan::class, 'kompetensi_pelatihan_id');
    }

    public function peserta()
    {
        return $this->hasManyThrough(
            Peserta::class,
            PendaftaranPelatihan::class,
            'kompetensi_pelatihan_id',
            'id',
            'id',
            'peserta_id'
        );
    }

    public function instrukturs()
    {
        return $this->belongsToMany(Instruktur::class, 'kompetensi_pelatihan_instruktur');
    }

    protected static function booted()
    {
        static::creating(function ($model) {
            $model->generateKode();
        });

        static::updating(function ($model) {
            if (empty($model->kode_kompetensi_pelatihan)) {
                $model->generateKode();
            }
        });
    }

    public function generateKode()
    {
        $pelatihan = $this->pelatihan ?? \App\Models\Pelatihan::find($this->pelatihan_id);
        $kompetensi = $this->kompetensi ?? \App\Models\Kompetensi::find($this->kompetensi_id);

        if (!$pelatihan || !$kompetensi) return;

        $jenisMap = ['AKSEL' => 'AKS', 'REGULER' => 'REG'];
        $jenisRaw = strtoupper($pelatihan->jenis_program ?? '');
        $jenis = $jenisMap[$jenisRaw] ?? substr($jenisRaw, 0, 3);

        $kodeKomp = strtoupper(trim($kompetensi->kode ?? 'XXX'));
        $tahun = $pelatihan->tanggal_mulai ? $pelatihan->tanggal_mulai->format('y') : date('y');
        $angkatan = $pelatihan->angkatan ?? '0';

        $kotaRaw = strtoupper($this->kota ?? '');
        $kotaMap = [
            'SURABAYA' => 'SBY', 'MALANG' => 'MLG', 'SIDOARJO' => 'SDA', 'GRESIK' => 'GSK',
            'KEDIRI' => 'KDR', 'MADIUN' => 'MDN', 'BLITAR' => 'BLT', 'MOJOKERTO' => 'MJK',
            'PROBOLINGGO' => 'PBL', 'PASURUAN' => 'PSR', 'BATU' => 'BT', 'BANYUWANGI' => 'BWI', 'JEMBER' => 'JMR',
        ];

        if (isset($kotaMap[$kotaRaw])) {
            $kota = $kotaMap[$kotaRaw];
        } else {
            $consonants = str_replace(['A', 'E', 'I', 'O', 'U', ' '], '', $kotaRaw);
            $kota = substr($consonants, 0, 3) ?: substr($kotaRaw, 0, 3);
        }

        $this->kode_kompetensi_pelatihan = sprintf('%s-%s-%s-%s-%s', $jenis, $kodeKomp, $tahun, $angkatan, $kota);
    }
}
