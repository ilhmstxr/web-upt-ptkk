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
            // ================= Sheet 1: Data Peserta =================
            new class implements FromCollection, WithHeadings {
                public function collection()
                {
                    return Peserta::with(['instansi','pelatihan'])->get()->map(function ($p) {
                        $tgl = $this->safeDate($p->tanggal_lahir);

                        return [
                            'Nama'          => (string) ($p->nama ?? ''),
                            'NIK'           => (string) ($p->nik ?? ''),
                            'Tempat Lahir'  => (string) ($p->tempat_lahir ?? ''),
                            'Tanggal Lahir' => $tgl,
                            'Jenis Kelamin' => (string) ($p->jenis_kelamin ?? ''),
                            'Agama'         => (string) ($p->agama ?? ''),
                            'Alamat'        => (string) ($p->alamat ?? ''),
                            'No HP'         => (string) ($p->no_hp ?? ''),
                            'Email'         => (string) ($p->email ?? ''),
                            'Instansi'      => (string) ($p->instansi->asal_instansi ?? ''),
                            'Pelatihan'     => (string) ($p->pelatihan->nama ?? '-'),
                        ];
                    });
                }

                /** Kembalikan string 'YYYY-mm-dd' atau '' tanpa error */
                private function safeDate($value): string
                {
                    if ($value instanceof \DateTimeInterface) {
                        return $value->format('Y-m-d');
                    }

                    if (is_string($value) && trim($value) !== '') {
                        try {
                            return \Carbon\Carbon::parse($value)->format('Y-m-d');
                        } catch (\Throwable $e) {
                            return ''; // jangan balikin string aneh
                        }
                    }

                    return '';
                }

                public function headings(): array
                {
                    return [
                        'Nama',
                        'NIK',
                        'Tempat Lahir',
                        'Tanggal Lahir',
                        'Jenis Kelamin',
                        'Agama',
                        'Alamat',
                        'No HP',
                        'Email',
                        'Instansi',
                        'Pelatihan',
                    ];
                }
            },

            // ================= Sheet 2: Lampiran Peserta =================
            new class implements FromCollection, WithHeadings {
                public function collection()
                {
                    return Peserta::with('lampiran')->get()->map(function ($p) {
                        $l = $p->lampiran;

                        $url = function ($path) {
                            return $path ? (string) asset('storage/' . ltrim($path, '/')) : '';
                        };

                        return [
                            'Nama'           => (string) ($p->nama ?? ''),
                            'fc_ktp'         => $url($l->fc_ktp ?? null),
                            'fc_ijazah'      => $url($l->fc_ijazah ?? null),
                            'fc_surat_tugas' => $url($l->fc_surat_tugas ?? null),
                            'fc_surat_sehat' => $url($l->fc_surat_sehat ?? null),
                            'pas_foto'       => $url($l->pas_foto ?? null),
                        ];
                    });
                }

                public function headings(): array
                {
                    return [
                        'Nama',
                        'fc_ktp',
                        'fc_ijazah',
                        'fc_surat_tugas',
                        'fc_surat_sehat',
                        'pas_foto',
                    ];
                }
            }
        ];
    }
}
