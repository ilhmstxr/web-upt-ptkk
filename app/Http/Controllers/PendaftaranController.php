<?php

namespace App\Http\Controllers;

use AlperenErsoy\FilamentExport\Actions\FilamentExportBulkAction;
use AlperenErsoy\FilamentExport\FilamentExport;
use App\Exports\PesertaExport;
use Illuminate\Http\Request;
use App\Models\Pelatihan;
use App\Models\Instansi;
use App\Models\Peserta;
use App\Models\Lampiran;
use App\Models\Bidang;
use App\Models\CabangDinas;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Filament\Resources\PesertaResource;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use ZipArchive;

class PendaftaranController extends Controller
{
    private static $currentStep = null;
    /**
     * Menampilkan halaman formulir pendaftaran berdasarkan langkah saat ini.
     */


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return redirect()->route('pendaftaran.create');
    }

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

        // return $currentStep;
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

        }
    }
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
            // return $currentStep;
            // return view('peserta.pendaftaran.selesai')->with('success', 'Pendaftaran Anda telah berhasil terkirim! Terima kasih.');
            return redirect()->route('pendaftaran.selesai')->with('success', 'Pendaftaran Anda telah berhasil terkirim! Terima kasih.');
        }

        // return redirect()->route('pendaftaran.create');
    }


    public function selesai()
    {
        // Pindahkan logika dari rute ke sini
        return view('peserta.pendaftaran.selesai');
    }

    public function downloadPDF(Peserta $peserta)
    {
        // // Load relasi yang mungkin dibutuhkan di dalam view PDF
        // $peserta->load('pelatihan', 'instansi', 'lampiran');

        // // Membuat PDF dari sebuah Blade view
        // $pdf = Pdf::loadView('pdf.biodata-peserta', ['peserta' => $peserta]);

        // // Mengunduh file PDF dengan nama dinamis
        // return $pdf->download('biodata-' . Str::slug($peserta->nama) . '.pdf');
    }

    public function download(Peserta $peserta)
    {
        $lampiran = $peserta->lampiran;
        if (!$lampiran) {
            return back()->with('error', 'Peserta tidak memiliki data lampiran.');
        }

        $filesToZip = [
            $lampiran->pas_foto,
            $lampiran->fc_ktp,
            $lampiran->fc_ijazah,
            $lampiran->fc_surat_sehat,
            $lampiran->fc_surat_tugas,
        ];

        $zipFileName = 'lampiran-' . Str::slug($peserta->nama) . '.zip';
        $zipPath = storage_path('app/temp/' . $zipFileName);

        $zip = new ZipArchive;
        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
            foreach ($filesToZip as $filePath) {
                if ($filePath && Storage::disk('public')->exists($filePath)) {
                    $absolutePath = storage_path('app/public/' . $filePath);
                    $zip->addFile($absolutePath, basename($filePath));
                }
            }
            $zip->close();
        } else {
            return back()->with('error', 'Gagal membuat file ZIP.');
        }

        return response()->download($zipPath)->deleteFileAfterSend(true);
    }

    public function downloadBulk(Request $request)
    {
        // Pastikan request punya ids dan excelFileName
        $request->validate([
            'ids'            => 'required|array',
            'ids.*'          => 'integer|exists:pesertas,id',
            'excelFileName'  => 'required|string'
        ]);

        $ids = $request->input('ids');
        $fileName = $request->input('excelFileName') . '.xlsx';

        return Excel::download(new PesertaExport($ids), $fileName);
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
