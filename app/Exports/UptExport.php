<?php

namespace App\Exports;

use App\Models\Registration;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

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
        )->get();
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
