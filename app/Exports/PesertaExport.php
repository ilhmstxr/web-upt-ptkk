<?php

namespace App\Exports;

use App\Models\Peserta;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PesertaExport implements WithMultipleSheets
{
    public function sheets(): array
    {
        return [
            new class implements FromCollection, WithHeadings {
                public function collection()
                {
                    return Peserta::with('instansi','pelatihan')->get()->map(fn ($p) => [
                        'Nama' => $p->nama,
                        'NIK' => $p->nik,
                        'Tempat Lahir' => $p->tempat_lahir,
                        'Tanggal Lahir' => $p->tanggal_lahir->format('Y-m-d'),
                        'Jenis Kelamin' => $p->jenis_kelamin,
                        'Agama' => $p->agama,
                        'Alamat' => $p->alamat,
                        'No HP' => $p->no_hp,
                        'Email' => $p->email,
                        'Instansi' => $p->instansi?->asal_instansi,
                        'Pelatihan' => $p->pelatihan?->id,
                    ]);
                }

                public function headings(): array
                {
                    return ['Nama','NIK','Tempat Lahir','Tanggal Lahir','Jenis Kelamin','Agama','Alamat','No HP','Email','Instansi','Pelatihan'];
                }
            },
            new class implements FromCollection, WithHeadings {
                public function collection()
                {
                    return Peserta::with('lampiran')->get()->map(fn ($p) => array_merge(
                        ['Nama' => $p->nama],
                        $p->lampirans()
                    ));
                }

                public function headings(): array
                {
                    return ['Nama','fc_ktp','fc_ijazah','fc_surat_tugas','fc_surat_sehat','pas_foto'];
                }
            }
        ];
    }
}
