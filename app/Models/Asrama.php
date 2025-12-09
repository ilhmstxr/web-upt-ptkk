<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asrama extends Model
{
    use HasFactory;

     //protected $connection = 'fasilitas';

    protected $table = 'asrama';

    protected $fillable = [
        'nama',
        'jenis_kelamin',
        'alamat',
    ];

    public function kamars()
    {
        return $this->hasMany(Kamar::class, 'asrama_id');
    }

    /**
     * Ambil denah kamar dari config/kamar.php berdasar nama asrama.
     */
    public function getDenahConfig(): array
    {
        $denah = config('kamar', []);
        return $denah[$this->nama] ?? [];
    }

    /**
     * ✅ AUTO SYNC: bikin record Asrama dari config kamar.php kalau belum ada di DB.
     * Dipanggil dari Resource saat list dibuka.
     */
    public static function syncFromConfig(): void
    {
        $denah = config('kamar', []);

        if (empty($denah) || !is_array($denah)) {
            return;
        }

        foreach (array_keys($denah) as $namaAsrama) {
            static::firstOrCreate(
                ['nama' => $namaAsrama],
                [
                    'jenis_kelamin' => 'Campur', // default kalau belum di-set
                    'alamat' => null,
                ]
            );
        }
    }

    /**
     * Virtual column: total_bed_config
     */
    public function getTotalBedConfigAttribute(): int
    {
        $rooms = $this->getDenahConfig();
        $total = 0;

        foreach ($rooms as $r) {
            if (is_numeric($r['bed'] ?? null)) {
                $total += (int) $r['bed'];
            }
        }

        return $total;
    }

    /**
     * Virtual column: kamar_rusak_config
     */
    public function getKamarRusakConfigAttribute(): int
    {
        $rooms = $this->getDenahConfig();
        return collect($rooms)->where('bed', 'rusak')->count();
    }

    /**
     * Virtual column: deskripsi_fasilitas
     */
    public function getDeskripsiFasilitasAttribute(): string
    {
        $rooms = $this->getDenahConfig();

        if (empty($rooms)) {
            $kamarCount = $this->kamars()->count();
            $bedsTotal  = $this->kamars()->sum('total_beds');
            return "Terdaftar {$kamarCount} kamar, total kapasitas {$bedsTotal} bed (berdasarkan database).";
        }

        $totalKamar = count($rooms);
        $bedCount   = 0;
        $rusak      = 0;
        $unknown    = 0;

        foreach ($rooms as $r) {
            $bed = $r['bed'] ?? null;

            if ($bed === 'rusak') { $rusak++; continue; }
            if (is_numeric($bed)) { $bedCount += (int) $bed; continue; }

            $unknown++;
        }

        $parts = [
            "Total {$totalKamar} kamar",
            "kapasitas bed terdata {$bedCount}",
        ];

        if ($rusak > 0)   $parts[] = "{$rusak} kamar rusak";
        if ($unknown > 0) $parts[] = "{$unknown} kamar belum terdata bed-nya";

        return implode(', ', $parts) . '.';
    }
}
