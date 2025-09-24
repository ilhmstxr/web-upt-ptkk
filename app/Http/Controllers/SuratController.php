<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PhpOffice\PhpWord\TemplateProcessor;
use PDF; // dari barryvdh/laravel-dompdf

class SuratController extends Controller
{
    public function generate($id)
    {
        // ambil data dari database, misalnya model Peserta
        $peserta = \App\Models\Peserta::findOrFail($id);

        // load template word
        $templatePath = storage_path('app/templates/template_surat.docx');
        $template = new TemplateProcessor($templatePath);

        // replace placeholder dengan data
        $template->setValue('nama', $peserta->nama);
        $template->setValue('alamat', $peserta->alamat);
        $template->setValue('keperluan', $peserta->keperluan);

        // simpan word sementara
        $tempWord = storage_path("app/public/surat_{$peserta->id}.docx");
        $template->saveAs($tempWord);

        // Konversi ke PDF (opsi 1: langsung pakai dompdf dengan html)
        $html = "
            <h3>Surat Untuk {$peserta->nama}</h3>
            <p>Alamat: {$peserta->alamat}</p>
            <p>Keperluan: {$peserta->keperluan}</p>
        ";

        $pdf = PDF::loadHTML($html);
        return $pdf->download("surat_{$peserta->id}.pdf");

        // --- Opsi 2: kalau butuh hasil persis Word, perlu convert .docx → PDF
        // pakai libreoffice/unoconv di server (lebih berat).
    }
}
