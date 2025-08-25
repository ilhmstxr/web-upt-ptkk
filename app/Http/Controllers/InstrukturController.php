<?php

namespace App\Http\Controllers;

use App\Models\instruktur;
use Carbon\Carbon;
use Illuminate\Http\Request;
use PhpOffice\PhpWord\TemplateProcessor;

class InstrukturController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function generateBiodata($id)
    {
        // 1. Cari data instruktur berdasarkan ID
        $instruktur = Instruktur::findOrFail($id);

        // 2. Path ke file template
        $templatePath = storage_path('app/templates/template_instruktur.docx');

        // 3. Buat instance TemplateProcessor
        $templateProcessor = new TemplateProcessor($templatePath);

        // 4. Ganti variabel di template dengan data dari database
        $templateProcessor->setValue('kompetensi', $instruktur->kompetensi);
        $templateProcessor->setValue('tanggal_mulai', Carbon::parse($instruktur->tanggal_mulai)->isoFormat('D MMMM YYYY'));
        $templateProcessor->setValue('tanggal_akhir', Carbon::parse($instruktur->tanggal_akhir)->isoFormat('D MMMM YYYY'));
        $templateProcessor->setValue('nama_gelar', $instruktur->nama_gelar);
        $templateProcessor->setValue('tempat_lahir', $instruktur->tempat_lahir);
        $templateProcessor->setValue('tgl_lahir', Carbon::parse($instruktur->tgl_lahir)->isoFormat('D MMMM YYYY'));
        $templateProcessor->setValue('jenis_kelamin', $instruktur->jenis_kelamin);
        $templateProcessor->setValue('agama', $instruktur->agama);
        $templateProcessor->setValue('alamat_rumah', $instruktur->alamat_rumah);
        $templateProcessor->setValue('no_hp', $instruktur->no_hp);
        $templateProcessor->setValue('instansi', $instruktur->instansi);
        $templateProcessor->setValue('npwp', $instruktur->npwp);
        $templateProcessor->setValue('nik', $instruktur->nik);
        $templateProcessor->setValue('nama_bank', $instruktur->nama_bank);
        $templateProcessor->setValue('no_rekening', $instruktur->no_rekening);
        $templateProcessor->setValue('pendidikan_terakhir', $instruktur->pendidikan_terakhir);
        $templateProcessor->setValue('pengalaman_kerja', $instruktur->pengalaman_kerja);

        // 5. Siapkan nama file untuk di-download
        $fileName = 'Biodata_Instruktur_' . str_replace(' ', '_', $instruktur->nama_gelar) . '.docx';
        $pathToSave = storage_path('app/public/' . $fileName);

        // 6. Simpan dokumen yang sudah diisi
        $templateProcessor->saveAs($pathToSave);

        // 7. Kembalikan response untuk men-download file
        return response()->download($pathToSave)->deleteFileAfterSend(true);
    }
    public function index() {}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(instruktur $instruktur)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(instruktur $instruktur)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, instruktur $instruktur)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(instruktur $instruktur)
    {
        //
    }
}
