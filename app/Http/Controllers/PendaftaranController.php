<?php

namespace App\Http\Controllers;

use App\Exports\PesertaExport;
use App\Models\Bidang;
use App\Models\CabangDinas;
use App\Models\Instansi;
use App\Models\Instruktur;
use App\Models\Lampiran;
use App\Models\Pelatihan;
use App\Models\Peserta;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use ZipArchive;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class PendaftaranController extends Controller
{
    public function index()
    {
        return redirect()->route('pendaftaran.create');
    }

    public function create(Request $request)
    {
        $currentStep = (int) $request->query('step', 1);
        $allowedStep = (int) $request->session()->get('pendaftaran_step', 1);

        if ($currentStep > $allowedStep) {
            return redirect()->route('pendaftaran.create', ['step' => $allowedStep])
                ->with('error', 'Harap lengkapi formulir secara berurutan.');
        }

        $formData = $request->session()->get('pendaftaran_data', []);

        switch ($currentStep) {
            case 1:
                $cabangDinas = CabangDinas::all();
                $pelatihan = Pelatihan::all();
                return view('peserta.pendaftaran.bio-peserta', compact('currentStep', 'allowedStep', 'formData', 'cabangDinas'));
            case 2:
                $pelatihan = Pelatihan::where('status', 'aktif')->get();
                $bidang = Bidang::all();
                $cabangDinas = CabangDinas::all();
                return view('peserta.pendaftaran.bio-sekolah', compact('currentStep', 'allowedStep', 'formData', 'pelatihan', 'bidang', 'cabangDinas'));
            case 3:
                $pelatihanId = $formData['pelatihan_id'] ?? null;
                // return $pelatihanId;
                // Ambil data pelatihan spesifik beserta lampiran wajibnya
                $pelatihan = Pelatihan::find($pelatihanId);
                // return $pelatihan;
                return view('peserta.pendaftaran.lampiran', compact('currentStep', 'allowedStep', 'formData', 'pelatihan'));
        }
    }

    public function store(Request $request)
    {
        $currentStep = $request->input('current_step');
        $formData = $request->session()->get('pendaftaran_data', []);

        if ($currentStep == 1) {
            $validatedData = $request->validate([
                'nama' => 'required|string|max:150',
                'nik' => 'required|string|digits:16|max:20|unique:peserta,nik',
                'no_hp' => 'required|string|max:20',
                'email' => 'required|email|max:255|unique:users,email',
                'tempat_lahir' => 'required|string|max:100',
                'tanggal_lahir' => 'required|date',
                'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
                'agama' => 'required|string|max:50',
                'alamat' => 'required|string',
            ]);

            $request->session()->put('pendaftaran_data', array_merge($formData, $validatedData));
            $request->session()->put('pendaftaran_step', 2);
            return redirect()->route('pendaftaran.create', ['step' => 2]);
        }

        if ($currentStep == 2) {
            $validatedData = $request->validate([
                'asal_instansi' => 'required|string|max:255',
                'alamat_instansi' => 'required|string',
                'kota' => 'required|string|max:100',
                'kota_id' => 'required|integer',
                'bidang_keahlian' => 'required|string|max:255',
                'kelas' => 'required|string|max:100',
                'cabangDinas_id' => 'required|string|max:255',
                'pelatihan_id' => 'required|string',
            ]);

            $request->session()->put('pendaftaran_data', array_merge($formData, $validatedData));
            $request->session()->put('pendaftaran_step', 3);
            return redirect()->route('pendaftaran.create', ['step' => 3]);
        }

        if ($currentStep == 3) {
            $validatedData = $request->validate([
                'fc_ktp' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
                'fc_ijazah' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
                'fc_surat_tugas' => 'file|mimes:pdf,jpg,jpeg,png|max:2048',
                'fc_surat_sehat' => 'file|mimes:pdf,jpg,jpeg,png|max:2048',
                'pas_foto' => 'required|file|mimes:jpg,jpeg,png|max:2048',
            ]);

            $bidang = Bidang::all();
            $pelatihan = pelatihan::all();
            // return $pelatihan;
            // return $bidang;


            $allData = array_merge($formData, $validatedData);

            // DONE: registrasi sudah disesuaikan dengan db baru
            // return $allData;
            // DB::transaction(function () use ($allData, $request) {
            // 1) Pastikan instansi ada
            $instansi = Instansi::firstOrCreate(
                [
                    'asal_instansi'     => $allData['asal_instansi'],
                    'alamat_instansi'   => $allData['alamat_instansi'],
                    'kota'   => $allData['kota'],
                    'kota_id'   => $allData['kota_id'],
                ],
                [
                    'bidang_keahlian'       => $allData['bidang_keahlian'],
                    'kelas'                 => $allData['kelas'],
                    'cabangDinas_id'  => $allData['cabangDinas_id'],
                ]
            );

            // 2) Buat/temukan user berdasarkan email
            //    - Jika user belum ada: buat dengan nama dan password default (mis: NIK atau random).
            //    - Jika sudah ada: opsional update name agar sinkron dengan form pendaftaran.
            $user = User::firstOrCreate(
                ['email' => $allData['email']],
                [
                    'name'     => $allData['nama'],
                    // pilih salah satu:
                    'password' => Hash::make(Carbon::parse($allData['tanggal_lahir'])->format('dmY')),
                    // 'password' => Hash::make(Str::random(16)),    // atau acak 16 char
                ]
            );

            // Opsional: sinkronkan nama jika user sudah ada tapi nama berbeda
            if ($user->wasRecentlyCreated === false && $user->name !== $allData['nama']) {
                $user->name = $allData['nama'];
                $user->save();
            }

            // 3) Buat peserta dan kaitkan ke user & instansi
            $peserta = Peserta::create([
                'pelatihan_id'  => $allData['pelatihan_id'],
                'instansi_id'   => $instansi->id,
                'bidang_id'     => $allData['bidang_keahlian'],
                'user_id'       => $user->id,             // <— penting: foreign key ke users
                'nama'          => $allData['nama'],
                'nik'           => $allData['nik'],
                'tempat_lahir'  => $allData['tempat_lahir'],
                'tanggal_lahir' => $allData['tanggal_lahir'],
                'jenis_kelamin' => $allData['jenis_kelamin'],
                'agama'         => $allData['agama'],
                'alamat'        => $allData['alamat'],
                'no_hp'         => $allData['no_hp'],
            ]);



            // 4) Lampiran + upload file
            $lampiranData = [
                'peserta_id'      => $peserta->id,
                'no_surat_tugas'  => $allData['no_surat_tugas'] ?? null,
            ];

            $fileFields = ['fc_ktp', 'fc_ijazah', 'fc_surat_tugas', 'fc_surat_sehat', 'pas_foto'];

            foreach ($fileFields as $field) {
                if ($request->hasFile($field)) {
                    $file = $request->file($field);
                    $fileName = $peserta->id . '_' . $peserta->bidang_id . '_' . $peserta->instansi_id
                        . '_' . $field . '.' . $file->extension();

                    // simpan di storage app/public/berkas_pendaftaran
                    $path = $file->storeAs('berkas_pendaftaran', $fileName, 'public');
                    $lampiranData[$field] = $path;
                }
            }

            Lampiran::create($lampiranData);

            // return true;
            // });

            $request->session()->forget('pendaftaran_data');
            return redirect()->route('pendaftaran.selesai')->with('success', 'Pendaftaran berhasil terkirim!');
        }
    }

    public function selesai()
    {
        return view('peserta.pendaftaran.selesai');
    }

    public function generateMassal()
    {
        $instruktur = Instruktur::with(['bidang', 'pelatihan'])->get();
        $pdf = Pdf::loadView('instruktur.cetak_massal', ['instruktur' => $instruktur])
            ->setPaper('A4', 'portrait');
        $fileName = 'Biodata_Instruktur_Massal_' . Carbon::now()->format('Y-m-d') . '.pdf';
        return $pdf->stream($fileName);
    }

    public function download_file(Request $request): BinaryFileResponse
    {
        $request->validate(['path' => 'required|string']);
        $filePath = $request->input('path');
        abort_if(!Storage::disk('public')->exists($filePath), 404, 'File not found.');

        return response()->download(storage_path('app/public/' . $filePath));
    }

    public function download(Peserta $peserta)
    {
        $lampiran = $peserta->lampiran;
        if (!$lampiran) {
            return back()->with('error', 'Peserta tidak memiliki lampiran.');
        }

        $filesToZip = [$lampiran->pas_foto, $lampiran->fc_ktp, $lampiran->fc_ijazah, $lampiran->fc_surat_sehat, $lampiran->fc_surat_tugas];
        $zipFileName = 'lampiran-' . Str::slug($peserta->nama) . '.zip';
        $zipPath = storage_path('app/temp/' . $zipFileName);

        $zip = new ZipArchive;
        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
            foreach ($filesToZip as $filePath) {
                if ($filePath && Storage::disk('public')->exists($filePath)) {
                    $zip->addFile(storage_path('app/public/' . $filePath), basename($filePath));
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
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'integer|exists:peserta,id',
            'excelFileName' => 'required|string'
        ]);

        $ids = $request->input('ids');
        $fileName = $request->input('excelFileName') . '.xlsx';

        return Excel::download(new PesertaExport($ids), $fileName);
    }

    public function testing()
    {
        $peserta = Peserta::with('instansi', 'lampiran')->get();
        return view('admin.testing', compact('peserta'));
    }

    // Placeholder methods
    public function show(string $id) {}
    public function edit(string $id) {}
    public function update(Request $request, string $id) {}
    public function destroy(string $id) {}
}
