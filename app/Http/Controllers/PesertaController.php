<?php 

namespace App\Http\Controllers;

use App\Models\Peserta;
use PhpOffice\PhpWord\TemplateProcessor;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class PesertaController extends Controller
{
    /**
     * Download biodata peserta dalam format PDF atau DOCX
     */
    public function download(Peserta $peserta, Request $request)
    {
        $format = $request->get('format', 'pdf'); // default PDF

        if ($format === 'docx') {
            // === DOCX ===
            $templatePath = storage_path('app/templates/template_surat.docx');
            $template = new TemplateProcessor($templatePath);

            // isi placeholder
            $template->setValue('nama', $peserta->nama);
            $template->setValue('tempat_lahir', $peserta->tempat_lahir);
            $template->setValue('tanggal_lahir', $peserta->tanggal_lahir->format('d-m-Y'));
            $template->setValue('nik', $peserta->nik);
            $template->setValue('jenis_kelamin', $peserta->jenis_kelamin);
            $template->setValue('agama', $peserta->agama);
            $template->setValue('alamat', $peserta->alamat);
            $template->setValue('no_hp', $peserta->no_hp);
            $template->setValue('email', $peserta->email);
            $template->setValue('asal_instansi', $peserta->instansi->asal_instansi ?? '-');
            $template->setValue('alamat_instansi', $peserta->instansi->alamat_instansi ?? '-');
            $template->setValue('bidang_keahlian', $peserta->instansi->bidang_keahlian ?? '-');
            $template->setValue('no_surat_tugas', $peserta->lampiran->no_surat_tugas ?? '-');

            // simpan file sementara
            $tempFile = storage_path("app/Biodata-{$peserta->nama}.docx");
            $template->saveAs($tempFile);

            return response()->download($tempFile)->deleteFileAfterSend(true);

        } else {
            // === PDF === (langsung render view blade atau inline HTML)
            $pdf = Pdf::loadView('exports.biodata', compact('peserta'))
                      ->setPaper('A4', 'portrait');

            return $pdf->download("Biodata-{$peserta->nama}.pdf");
        }
    }
}
