<?php

namespace App\Http\Controllers;

use App\Exports\PesertaExport;
use App\Models\Bidang;
use App\Models\CabangDinas;
use App\Models\Instansi;
use App\Models\Instruktur;
use App\Models\Lampiran;
use App\Models\Pelatihan;
use App\Models\PendaftaranPelatihan;
use App\Models\Peserta;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\TemplateProcessor;
use ZipArchive;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class PendaftaranController extends Controller
{
    public const LAMPIRAN_DESTINATION = 'pertanyaan/opsi';

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
                $pelatihan = Pelatihan::find($pelatihanId);
                return view('peserta.pendaftaran.lampiran', compact('currentStep', 'allowedStep', 'formData', 'pelatihan'));
        }
    }

    /**
     * Generate Token Assessment Unik
     * Format: NAMA(5)-PELATIHANID-TAHUN-RANDOM(4)
     * Contoh: BUDI-12-2025-X7A9
     */
    private function generateUniqueAssessmentToken($nama, $pelatihanId)
    {
        // 1. Ambil Nama Depan (maks 5 huruf, uppercase, hapus spasi/simbol)
        $namaDepan = Str::upper(Str::slug(explode(' ', $nama)[0], ''));
        $namaClean = substr($namaDepan, 0, 5);
        
        // 2. Tahun Sekarang
        $tahun = date('Y');

        // 3. Generate Random String awal (4 karakter)
        $random = Str::upper(Str::random(4));

        // 4. Gabungkan
        $token = sprintf('%s-%s-%s-%s', $namaClean, $pelatihanId, $tahun, $random);

        // 5. Cek keunikan di database, jika ada duplikat, regenerate random-nya
        while (PendaftaranPelatihan::where('assessment_token', $token)->exists()) {
            $random = Str::upper(Str::random(4));
            $token = sprintf('%s-%s-%s-%s', $namaClean, $pelatihanId, $tahun, $random);
        }

        return $token;
    }

    public function store(Request $request)
    {
        $currentStep = $request->input('current_step');
        $formData = $request->session()->get('pendaftaran_data', []);

        // [STEP 1] Validasi Biodata Peserta
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

        // [STEP 2] Validasi Data Instansi/Sekolah
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

            $validatedData['asal_instansi'] = $this->normalizeAsalInstansi($validatedData['asal_instansi']);

            $request->session()->put('pendaftaran_data', array_merge($formData, $validatedData));
            $request->session()->put('pendaftaran_step', 3);
            return redirect()->route('pendaftaran.create', ['step' => 3]);
        }

        // [STEP 3] Validasi Lampiran & Finalisasi
        if ($currentStep == 3) {
            $validatedData = $request->validate([
                'fc_ktp' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
                'fc_ijazah' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
                'fc_surat_tugas' => 'file|mimes:pdf,jpg,jpeg,png|max:2048',
                'fc_surat_sehat' => 'file|mimes:pdf,jpg,jpeg,png|max:2048',
                'pas_foto' => 'required|file|mimes:jpg,jpeg,png|max:2048',
            ]);

            $allData = array_merge($formData, $validatedData);
            
            // Variabel untuk menampung hasil create di dalam closure transaction
            $pendaftaran = null;

            // [TRANSACTION] Simpan semua data sekaligus agar aman
            DB::transaction(function () use ($allData, $request, &$pendaftaran) {
                
                // 1. Buat/Ambil Instansi
                $instansi = Instansi::firstOrCreate(
                    [
                        'asal_instansi'   => $allData['asal_instansi'],
                        'alamat_instansi' => $allData['alamat_instansi'],
                        'kota'            => $allData['kota'],
                        'kota_id'         => $allData['kota_id'],
                    ],
                    [
                        'bidang_keahlian' => $allData['bidang_keahlian'],
                        'cabangDinas_id'  => $allData['cabangDinas_id'],
                    ]
                );

                // 2. Buat/Ambil User Login
                // PASSWORD = TANGGAL LAHIR FORMAT dmY (Contoh: 25081999)
                $passwordDob = Carbon::parse($allData['tanggal_lahir'])->format('dmY');

                $user = User::firstOrCreate(
                    ['email' => $allData['email']],
                    [
                        'name'     => $allData['nama'],
                        'password' => Hash::make($passwordDob),
                    ]
                );

                // Sinkron nama user jika ada perubahan
                if ($user->wasRecentlyCreated === false && $user->name !== $allData['nama']) {
                    $user->name = $allData['nama'];
                    // Opsional: Reset password user lama agar sesuai tgl lahir baru
                    // $user->password = Hash::make($passwordDob);
                    $user->save();
                }

                // 3. Buat Data Peserta
                $peserta = Peserta::create([
                    'pelatihan_id'  => $allData['pelatihan_id'],
                    'instansi_id'   => $instansi->id,
                    'bidang_id'     => $allData['bidang_keahlian'],
                    'user_id'       => $user->id,
                    'nama'          => $allData['nama'],
                    'nik'           => $allData['nik'],
                    'tempat_lahir'  => $allData['tempat_lahir'],
                    'tanggal_lahir' => $allData['tanggal_lahir'],
                    'jenis_kelamin' => $allData['jenis_kelamin'],
                    'agama'         => $allData['agama'],
                    'alamat'        => $allData['alamat'],
                    'no_hp'         => $allData['no_hp'],
                ]);

                // 4. Generate Nomor Registrasi & Urutan
                ['nomor' => $nomorReg, 'urutan' => $urutBidang] = $this->generateToken($allData['pelatihan_id'], $allData['bidang_keahlian']);

                // 5. [BARU] Generate Token Assessment
                $assessmentToken = $this->generateUniqueAssessmentToken($allData['nama'], $allData['pelatihan_id']);

                // 6. Upload Lampiran
                $lampiranData = [
                    'peserta_id'     => $peserta->id,
                    'no_surat_tugas' => $allData['no_surat_tugas'] ?? null,
                ];

                $fileFields = ['fc_ktp', 'fc_ijazah', 'fc_surat_tugas', 'fc_surat_sehat', 'pas_foto'];

                foreach ($fileFields as $field) {
                    if ($request->hasFile($field)) {
                        $file = $request->file($field);
                        $fileName = $peserta->id . '_' . $peserta->bidang_id . '_' . $peserta->instansi_id
                            . '_' . $field . '.' . $file->extension();

                        $targetDirectory = public_path(self::LAMPIRAN_DESTINATION);
                        if (! File::isDirectory($targetDirectory)) {
                            File::makeDirectory($targetDirectory, 0755, true);
                        }

                        $file->move($targetDirectory, $fileName);
                        $lampiranData[$field] = self::LAMPIRAN_DESTINATION . '/' . $fileName;
                    }
                }

                Lampiran::create($lampiranData);

                // 7. Simpan Pendaftaran dengan Token
                $pendaftaran = PendaftaranPelatihan::create([
                    'peserta_id'          => $peserta->id,
                    'pelatihan_id'        => $allData['pelatihan_id'],
                    'bidang_id'           => $allData['bidang_keahlian'],
                    'urutan_per_bidang'   => $urutBidang,
                    'nomor_registrasi'    => $nomorReg,
                    'tanggal_pendaftaran' => now(),
                    'kelas'               => $allData['kelas'],
                    'status_pendaftaran'  => 'menunggu_verifikasi',
                    'assessment_token'    => $assessmentToken,      // Simpan Token Baru
                    'token_expires_at'    => now()->addMonths(3),   // Token Expired
                ]);
            });

            // Load relasi agar data lengkap di halaman selesai
            if ($pendaftaran) {
                $pendaftaran->load('peserta', 'pelatihan', 'bidang');
            }

            $request->session()->forget('pendaftaran_data');
            
            return redirect()
                ->route('pendaftaran.selesai', ['id' => $pendaftaran->id])
                ->with('pendaftaran', $pendaftaran);
        }
    }

    public function selesai(int $id)
    {
        $pendaftaran = session('pendaftaran');

        // Kalau tidak ada (misal user reload / akses ulang lewat URL), ambil dari DB
        if (!$pendaftaran) {
            $pendaftaran = PendaftaranPelatihan::with('peserta', 'pelatihan', 'bidang')
                ->findOrFail($id);
        }

        return view('peserta.pendaftaran.selesai', compact('pendaftaran'));
    }

    // --- UTILITIES & EXPORTS ---

    public function generateMassal()
    {
        $instruktur = Instruktur::with(['bidang', 'pelatihan'])->get();
        $pdf = Pdf::loadView('Instruktur.cetak_massal', ['Instruktur' => $instruktur])
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

    public function exportBulk(Pelatihan $pelatihan)
    {
        $pendaftarans = PendaftaranPelatihan::with(['peserta', 'pelatihan', 'bidang'])
            ->where('pelatihan_id', $pelatihan->id)
            ->get();

        if ($pendaftarans->isEmpty()) {
            return back()->with('danger', 'Belum ada pendaftaran untuk pelatihan ini.');
        }

        $tmpDir = storage_path('app/tmp/exports');
        if (! is_dir($tmpDir)) @mkdir($tmpDir, 0775, true);

        $zipPath = $tmpDir . '/pendaftaran-' . now()->format('Ymd-His') . '.zip';
        $zip = new \ZipArchive();
        $zip->open($zipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);

        foreach ($pendaftarans as $pendaftaran) {
            $pdfPath = $this->generatePendaftaranPdf($pendaftaran);
            $zip->addFile($pdfPath, basename($pdfPath));
        }
        $zip->close();

        return response()->download($zipPath)->deleteFileAfterSend(true);
    }

    public function exportSample(Pelatihan $pelatihan)
    {
        $pendaftaran = PendaftaranPelatihan::with(['peserta', 'pelatihan', 'bidang'])
            ->where('pelatihan_id', $pelatihan->id)
            ->latest('id')
            ->firstOrFail();

        $pdfPath = $this->generatePendaftaranPdf($pendaftaran);
        return response()->download($pdfPath)->deleteFileAfterSend(true);
    }

    public function exportSingle(PendaftaranPelatihan $pendaftaran)
    {
        $pendaftaran->loadMissing(['peserta', 'pelatihan', 'bidang']);
        $pdfPath = $this->generatePendaftaranPdf($pendaftaran);
        return response()->download($pdfPath)->deleteFileAfterSend(true);
    }

    private function generatePendaftaranPdf(PendaftaranPelatihan $pendaftaran): string
    {
        $templatePath = Storage::path('templates/BIODATA_PESERTA_template.docx');

        $tp = new TemplateProcessor($templatePath);

        $pendaftaran->loadMissing(['peserta', 'pelatihan', 'bidang']);
        $p = $pendaftaran->peserta;
        $pl = $pendaftaran->pelatihan;
        $b = $pendaftaran->bidang;

        $tp->setValue('nama', $p->nama ?? '');
        $tp->setValue('tempat_lahir', $p->tempat_lahir ?? '');
        $tp->setValue('tanggal_lahir', optional($p->tanggal_lahir)->format('d-m-Y') ?? '');
        $tp->setValue('jenis_kelamin', $p->jenis_kelamin ?? '');
        $tp->setValue('agama', $p->agama ?? '');
        $tp->setValue('no_hp', $p->no_hp ?? '');
        $tp->setValue('nik', $p->nik ?? '');
        $tp->setValue('asal_instansi', $p->asal_instansi ?? '');
        $tp->setValue('alamat_instansi', $p->alamat_instansi ?? '');
        $tp->setValue('kelas', $pendaftaran->kelas ?? '');
        $tp->setValue('nama_bidang', $b->nama ?? '');
        $tp->setValue('judul', $pl->nama_pelatihan ?? '');
        $tp->setValue('tanggal_kegiatan', optional($pl->tanggal_mulai)?->translatedFormat('d F Y') ?? '');

        $tmp = storage_path('app/tmp/exports');
        if (!is_dir($tmp)) @mkdir($tmp, 0775, true);

        $base = Str::slug(($p->nama ?? 'peserta') . '-' . ($pl->nama_pelatihan ?? 'pelatihan'));
        $docx = "$tmp/$base.docx";
        $pdf  = "$tmp/$base.pdf";

        $tp->saveAs($docx);

        $phpWord = IOFactory::load($docx);
        $writer  = IOFactory::createWriter($phpWord, 'PDF');
        $writer->save($pdf);
        
        try {
            $writer->save($pdf);
        } finally {
            unset($writer, $phpWord);
            gc_collect_cycles();
        }

        // Hapus DOCX sementara
        if(file_exists($docx)) {
            @unlink($docx);
        }

        return $pdf;
    }

    public function testing()
    {
        $peserta = Peserta::with('instansi', 'lampiran')->get();
        return view('admin.testing', compact('peserta'));
    }

    public function show(string $id) {}
    public function edit(string $id) {}
    public function update(Request $request, string $id) {}
    public function destroy(string $id) {}

    private function normalizeAsalInstansi(?string $value): ?string
    {
        if ($value === null) {
            return null;
        }

        $normalized = preg_replace('/\bsmk\s+negeri\b/i', 'SMKN', $value);
        $normalized = preg_replace('/\bsma\s+negeri\b/i', 'SMAN', $normalized);
        $normalized = preg_replace('/\bsmkn\b/i', 'SMKN', $normalized);
        $normalized = preg_replace('/\bsman\b/i', 'SMAN', $normalized);

        return Str::squish($normalized);
    }

    private function generateToken(int $pelatihanId, int $bidangId): array
    {
        return DB::transaction(function () use ($pelatihanId, $bidangId) {
            $bidang     = Bidang::findOrFail($bidangId);
            $kodeBidang = $bidang->kode ?? $this->akronim($bidang->nama);

            PendaftaranPelatihan::where('pelatihan_id', $pelatihanId)
                ->where('bidang_id', $bidangId)
                ->select('id')
                ->lockForUpdate()
                ->get();

            $jumlah = PendaftaranPelatihan::where('pelatihan_id', $pelatihanId)
                ->where('bidang_id', $bidangId)
                ->count();

            $nextUrut = $jumlah + 1;
            $nomor    = sprintf('%d-%s-%03d', $pelatihanId, strtoupper($kodeBidang), $nextUrut);

            return ['nomor' => $nomor, 'urutan' => $nextUrut];
        }, 3);
    }

    private function akronim(string $nama): string
    {
        $words = preg_split('/\s+/', trim($nama));
        $akronim = collect($words)->map(fn($w) => \Illuminate\Support\Str::substr($w, 0, 1))->implode('');
        $akronim = preg_replace('/[^A-Za-z0-9]/', '', $akronim) ?: 'BDG';
        return strtoupper($akronim);
    }
}