<?php

namespace App\Exports;

use App\Models\Peserta;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PesertaExport implements FromCollection, WithHeadings, WithMapping
{
    protected $ids;

    public function __construct($ids = null)
    {
        $this->ids = $ids;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        if ($this->ids) {
            return Peserta::with(['instansi', 'pelatihan', 'kompetensi'])->whereIn('id', $this->ids)->get();
        }
        return Peserta::with(['instansi', 'pelatihan', 'kompetensi'])->get();
    }

    public function headings(): array
    {
        return [
            'Nama',
            'NIK',
            'No HP',
            'Email',
            'Tempat Lahir',
            'Tanggal Lahir',
            'Jenis Kelamin',
            'Agama',
            'Alamat',
            'Asal Instansi',
            'Pelatihan',
            'Kompetensi',
        ];
    }

    public function map($peserta): array
    {
        return [
            $peserta->nama,
            "'" . $peserta->nik, // Force string for NIK
            $peserta->no_hp,
            $peserta->user->email ?? '-',
            $peserta->tempat_lahir,
            $peserta->tanggal_lahir,
            $peserta->jenis_kelamin,
            $peserta->agama,
            $peserta->alamat,
            $peserta->instansi->asal_instansi ?? '-',
            $peserta->pelatihan->nama_pelatihan ?? '-',
            $peserta->kompetensi->nama_kompetensi ?? '-',
        ];
    }
}
