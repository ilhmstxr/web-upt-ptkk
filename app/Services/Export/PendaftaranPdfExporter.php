<?php

namespace App\Services\Export;

use PhpOffice\PhpWord\TemplateProcessor;
use PhpOffice\PhpWord\Settings;
use PhpOffice\PhpWord\IOFactory;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Models\PendaftaranPelatihan;
use Carbon\Carbon;

class PendaftaranPdfExporter
{
    public function __construct(
        private string $templatePath = 'templates/BIODATA_PESERTA_template.docx', // simpan file template di storage/app/templates/
    ) {
        // Atur renderer PDF (DomPDF)
        Settings::setPdfRendererName(Settings::PDF_RENDERER_DOMPDF);
        // DomPDF tidak butuh path khusus, tapi jika perlu:
        // Settings::setPdfRendererPath(base_path('vendor/dompdf/dompdf'));
    }

    /**
     * Hasilkan PDF untuk satu pendaftaran, return path file PDF di storage/tmp
     */
    public function generateSingle(PendaftaranPelatihan $pendaftaran): string
    {
        // Pastikan template tersedia
        $absTemplate = Storage::path($this->templatePath);

        $tp = new TemplateProcessor($absTemplate);

        $peserta  = $pendaftaran->peserta;
        $pelatihan = $pendaftaran->pelatihan;
        $bidang   = $pendaftaran->bidang;

        // mapping placeholder sesuai template «...»
        $tp->setValue('nama', $peserta->nama ?? '');
        $tp->setValue('tempat_lahir', $peserta->tempat_lahir ?? '');
        $tp->setValue('tanggal_lahir', optional($peserta->tanggal_lahir)->format('d-m-Y') ?? '');
        $tp->setValue('jenis_kelamin', $peserta->jenis_kelamin ?? '');
        $tp->setValue('agama', $peserta->agama ?? '');
        $tp->setValue('no_hp', $peserta->no_hp ?? '');
        $tp->setValue('nik', $peserta->nik ?? '');
        $tp->setValue('asal_instansi', $peserta->asal_instansi ?? '');
        $tp->setValue('alamat_instansi', $peserta->alamat_instansi ?? '');
        $tp->setValue('kelas', $pendaftaran->kelas ?? '');
        $tp->setValue('nama_bidang', $bidang->nama ?? '');

        // contoh judul/tanggal kegiatan dari template (opsional)
        $tp->setValue('judul', $pelatihan->nama_pelatihan ?? '');
        $tp->setValue('tanggal_kegiatan', $pelatihan->tanggal_mulai
            ? Carbon::parse($pelatihan->tanggal_mulai)->translatedFormat('d F Y')
            : '');

        // Simpan DOCX sementara
        $tmpDir = storage_path('app/tmp/exports');
        if (! is_dir($tmpDir)) {
            @mkdir($tmpDir, 0775, true);
        }

        $baseName = Str::slug(($peserta->nama ?? 'peserta') . '-' . ($pelatihan->nama_pelatihan ?? 'pelatihan'));
        $docxPath = $tmpDir . "/{$baseName}.docx";
        $pdfPath  = $tmpDir . "/{$baseName}.pdf";

        $tp->saveAs($docxPath);

        // Konversi DOCX → PDF
        $phpWord = IOFactory::load($docxPath);
        $pdfWriter = IOFactory::createWriter($phpWord, 'PDF');
        $pdfWriter->save($pdfPath);

        return $pdfPath;
    }

    /**
     * Hasilkan banyak PDF lalu zip, return path ZIP
     */
    public function generateBulkZip(iterable $pendaftarans): string
    {
        $tmpDir = storage_path('app/tmp/exports');
        if (! is_dir($tmpDir)) {
            @mkdir($tmpDir, 0775, true);
        }

        $zipName = 'pendaftaran-' . now()->format('Ymd-His') . '.zip';
        $zipPath = $tmpDir . '/' . $zipName;

        $zip = new \ZipArchive();
        $zip->open($zipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);

        foreach ($pendaftarans as $pendaftaran) {
            $pdfPath = $this->generateSingle($pendaftaran);
            $localName = basename($pdfPath);
            $zip->addFile($pdfPath, $localName);
        }

        $zip->close();

        return $zipPath;
    }
}
