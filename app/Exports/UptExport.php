<?php

namespace App\Exports;

use App\Models\Registration;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Carbon\Carbon;

class UptExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Registration::select(
            'email',
            'name',
            'address',
            'birth_date',
            'nik',
            'phone',
            'gender',
            'competence',
            'school_name',
            'dinas_branch',
            'pas_foto_path',
            'ktp_path',
            'ijazah_path',
            'surat_tugas_path',
            'surat_tugas_nomor',
            'surat_sehat_path'
        )->get()->map(function ($row) {
            return [
                'Email' => $row->email,
                'Nama Lengkap' => $row->name,
                'Alamat' => $row->address,
                'Tanggal Lahir' => $row->birth_date 
                    ? Carbon::parse($row->birth_date)->format('Y-m-d')
                    : '',
                'NIK' => $row->nik,
                'No HP' => $row->phone,
                'Jenis Kelamin' => $row->gender,
                'Kompetensi / Bidang Keahlian' => $row->competence,
                'Asal Lembaga / Sekolah' => $row->school_name,
                'Cabang Dinas Wilayah' => $row->dinas_branch,
                'Foto Formal BG Merah' => $row->pas_foto_path,
                'Copy KTP' => $row->ktp_path,
                'Copy Ijazah Terakhir' => $row->ijazah_path,
                'Copy Surat Tugas' => $row->surat_tugas_path,
                'Nomor Surat Tugas' => $row->surat_tugas_nomor,
                'Surat Keterangan Sehat' => $row->surat_sehat_path,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Email',
            'Nama Lengkap',
            'Alamat',
            'Tanggal Lahir',
            'NIK',
            'No HP',
            'Jenis Kelamin',
            'Kompetensi / Bidang Keahlian',
            'Asal Lembaga / Sekolah',
            'Cabang Dinas Wilayah',
            'Foto Formal BG Merah',
            'Copy KTP',
            'Copy Ijazah Terakhir',
            'Copy Surat Tugas',
            'Nomor Surat Tugas',
            'Surat Keterangan Sehat',
        ];
    }
}
