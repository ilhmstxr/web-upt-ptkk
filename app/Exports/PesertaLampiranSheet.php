<?php

namespace App\Exports;

use App\Models\Peserta;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class PesertaLampiranSheet implements FromCollection, WithHeadings, WithDrawings
{
    protected $ids;
    protected $pesertas;

    public function __construct(array $ids)
    {
        $this->ids = $ids;
        $this->pesertas = Peserta::with('lampiran')->whereIn('id', $ids)->get();
    }

    public function collection()
    {
        return $this->pesertas->map(function ($p) {
            return [
                'ID'       => $p->id,
                'Nama'     => $p->nama,
                'KTP'      => $p->lampiran?->fc_ktp,
                'Ijazah'   => $p->lampiran?->fc_ijazah,
                'Surat Tugas' => $p->lampiran?->fc_surat_tugas,
                'Surat Sehat' => $p->lampiran?->fc_surat_sehat,
                'Pas Foto' => $p->lampiran?->pas_foto,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nama',
            'KTP',
            'Ijazah',
            'Surat Tugas',
            'Surat Sehat',
            'Pas Foto',
        ];
    }

    public function drawings()
    {
        $drawings = [];
        foreach ($this->pesertas as $index => $p) {
            if ($p->lampiran && $p->lampiran->pas_foto && Storage::disk('public')->exists($p->lampiran->pas_foto)) {
                $drawing = new Drawing();
                $drawing->setName('Pas Foto');
                $drawing->setDescription('Pas Foto ' . $p->nama);
                $drawing->setPath(storage_path('app/public/' . $p->lampiran->pas_foto));
                $drawing->setHeight(80);

                // gambar akan ditempatkan di kolom G (Pas Foto), baris sesuai peserta
                $drawing->setCoordinates('G' . ($index + 2));
                $drawings[] = $drawing;
            }

            if ($p->lampiran && $p->lampiran->fc_ktp && Storage::disk('public')->exists($p->lampiran->fc_ktp)) {
                $drawing = new Drawing();
                $drawing->setName('KTP');
                $drawing->setDescription('KTP ' . $p->nama);
                $drawing->setPath(storage_path('app/public/' . $p->lampiran->fc_ktp));
                $drawing->setHeight(80);

                // gambar akan ditempatkan di kolom H (KTP), baris sesuai peserta
                $drawing->setCoordinates('H' . ($index + 2));
                $drawings[] = $drawing;
            }

            if ($p->lampiran && $p->lampiran->fc_ijazah && Storage::disk('public')->exists($p->lampiran->fc_ijazah)) {
                $drawing = new Drawing();
                $drawing->setName('Ijazah');
                $drawing->setDescription('Ijazah ' . $p->nama);
                $drawing->setPath(storage_path('app/public'.$p->lampiran->fc_ijazah));
                $drawing->setHeight(80);

                // gambar akan ditempatkan di kolom I (Ijazah), baris sesuai peserta
                $drawing->setCoordinates('I' . ($index + 2));
                $drawings[] = $drawing;
            }

            if ($p->lampiran && $p->lampiran->fc_surat_sehat && Storage::disk('public')->exists($p->lampiran->fc_surat_sehat)) {
                $drawing = new Drawing();
                $drawing->setName('Surat Sehat');
                $drawing->setDescription('Surat Sehat ' . $p->nama);
                $drawing->setPath(storage_path('app/public/' . $p->lampiran->fc_surat_sehat));
                $drawing->setHeight(80);

                // gambar akan ditempatkan di kolom J (Surat Sehat), baris sesuai peserta
                $drawing->setCoordinates('J' . ($index + 2));
                $drawings[] = $drawing;
            }

            if ($p->lampiran && $p->lampiran->fc_surat_tugas && Storage::disk('public')->exists($p->lampiran->fc_surat_tugas)) {
                $drawing = new Drawing();
                $drawing->setName('Surat Tugas');
                $drawing->setDescription('Surat Tugas ' . $p->nama);
                $drawing->setPath(storage_path('app/public/' . $p->lampiran->fc_surat_tugas));
                $drawing->setHeight(80);

                // gambar akan ditempatkan di kolom K (Surat Tugas), baris sesuai peserta
                $drawing->setCoordinates('K' . ($index + 2));
                $drawings[] = $drawing;
            }

        }

        return $drawings;
    }
}
