<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pelatihan;
use App\Models\Instansi;
use App\Models\Peserta;
use App\Models\Lampiran;
use App\Models\Bidang;
use App\Models\CabangDinas;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Support\Facades\Storage; // <-- TAMBAHKAN BARIS INI


class PendaftaranController extends Controller
{
    private static $currentStep = null;
    /**
     * Menampilkan halaman formulir pendaftaran berdasarkan langkah saat ini.
     */
    public function create(Request $request)
    {
        // 1. Ambil langkah yang ingin diakses dari URL (target), default ke 1.
        $currentStep = (int) $request->query('step', 1);

        // 2. Ambil langkah maksimal yang diizinkan dari session, default ke 1.
        // Session ini HANYA diubah di method store() setelah validasi berhasil.
        $allowedStep = (int) $request->session()->get('pendaftaran_step', 1);

        // 3. VALIDASI KEAMANAN: Cek apakah pengguna mencoba melompat.
        // Jika target lebih besar dari yang diizinkan, paksa kembali ke langkah terakhir yang valid.
        if ($currentStep > $allowedStep) {
            return redirect()->route('pendaftaran.create', ['step' => $allowedStep])
                ->with('error', 'Harap lengkapi formulir secara berurutan.');
        }

        // Ambil data yang sudah tersimpan di session untuk mengisi kembali input form.
        $formData = $request->session()->get('pendaftaran_data', []);

        // Gunakan switch untuk menampilkan view dan memuat data yang relevan saja.
        switch ($currentStep) {
            case 1:
                $cabangDinas = CabangDinas::all();
                return view('peserta.pendaftaran.bio-peserta', compact('currentStep', 'allowedStep', 'formData', 'cabangDinas'));

            case 2:
                $pelatihan = Pelatihan::all();
                $bidang = Bidang::all();
                $cabangDinas = CabangDinas::all(); // Jika masih dibutuhkan di step 2
                return view('peserta.pendaftaran.bio-sekolah', compact('currentStep', 'allowedStep', 'formData', 'pelatihan', 'bidang', 'cabangDinas'));

            case 3:
                return view('peserta.pendaftaran.lampiran', compact('currentStep', 'allowedStep', 'formData'));

                // Ini adalah halaman "Selesai" setelah form disubmit
            case 4:
                // Halaman ini hanya bisa diakses jika session 'success' ada,
                // yang diatur di method store() setelah berhasil menyimpan.
                if (!$request->session()->has('success')) {
                    return redirect()->route('pendaftaran.create', ['step' => 1]);
                }
                return view('peserta.pendaftaran.selesai', compact('currentStep', 'allowedStep'));

            default:
                // Jika pengguna memasukkan step yang aneh (misal: 0 atau 5),
                // kembalikan ke langkah terakhir yang mereka boleh akses.
                return redirect()->route('pendaftaran.create', ['step' => $allowedStep]);
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function index() {}


    /**
     * Store a newly created resource in storage.
     */
    // public function store(Request $request)


    public function store(Request $request)
    {
        $currentStep = $request->input('current_step');
        $formData = $request->session()->get('pendaftaran_data', []);

        // return $request;
        // return $currentStep;
        // return $formData;
        // --- VALIDASI DAN SIMPAN DATA LANGKAH 1 ---
        if ($currentStep == 1) {
            $validatedData = $request->validate([
                'nama' => 'required|string|max:255',
                'nik' => 'required|string|digits:16|unique:pesertas,nik',
                'no_hp' => 'required|string|max:15',
                'email' => 'required|email|max:255|unique:pesertas,email',
                'tempat_lahir' => 'required|string|max:100',
                'tanggal_lahir' => 'required|date',
                'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
                'agama' => 'required|string|max:50',
                'alamat' => 'required|string',
            ]);

            // Gabungkan data yang divalidasi dengan data session yang sudah ada.
            $request->session()->put('pendaftaran_data', array_merge($formData, $validatedData));
            // Set langkah berikutnya adalah 2.
            $request->session()->put('pendaftaran_step', 2);

            // Redirect ke method create dengan parameter step=2
            return redirect()->route('pendaftaran.create', ['step' => 2]);
        }

        // --- VALIDASI DAN SIMPAN DATA LANGKAH 2 ---
        else if ($currentStep == 2) {
            // return $request;
            // PERHATIKAN: Nama field di sini HARUS SAMA dengan atribut 'name' pada input di form bio-sekolah.blade.php
            $validatedData = $request->validate([
                'asal_instansi' => 'required|string|max:255',
                'alamat_instansi' => 'required|string',
                'bidang_keahlian' => 'required|string|max:255',
                'kelas' => 'required|string|max:100',
                'cabang_dinas_wilayah' => 'required|string|max:255',
                'pelatihan_id' => 'required|string',
            ]);


            // return "asjil";

            $request->session()->put('pendaftaran_data', array_merge($formData, $validatedData));
            $request->session()->put('pendaftaran_step', 3);

            // Redirect ke method create dengan parameter step=3
            return redirect()->route('pendaftaran.create', ['step' => 3]);
        }

        // --- VALIDASI DAN SIMPAN DATA LANGKAH 3 (FINAL) ---
        else if ($currentStep == 3) {;
            $validatedData = $request->validate([
                // 'no_surat_tugas' => 'string|max:255',
                'fc_ktp' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
                'fc_ijazah' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
                'fc_surat_tugas' => 'file|mimes:pdf,jpg,jpeg,png|max:2048',
                'fc_surat_sehat' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
                'pas_foto' => 'required|file|mimes:jpg,jpeg,png|max:2048',
            ]);


            $allData = array_merge($formData, $validatedData);
            // $request->session()->flush();
            // return $allData;

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
                    'bidang_id' => $allData['bidang_keahlian'], // Asumsi bidang_keahlian adalah ID bidang
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
                // $saveFile = fn($file) => $file->store('public/berkas_pendaftaran');
                // Lampiran::create([
                //     'peserta_id' => $peserta->id,
                //     'no_surat_tugas' => $allData['no_surat_tugas'],
                //     'fc_ktp' => $saveFile($request->file('fc_ktp')),
                //     'fc_ijazah' => $saveFile($request->file('fc_ijazah')),
                //     'fc_surat_tugas' => $saveFile($request->file('fc_surat_tugas')),
                //     'fc_surat_sehat' => $saveFile($request->file('fc_surat_sehat')),
                //     'pas_foto' => $saveFile($request->file('pas_foto')),
                // ]);

                $lampiranData = [
                    'peserta_id' => $peserta->id,
                    'no_surat_tugas' => $allData['no_surat_tugas'] ?? null,
                ];

                // 2. Buat daftar nama input file Anda
                $fileFields = [
                    'fc_ktp',
                    'fc_ijazah',
                    'fc_surat_tugas',
                    'fc_surat_sehat',
                    'pas_foto'
                ];
                foreach ($fileFields as $field) {
                    // Periksa apakah file dengan nama tersebut ada di request
                    if ($request->hasFile($field)) {
                        // Ambil objek filenya
                        $file = $request->file($field);

                        // Kumpulkan semua informasi yang Anda butuhkan
                        $namaAsli = $file->getClientOriginalName();
                        $ukuranFile = $file->getSize(); // dalam bytes
                        $ekstensi = $file->extension();

                        // Simpan file dan dapatkan path-nya
                        // $path = $file->store('/berkas_pendaftaran'); // --> store ke disk private/public/berkas_pendaftaran
                        $path = $file->store('berkas_pendaftaran', 'public'); // --> store ke disk public/berkas_pendaftaran 

                        // Simpan semua informasi ini ke dalam array hasil
                        // $processedFiles[$field] = [
                        //     'path'       => $path,
                        //     'nama_asli'  => $namaAsli,
                        //     'ukuran'     => $ukuranFile,
                        //     'ekstensi'   => $ekstensi,
                        // ];

                        $lampiranData[$field] = $path;
                    }
                }

                Lampiran::create($lampiranData);
            });

            // return "kesave";
            // Hapus data dari session dan redirect dengan pesan sukses
            $request->session()->forget('pendaftaran_data');
            return view('peserta.pendaftaran.selesai', compact('currentStep'))->with('success', 'Pendaftaran Anda telah berhasil terkirim! Terima kasih.');
        }
        else if ($currentStep == 4) {
            // Jika sudah sampai di step 4, tidak perlu melakukan apa-apa
            // karena ini hanya halaman selesai.
            return view('peserta.pendaftaran.selesai', compact('currentStep'));
        }

        // return redirect()->route('pendaftaran.create');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }
public function testing()
    {
        $pesertas = peserta::with('instansi', 'lampiran')->get();
        return view('admin.testing', compact('pesertas'));
    }


    public function download_file(Request $request): StreamedResponse
    {
        // Validasi untuk memastikan path file ada di request
        $request->validate([
            'path' => 'required|string',
        ]);

        $filePath = $request->input('path');

        $disk = Storage::disk('public');

        // Validasi keamanan untuk memastikan file ada di dalam disk 'public'
        abort_if(!$disk->exists($filePath), 404, 'File not found.');

        return $disk->download($filePath);
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
