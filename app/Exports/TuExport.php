<?php

namespace App\Exports;

use App\Models\Registration;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Carbon\Carbon;

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
        )->get()->map(function ($row) {
            return [
                'Nama' => $row->name,
                'Tempat Lahir' => $row->birth_place,
                'Tanggal Lahir' => $row->birth_date 
                    ? Carbon::parse($row->birth_date)->format('Y-m-d')
                    : '',
                'Jenis Kelamin' => $row->gender,
                'Agama' => $row->religion,
                'Nama Sekolah' => $row->school_name,
                'Alamat Sekolah' => $row->school_address,
                'Kelas' => $row->class,
                'Alamat Rumah' => $row->address,
                'No Telepon Rumah' => $row->phone,
                'Email' => $row->email,
                'Pelatihan yang Diikuti' => $row->competence,
                'Foto Formal BG Merah' => $row->pas_foto_path,
            ];
        });
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
