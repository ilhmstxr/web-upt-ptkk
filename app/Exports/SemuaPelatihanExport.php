<?php

namespace App\Exports;

use App\Models\Pelatihan;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class SemuaPelatihanExport implements WithMultipleSheets
{
    use Exportable;

    public function sheets(): array
    {
        $sheets = [];
        // Ambil semua pelatihan yang memiliki pendaftaran (optional: atau filter status 'aktif')
        // Disini kita ambil semua pelatihan
        $pelatihans = Pelatihan::all();

        foreach ($pelatihans as $pelatihan) {
            $sheets[] = new PerPelatihanExport($pelatihan->id);
        }

        return $sheets;
    }
}
