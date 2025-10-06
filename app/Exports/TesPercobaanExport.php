<?php

namespace App\Exports;

use App\Models\Percobaan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TesPercobaanExport implements FromCollection, WithHeadings
{
    protected $query;

    public function __construct($query = null)
    {
        $this->query = $query ?? Percobaan::query();
    }

    public function collection()
    {
        return $this->query->with(['peserta','tes'])->get()->map(function($row){
            return [
                'peserta' => $row->peserta->nama ?? '-',
                'bidang' => $row->peserta->bidang->nama_bidang ?? '-',
                'instansi' => $row->peserta->instansi->asal_instansi ?? '-',
                'tes' => $row->tes->judul ?? $row->tes->jenis,
                'jenis' => $row->tes->jenis ?? '-',
                'skor' => $row->skor,
                'waktu_mulai' => $row->waktu_mulai,
                'waktu_selesai' => $row->waktu_selesai,
            ];
        });
    }

    public function headings(): array
    {
        return ['Peserta','Bidang','Instansi','Tes','Jenis','Skor','Waktu Mulai','Waktu Selesai'];
    }
}
