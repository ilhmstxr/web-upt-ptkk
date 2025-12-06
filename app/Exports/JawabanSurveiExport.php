<?php

namespace App\Exports;

use App\Models\JawabanSurvei;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class JawabanSurveiExport implements FromCollection, WithHeadings
{
    /**
     * Kembalikan koleksi data yang akan diexport.
     * Ubah relasi/kolom sesuai struktur modelmu jika perlu.
     */
    public function collection()
    {
        return JawabanSurvei::with([
                'peserta:id,nama,kompetensi_id,instansi_id',
                'peserta.kompetensi:id,nama_kompetensi',
                'peserta.instansi:id,asal_instansi',
                'pertanyaan:id,judul',
                'opsiJawaban:id,judul,apakah_benar',
            ])
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'peserta_nama' => $item->peserta->nama ?? '-',
                    'kompetensi' => $item->peserta->kompetensi->nama_kompetensi ?? '-',
                    'instansi' => $item->peserta->instansi->asal_instansi ?? '-',
                    'pertanyaan' => $item->pertanyaan->judul ?? '-',
                    'jawaban_id' => $item->opsi_jawaban_id ?? null,
                    'jawaban_text' => $item->opsiJawaban->judul ?? ($item->jawaban_teks ?? null),
                    'jawaban_benar' => $item->opsiJawaban->apakah_benar ?? null,
                    'tes_id' => $item->tes_id ?? null,
                    'tipe' => $item->tes?->tipe ?? $item->tes?->jenis ?? null,
                    'waktu_mulai' => optional($item->created_at)->toDateTimeString(),
                ];
            });
    }

    /**
     * Headings (kolom Excel)
     */
    public function headings(): array
    {
        return [
            'ID',
            'Nama Peserta',
            'Kompetensi',
            'Instansi',
            'Pertanyaan',
            'Jawaban ID',
            'Jawaban (text)',
            'Jawaban Benar?',
            'Tes ID',
            'Tipe Tes',
            'Waktu Dibuat',
        ];
    }
}
