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

        // Definisikan semua lampiran yang ingin diproses dalam sebuah array
        // Format: 'nama_properti' => ['kolom_excel', 'Label untuk Deskripsi']
        $attachmentMap = [
            'pas_foto' => ['C', 'Pas Foto'],
            'fc_ktp' => ['D', 'KTP'],
            'fc_ijazah' => ['E', 'Ijazah'],
            'fc_surat_sehat' => ['F', 'Surat Sehat'],
            'fc_surat_tugas' => ['G', 'Surat Tugas'],
        ];

        // Loop untuk setiap peserta
        foreach ($this->pesertas as $index => $peserta) {
            if (!$peserta->lampiran) {
                continue; // Lanjut ke peserta berikutnya jika tidak ada data lampiran
            }

            // Loop untuk setiap jenis lampiran yang sudah kita definisikan
            foreach ($attachmentMap as $property => $details) {
                $filePath = $peserta->lampiran->$property;

                
                // Cek apakah path file ada dan filenya benar-benar ada di storage
                if ($filePath && Storage::disk('public')->exists($filePath)) {

                    $drawing = new Drawing();
                    $drawing->setName($details[1]); // Menggunakan Label dari map
                    $drawing->setDescription($details[1] . ' ' . $peserta->nama);

                    // --- REVISI: Gunakan metode path() dari Storage ---
                    $drawing->setPath(Storage::disk('public')->path($filePath));

                    $drawing->setHeight(80);

                    // Set koordinat secara dinamis berdasarkan kolom dari map dan index peserta
                    $drawing->setCoordinates($details[0] . ($index + 2));

                    $drawings[] = $drawing;
                }
            }
        }

        // dd($drawings); // Debugging: Tampilkan semua drawing yang akan digunakan

        return $drawings;
    }
}
