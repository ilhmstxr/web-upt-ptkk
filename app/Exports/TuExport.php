<?php

namespace App\Exports;

use App\Models\Registration;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TuExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Registration::select(
            'name',
            'birth_place',
            'birth_date',
            'gender',
            'religion',
            'school_name',
            'school_address',
            'class',
            'address',
            'phone',
            'email',
            'competence',
            'pas_foto_path'
        )->get();
    }

    public function headings(): array
    {
        return [
            'Nama',
            'Tempat Lahir',
            'Tanggal Lahir',
            'Jenis Kelamin',
            'Agama',
            'Nama Sekolah',
            'Alamat Sekolah',
            'Kelas',
            'Alamat Rumah',
            'No Telepon Rumah',
            'Email',
            'Pelatihan yang Diikuti',
            'Foto Formal BG Merah',
        ];
    }
}
