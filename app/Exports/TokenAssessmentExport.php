<?php

namespace App\Exports;

use App\Models\PendaftaranPelatihan;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class TokenAssessmentExport implements FromQuery, WithHeadings, WithMapping, WithStyles
{
    protected $status;
    protected $all;

    public function __construct($status = 'diterima', $all = false)
    {
        $this->status = $status;
        $this->all = $all;
    }

    public function query()
    {
        $query = PendaftaranPelatihan::query()
            ->with(['peserta', 'pelatihan'])
            ->whereNotNull('assessment_token')
            ->where('assessment_token', '<>', '');

        if (! $this->all) {
            $query->where('status_pendaftaran', $this->status);
        }

        return $query->orderBy('id', 'asc');
    }

    public function headings(): array
    {
        return [
            'ID Pendaftaran',
            'Nama Peserta',
            'Sesi Pelatihan',
            'Nomor Registrasi',
            'Token Assessment',
            'Tanggal Pendaftaran',
        ];
    }

    public function map($pendaftaran): array
    {
        return [
            $pendaftaran->id,
            $pendaftaran->peserta->nama ?? '-',
            $pendaftaran->pelatihan->nama_pelatihan ?? '-',
            $pendaftaran->nomor_registrasi ?? '-',
            $pendaftaran->assessment_token ?? '-',
            $pendaftaran->tanggal_pendaftaran ? date('d-m-Y H:i', strtotime($pendaftaran->tanggal_pendaftaran)) : '-',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text.
            1    => ['font' => ['bold' => true]],
        ];
    }
}
