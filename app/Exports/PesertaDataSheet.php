<?php

namespace App\Exports;

use App\Models\Peserta;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PesertaDataSheet implements FromCollection, WithHeadings
{
    protected $ids;

    public function __construct(array $ids)
    {
        $this->ids = $ids;
    }

    public function collection()
    {
        return Peserta::whereIn('id', $this->ids)->get()->map(function ($p) {
            return [
                'ID'             => $p->id,
                'Nama'           => $p->nama,
                'NIK'            => $p->nik,
                'Tanggal Lahir'  => $p->tanggal_lahir
                    ? Carbon::parse($p->tanggal_lahir)->format('Y-m-d')
                    : null,
                'Alamat'         => $p->alamat,
                'Email'          => $p->email,
                'No HP'          => $p->no_hp,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nama',
            'NIK',
            'Tanggal Lahir',
            'Alamat',
            'Email',
            'No HP',
        ];
    }
}
