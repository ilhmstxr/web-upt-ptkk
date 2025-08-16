<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pelatihan;
use App\Models\Instansi;
use App\Models\Peserta;
use App\Models\Lampiran;
use App\Models\Bidang;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PendaftaranController extends Controller
{
    /**
     * Menampilkan halaman formulir pendaftaran berdasarkan langkah saat ini.
     */
    public function create(Request $request)
    {
        // Ambil semua data dari session
        $formData = $request->session()->get('pendaftaran_data', []);

        // Tentukan langkah saat ini, defaultnya adalah 1
        $currentStep = $request->query('step', 1);

        // Ambil data pelatihan untuk dropdown
        $bidangs = Bidang::with('pelatihans')->get();


        //         return view('peserta.pendaftaran.bio-peserta', compact('bidangs', 'currentStep', 'formData'));

        if ($currentStep == 1 ) {
            return view('peserta.pendaftaran.bio-peserta', compact('bidangs', 'currentStep', 'formData'));
        }elseif ($currentStep == 2) {
            return view('peserta.pendaftaran.bio-sekolah', compact('bidangs', 'currentStep', 'formData'));
        }elseif($currentStep == 3) {
            return view('peserta.pendaftaran.lampiran', compact('bidangs', 'currentStep', 'formData'));
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function index() {

    }


    /**
     * Store a newly created resource in storage.
     */
    // public function store(Request $request)


    public function store(Request $request)
    {

        $currentStep = $request->input('current_step');
        $formData = $request->session()->get('pendaftaran_data', []);

        // --- VALIDASI DAN SIMPAN DATA LANGKAH 1 ---
        if ($currentStep == 1) {
            $validatedData = $request->validate([
                'pelatihan_id' => 'required|exists:pelatihans,id',
                'nama' => 'required|string|max:255',
                'nik' => 'required|string|digits:16|unique:pesertas,nik',
                'tempat_lahir' => 'required|string|max:100',
                'tanggal_lahir' => 'required|date',
                'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
                'agama' => 'required|string|max:50',
                'alamat' => 'required|string',
                'no_hp' => 'required|string|max:15',
                'email' => 'required|email|max:255|unique:pesertas,email',
            ]);

            // Gabungkan data baru dengan data yang sudah ada di session
            $request->session()->put('pendaftaran_data', array_merge($formData, $validatedData));
            return redirect()->route('pendaftaran.create', ['step' => 2]);
        }

        // --- VALIDASI DAN SIMPAN DATA LANGKAH 2 ---
        else if ($currentStep == 2) {
            $validatedData = $request->validate([
                'asal_instansi' => 'required|string|max:255',
                'alamat_instansi' => 'required|string',
                'bidang_keahlian' => 'required|string|max:255',
                'kelas' => 'required|string|max:100',
                'cabang_dinas_wilayah' => 'required|string|max:255',
            ]);

            $request->session()->put('pendaftaran_data', array_merge($formData, $validatedData));
            return redirect()->route('pendaftaran.create', ['step' => 3]);
        }

        // --- VALIDASI DAN SIMPAN DATA LANGKAH 3 (FINAL) ---
        else if ($currentStep == 3) {
            $validatedData = $request->validate([
                'no_surat_tugas' => 'required|string|max:255',
                'fc_ktp' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
                'fc_ijazah' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
                'fc_surat_tugas' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
                'fc_surat_sehat' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
                'pas_foto' => 'required|file|mimes:jpg,jpeg,png|max:2048',
            ]);

            $allData = array_merge($formData, $validatedData);

            // Gunakan transaction untuk memastikan semua data berhasil disimpan atau tidak sama sekali
            DB::transaction(function () use ($allData, $request) {
                // 1. Simpan Instansi
                $instansi = Instansi::create([
                    'asal_instansi' => $allData['asal_instansi'],
                    'alamat_instansi' => $allData['alamat_instansi'],
                    'bidang_keahlian' => $allData['bidang_keahlian'],
                    'kelas' => $allData['kelas'],
                    'cabang_dinas_wilayah' => $allData['cabang_dinas_wilayah'],
                ]);

                // 2. Simpan Peserta
                $peserta = Peserta::create([
                    'pelatihan_id' => $allData['pelatihan_id'],
                    'instansi_id' => $instansi->id,
                    'nama' => $allData['nama'],
                    'nik' => $allData['nik'],
                    'tempat_lahir' => $allData['tempat_lahir'],
                    'tanggal_lahir' => $allData['tanggal_lahir'],
                    'jenis_kelamin' => $allData['jenis_kelamin'],
                    'agama' => $allData['agama'],
                    'alamat' => $allData['alamat'],
                    'no_hp' => $allData['no_hp'],
                    'email' => $allData['email'],
                ]);

                // 3. Simpan Lampiran
                $saveFile = fn($file) => $file->store('public/berkas_pendaftaran');
                Lampiran::create([
                    'peserta_id' => $peserta->id,
                    'no_surat_tugas' => $allData['no_surat_tugas'],
                    'fc_ktp' => $saveFile($request->file('fc_ktp')),
                    'fc_ijazah' => $saveFile($request->file('fc_ijazah')),
                    'fc_surat_tugas' => $saveFile($request->file('fc_surat_tugas')),
                    'fc_surat_sehat' => $saveFile($request->file('fc_surat_sehat')),
                    'pas_foto' => $saveFile($request->file('pas_foto')),
                ]);
            });

            // Hapus data dari session dan redirect dengan pesan sukses
            $request->session()->forget('pendaftaran_data');
            return redirect()->route('pendaftaran.create')->with('success', 'Pendaftaran Anda telah berhasil terkirim! Terima kasih.');
        }

        return redirect()->route('pendaftaran.create');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
