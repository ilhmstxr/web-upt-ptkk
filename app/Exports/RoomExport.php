<?php

namespace App\Exports;

use App\Models\Peserta;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class RoomExport implements FromCollection, WithHeadings
{
    protected $namaKamar;
    protected $jumlahBed;

    public function __construct($namaKamar, $jumlahBed)
    {
        $this->namaKamar = $namaKamar;
        $this->jumlahBed = $jumlahBed;
    }

    public function collection()
    {
        $peserta = Peserta::orderBy('id')->get();
        return $peserta->map(function ($p, $i) {
            return [
                'nama' => $p->nama,
                'jenis_kelamin' => $p->jenis_kelamin,
                'kamar' => $this->namaKamar,
                'bed' => ($i % $this->jumlahBed) + 1,
            ];
        });
    }

    public function headings(): array
    {
        return ['Nama', 'Jenis Kelamin', 'Kamar', 'Bed'];
    }
}
