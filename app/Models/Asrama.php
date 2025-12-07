<?php

// App\Models\Asrama.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asrama extends Model
{
    use HasFactory;

    protected $table = 'asrama';

    protected $fillable = [
        'nama',
        'gender',
        'alamat',
    ];

    protected $appends = [
        'deskripsi_fasilitas',
    ];

    public function kamars()
    {
        return $this->hasMany(Kamar::class, 'asrama_id');
    }

    public function getDeskripsiFasilitasAttribute(): string
    {
        $jumlahKamar = $this->kamars->count();
        $totalBed    = (int) $this->kamars->sum('total_beds');
        $rusak       = $this->kamars->where('status', 'Rusak')->count();

        $bagian = [];

        $bagian[] = "{$jumlahKamar} kamar, kapasitas ± {$totalBed} bed";

        if ($rusak > 0) {
            $bagian[] = "{$rusak} kamar bermasalah/rusak";
        }

        $bagian[] = "Khusus {$this->gender}";

        return implode(' • ', $bagian);
    }
}
