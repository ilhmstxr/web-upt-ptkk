<?php

namespace App\Http\Controllers;

use App\Models\Pelatihan;
use App\Models\Peserta;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller; 
use Illuminate\Support\Facades\Storage;

class PendaftaranController extends Controller
{
    public function create()
    {
        $pelatihans = Pelatihan::all();
        return view('pendaftaran', compact('pelatihans'));
    }

    public function store(Request $request)
    {
        // 1. Validasi super ketat karena semua wajib diisi
        $validated = $request->validate([
            'pelatihan_id' => 'required|exists:pelatihans,id',
            'nama' => 'required|string|max:255',
            'nik' => 'required|string|max:20|unique:pesertas,nik',
            'tempat_lahir' => 'required|string|max:100',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'agama' => 'required|string|max:50',
            'alamat' => 'required|string',
            'no_hp' => 'required|string|max:20',
            'email' => 'required|email|max:255|unique:pesertas,email',
            'asal_instansi' => 'required|string|max:255',
            'alamat_instansi' => 'required|string',
            'bidang_keahlian' => 'required|string|max:255',
            'kelas' => 'required|string|max:100',
            'cabang_dinas_wilayah' => 'required|string|max:255',
            'no_surat_tugas' => 'required|string|max:255',
            'fc_ktp' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'fc_ijazah' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'fc_surat_tugas' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'fc_surat_sehat' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'pas_foto' => 'required|file|mimes:jpg,jpeg,png|max:2048',
        ]);

        // 2. Fungsi untuk menyimpan file dan mendapatkan path
        $saveFile = function($file) {
            return $file->store('public/berkas-peserta');
        };

        // 3. Simpan semua file
        $validated['fc_ktp'] = $saveFile($request->file('fc_ktp'));
        $validated['fc_ijazah'] = $saveFile($request->file('fc_ijazah'));
        $validated['fc_surat_tugas'] = $saveFile($request->file('fc_surat_tugas'));
        $validated['fc_surat_sehat'] = $saveFile($request->file('fc_surat_sehat'));
        $validated['pas_foto'] = $saveFile($request->file('pas_foto'));

        // 4. Buat data peserta di database
        Peserta::create($validated);

        return redirect()->back()->with('success', 'Pendaftaran Anda berhasil dikirim! Terima kasih.');
    }
}
