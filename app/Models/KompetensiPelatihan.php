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
        'instruktur_id',
    ];

    public function pelatihan()
    {
        return $this->belongsTo(Pelatihan::class);
    }


    public function kompetensi()
    {
        return $this->belongsTo(Kompetensi::class);
    }

    /**
     * Mendapatkan semua pendaftaran untuk sesi/jadwal ini.
     */
    public function pendaftaranPelatihan()
    {
        // Terhubung ke PendaftaranPelatihan::class melalui 'kompetensi_pelatihan_id'
        return $this->hasMany(PendaftaranPelatihan::class, 'kompetensi_pelatihan_id');
    }

    /**
     * Mendapatkan semua peserta yang mendaftar di sesi ini
     * (melalui tabel pendaftaran_pelatihan).
     */
    public function peserta()
    {
        return $this->hasManyThrough(
            Peserta::class,
            PendaftaranPelatihan::class,
            'kompetensi_pelatihan_id', // Foreign key di PendaftaranPelatihan
            'id',                    // Foreign key di Peserta
            'id',                    // Local key di KompetensiPelatihan
            'peserta_id'             // Local key di PendaftaranPelatihan
        );
    }

    protected static function booted()
    {
        static::creating(function ($model) {
            $model->generateKode();
        });

        static::updating(function ($model) {
            // Jika kode masih kosong saat update, generate ulang
            if (empty($model->kode_kompetensi_pelatihan)) {
                $model->generateKode();
            }
        });
    }

    public function generateKode()
    {
        $pelatihan = $this->pelatihan ?? \App\Models\Pelatihan::find($this->pelatihan_id);
        $kompetensi = $this->kompetensi ?? \App\Models\Kompetensi::find($this->kompetensi_id);

        if (! $pelatihan || ! $kompetensi) {
            return;
        }

        // 1. Jenis Program (AKSEL -> AKS, REGULER -> REG)
        // Ambil 3 huruf pertama uppercase sebagai fallback
        $jenisMap = [
            'AKSEL' => 'AKS',
            'REGULER' => 'REG',
        ];
        $jenisRaw = strtoupper($pelatihan->jenis_program ?? '');
        $jenis = $jenisMap[$jenisRaw] ?? substr($jenisRaw, 0, 3);

        // 2. Kode Kompetensi (dari tabel kompetensi kolom kode)
        $kodeKomp = strtoupper($kompetensi->kode ?? 'XXX');

        // 3. Tahun (2 digit terakhir)
        $tahun = $pelatihan->tanggal_mulai ? $pelatihan->tanggal_mulai->format('y') : date('y');

        // 4. Angkatan
        $angkatan = $pelatihan->angkatan ?? '0';

        // 5. Kota (SURABAYA -> SBY)
        $kotaRaw = strtoupper($this->kota ?? '');
        $kotaMap = [
            'SURABAYA' => 'SBY',
            'MALANG' => 'MLG',
            'SIDOARJO' => 'SDA',
            'GRESIK' => 'GSK',
            'KEDIRI' => 'KDR',
            'MADIUN' => 'MDN',
            'BLITAR' => 'BLT',
            'MOJOKERTO' => 'MJK',
            'PROBOLINGGO' => 'PBL',
            'PASURUAN' => 'PSR',
            'BATU' => 'BT',
            'BANYUWANGI' => 'BWI',
            'JEMBER' => 'JMR',
        ];

        if (isset($kotaMap[$kotaRaw])) {
            $kota = $kotaMap[$kotaRaw];
        } else {
            // Fallback: Ambil konsonan saja, max 3 huruf. Jika kosong, ambil 3 huruf awal.
            $consonants = str_replace(['A', 'E', 'I', 'O', 'U', ' '], '', $kotaRaw);
            $kota = substr($consonants, 0, 3) ?: substr($kotaRaw, 0, 3);
        }

        // Format: JENIS-KODE-THN-ANGKATAN-KOTA
        // Contoh: AKS-VDGR-25-2-SBY
        $this->kode_kompetensi_pelatihan = sprintf(
            '%s-%s-%s-%s-%s',
            $jenis,
            $kodeKomp,
            $tahun,
            $angkatan,
            $kota
        );
    }

    public function instrukturs()
    {
        return $this->belongsToMany(Instruktur::class, 'kompetensi_pelatihan_instruktur');
    }
}
