<?php

namespace App\Http\Controllers;

use App\Exports\PesertaExport;
use App\Models\Kompetensi;
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

   public function showDaftar()
{
    // data master untuk form (dropdown)
    $kompetensi      = Kompetensi::all();
    $cabangDinas = CabangDinas::all();

    // 👉 ambil SATU pelatihan aktif, yang tanggalnya masih jalan
    $pelatihan = Pelatihan::with(['kompetensiPelatihan.kompetensi', 'kompetensiPelatihan.instruktur'])
        ->where('status', 'Pendaftaran Buka')
        ->whereDate('tanggal_selesai', '>=', now())
        ->orderBy('tanggal_mulai', 'asc')
        ->first();

    // kalau kamu tetap mau pakai variabel ini buat wizard di view, biarkan saja
    $currentStep = 1;
    $allowedStep = 1;
    $formData    = [];

    return view('pages.daftar', compact(
        'kompetensi',
        'cabangDinas',
        'pelatihan',
        'currentStep',
        'allowedStep',
        'formData'
    ));
}

    public function index()
    {
        // data master untuk form (dropdown)
        $kompetensi      = Kompetensi::all();
        $cabangDinas = CabangDinas::all();

        // 👉 ambil SATU pelatihan aktif, yang tanggalnya masih jalan
        $pelatihan = Pelatihan::with(['kompetensiPelatihan.kompetensi', 'kompetensiPelatihan.instruktur'])
            ->where('status', 'Pendaftaran Buka')
            ->whereDate('tanggal_selesai', '>=', now())
            ->orderBy('tanggal_mulai', 'asc')
            ->first();

        // kalau kamu tetap mau pakai variabel ini buat wizard di view, biarkan saja
        $currentStep = 1;
        $allowedStep = 1;
        $formData    = [];

        return view('pages.daftar', compact(
            'kompetensi',
            'cabangDinas',
            'pelatihan',
            'currentStep',
            'allowedStep',
            'formData'
        ));
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
                $pelatihan   = Pelatihan::all();

                return view(
                    'peserta.pendaftaran.bio-peserta',
                    compact('currentStep', 'allowedStep', 'formData', 'cabangDinas')
                );

            case 2:
                $pelatihan = Pelatihan::where('status', 'aktif')->get();
                $kompetensi = Kompetensi::all();
                $cabangDinas = CabangDinas::all();
                // return $pelatihan;
                return view('peserta.pendaftaran.bio-sekolah', compact('currentStep', 'allowedStep', 'formData', 'pelatihan', 'kompetensi', 'cabangDinas'));
            case 3:
                $pelatihanId = $formData['pelatihan_id'] ?? null;
                $pelatihan   = $pelatihanId ? Pelatihan::find($pelatihanId) : null;

                return view(
                    'peserta.pendaftaran.lampiran',
                    compact('currentStep', 'allowedStep', 'formData', 'pelatihan')
                );

            default:
                return redirect()->route('pendaftaran.create', ['step' => $allowedStep])
                    ->with('error', 'Step tidak valid.');
        }
    }

    /**
     * store => stepwise handler (1,2,3)
     */
    public function store(Request $request)
    {
        $currentStep = (int) $request->input('current_step', 1);
        $formData    = $request->session()->get('pendaftaran_data', []);

        // STEP 1: biodata
        if ($currentStep === 1) {
            $validatedData = $request->validate([
                'nama'          => 'required|string|max:150',
                'nik'           => 'required|string|digits:16|max:20|unique:peserta,nik',
                'no_hp'         => 'required|string|max:20',
                'email'         => 'required|email|max:255|unique:users,email',
                'tempat_lahir'  => 'required|string|max:100',
                'tanggal_lahir' => 'required|date',
                'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
                'agama'         => 'required|string|max:50',
                'alamat'        => 'required|string',
            ]);

            $request->session()->put('pendaftaran_data', array_merge($formData, $validatedData));
            $request->session()->put('pendaftaran_step', 2);

            return redirect()->route('pendaftaran.create', ['step' => 2]);
        }

        // STEP 2: instansi / sekolah
        if ($currentStep === 2) {
            $validatedData = $request->validate([
                'asal_instansi'    => 'required|string|max:255',
                'alamat_instansi'  => 'required|string',
                'kota'             => 'required|string|max:100',
                'kota_id'          => 'required|integer',
                'bidang_keahlian'  => 'required|string|max:255',
                'kelas'            => 'required|string|max:100',
                'cabangDinas_id'   => 'required|string|max:255',
                'pelatihan_id'     => 'required|string',
            ]);

            $validatedData['asal_instansi'] = $this->normalizeAsalInstansi($validatedData['asal_instansi']);

            $request->session()->put('pendaftaran_data', array_merge($formData, $validatedData));
            $request->session()->put('pendaftaran_step', 3);

            return redirect()->route('pendaftaran.create', ['step' => 3]);
        }

        // STEP 3: lampiran + final commit
        if ($currentStep === 3) {
            $validatedData = $request->validate([
                'fc_ktp'           => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
                'fc_ijazah'        => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
                'fc_surat_tugas'   => 'file|mimes:pdf,jpg,jpeg,png|max:2048',
                'fc_surat_sehat'   => 'file|mimes:pdf,jpg,jpeg,png|max:2048',
                'pas_foto'         => 'required|file|mimes:jpg,jpeg,png|max:2048',
                'nomor_surat_tugas'=> 'nullable|string|max:100',
            ]);

            $allData     = array_merge($formData, $validatedData);
            $pendaftaran = null;

            DB::transaction(function () use ($allData, $request, &$pendaftaran) {
                // Instansi
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

                // User
                $passwordDob = Carbon::parse($allData['tanggal_lahir'])->format('dmY');
                $user = User::firstOrCreate(
                    ['email' => $allData['email']],
                    [
                        'name'     => $allData['nama'],
                        'password' => Hash::make($passwordDob),
                    ]
                );

                if ($user->wasRecentlyCreated === false && $user->name !== $allData['nama']) {
                    $user->name = $allData['nama'];
                    $user->save();
                }

                // Peserta
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

                // Nomor registrasi & urutan
                ['nomor' => $nomorReg, 'urutan' => $urutBidang] =
                    $this->generateToken($allData['pelatihan_id'], $allData['bidang_keahlian']);

                // Generate assessment token unik
                $assessmentToken =
                    $this->generateUniqueAssessmentToken($allData['nama'], $allData['pelatihan_id']);

                // Upload lampiran
                $lampiranData = [
                    'peserta_id'     => $peserta->id,
                    'no_surat_tugas' => $allData['nomor_surat_tugas'] ?? null,
                ];

                $fileFields = ['fc_ktp', 'fc_ijazah', 'fc_surat_tugas', 'fc_surat_sehat', 'pas_foto'];

                foreach ($fileFields as $field) {
                    if ($request->hasFile($field)) {
                        $file     = $request->file($field);
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

                // Simpan pendaftaran
                $pendaftaran = PendaftaranPelatihan::create([
                    'peserta_id'          => $peserta->id,
                    'pelatihan_id'        => $allData['pelatihan_id'],
                    'bidang_id'           => $allData['bidang_keahlian'],
                    'urutan_per_bidang'   => $urutBidang,
                    'nomor_registrasi'    => $nomorReg,
                    'tanggal_pendaftaran' => now(),
                    'kelas'               => $allData['kelas'],
                    'status_pendaftaran'  => 'menunggu_verifikasi',
                    'assessment_token'    => $assessmentToken,
                    'token_expires_at'    => now()->addMonths(3),
                ]);
            });

            if ($pendaftaran) {
                $pendaftaran->load('peserta', 'pelatihan', 'bidang');
            }

            $request->session()->forget('pendaftaran_data');
            $request->session()->forget('pendaftaran_step');

            return redirect()
                ->route('pendaftaran.selesai', ['id' => $pendaftaran->id])
                ->with('pendaftaran', $pendaftaran);
        }

        return back()->with('error', 'Step pendaftaran tidak dikenali.');
    }

    /**
     * Alternatif: endpoint yang menerima seluruh data sekaligus (legacy / form non-wizard)
     * Nama method: storeComplete
     */
    public function storeComplete(Request $request)
    {
        $validatedData = $request->validate([
            // Step 1: Data Diri
            'nama'          => 'required|string|max:150',
            'nik'           => 'required|string|digits:16|max:20|unique:peserta,nik',
            'no_hp'         => 'required|string|max:20',
            'email'         => 'required|email|max:255|unique:users,email',
            'tempat_lahir'  => 'required|string|max:100',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'agama'         => 'required|string|max:50',
            'alamat'        => 'required|string',

            // Step 2: Data Instansi
            'asal_instansi'   => 'required|string|max:255',
            'alamat_instansi' => 'required|string',
            'kota' => 'required|string|max:100',
            'kota_id' => 'required|integer',
            'kompetensi_keahlian' => 'required|string|max:255',
            'kelas' => 'required|string|max:100',
            'cabangDinas_id' => 'required|string|max:255',
            'pelatihan_id' => 'required|string', // Hidden input

            // Step 3: Lampiran
            'fc_ktp'           => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'fc_ijazah'        => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'fc_surat_tugas'   => 'file|mimes:pdf,jpg,jpeg,png|max:2048',
            'fc_surat_sehat'   => 'file|mimes:pdf,jpg,jpeg,png|max:2048',
            'pas_foto'         => 'required|file|mimes:jpg,jpeg,png|max:2048',
            'nomor_surat_tugas'=> 'nullable|string|max:100',
        ]);

        $validatedData['asal_instansi'] =
            $this->normalizeAsalInstansi($validatedData['asal_instansi']);

        $pendaftaran = DB::transaction(function () use ($validatedData, $request) {
            // Instansi
            $instansi = Instansi::firstOrCreate(
                [
                    'asal_instansi'   => $validatedData['asal_instansi'],
                    'alamat_instansi' => $validatedData['alamat_instansi'],
                    'kota'            => $validatedData['kota'],
                    'kota_id'         => $validatedData['kota_id'],
                ],
                [
                    'kompetensi_keahlian' => $validatedData['kompetensi_keahlian'],
                    'cabangDinas_id'  => $validatedData['cabangDinas_id'],
                ]
            );

            // User
            $user = User::firstOrCreate(
                ['email' => $validatedData['email']],
                [
                    'name'     => $validatedData['nama'],
                    'password' => Hash::make(
                        Carbon::parse($validatedData['tanggal_lahir'])->format('dmY')
                    ),
                ]
            );

            if ($user->wasRecentlyCreated === false && $user->name !== $validatedData['nama']) {
                $user->name = $validatedData['nama'];
                $user->save();
            }

            // Peserta
            $peserta = Peserta::create([
                'pelatihan_id'  => $validatedData['pelatihan_id'],
                'instansi_id'   => $instansi->id,
                // 'kompetensi_id'     => $validatedData['kompetensi_keahlian'], // Removed as discussed
                'user_id'       => $user->id,
                'nama'          => $validatedData['nama'],
                'nik'           => $validatedData['nik'],
                'tempat_lahir'  => $validatedData['tempat_lahir'],
                'tanggal_lahir' => $validatedData['tanggal_lahir'],
                'jenis_kelamin' => $validatedData['jenis_kelamin'],
                'agama'         => $validatedData['agama'],
                'alamat'        => $validatedData['alamat'],
                'no_hp'         => $validatedData['no_hp'],
            ]);

            // D. Generate Token
            ['nomor' => $nomorReg, 'urutan' => $urutKompetensi] = $this->generateToken($validatedData['pelatihan_id'], $validatedData['kompetensi_keahlian']);

            // Lampiran
            $lampiranData = [
                'peserta_id'     => $peserta->id,
                'no_surat_tugas' => $validatedData['nomor_surat_tugas'] ?? null,
            ];

            $fileFields = ['fc_ktp', 'fc_ijazah', 'fc_surat_tugas', 'fc_surat_sehat', 'pas_foto'];

            foreach ($fileFields as $field) {
                if ($request->hasFile($field)) {
                    $file = $request->file($field);
                    // Use kompetensi_keahlian or similar for naming if needed, but here simple ID is consistent
                    $fileName = $peserta->id . '_' . $peserta->instansi_id
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

            PendaftaranPelatihan::create([
                'peserta_id'            => $peserta->id,
                'pelatihan_id'          => $validatedData['pelatihan_id'],
                'kompetensi_id'             => $validatedData['kompetensi_keahlian'],
                'urutan_per_kompetensi'     => $urutKompetensi,
                'nomor_registrasi'      => $nomorReg,
                'tanggal_pendaftaran'   => now(),
                'kelas'                 => $validatedData['kelas'],
            ]);

            return PendaftaranPelatihan::with('peserta', 'pelatihan', 'kompetensi')
                ->latest('id')
                ->first();
        });

        return redirect()
            ->route('pendaftaran.selesai', ['id' => $pendaftaran->id])
            ->with('pendaftaran', $pendaftaran);
    }

    /**
     * Halaman selesai / receipt
     */
    public function selesai(int $id)
    {
        $pendaftaran = session('pendaftaran');

        // Kalau tidak ada (misal user reload / akses ulang lewat URL), ambil dari DB
        if (!$pendaftaran) {
            $pendaftaran = PendaftaranPelatihan::with('peserta', 'pelatihan', 'kompetensi')
                ->findOrFail($id);
        }

        return view('peserta.pendaftaran.selesai', compact('pendaftaran'));
    }

    // =======================================================
    // # ADMIN UTILITIES (TOKEN GENERATION & EXPORT)
    // =======================================================

    /**
     * Generate token assessment unik untuk satu nama + pelatihan
     */
    private function generateUniqueAssessmentToken($nama, $pelatihanId)
    {
        $namaDepan = Str::upper(Str::slug(explode(' ', $nama)[0] ?? '', ''));
        $namaClean = substr($namaDepan, 0, 5);
        $tahun     = date('Y');
        $random    = Str::upper(Str::random(4));

        $token = sprintf('%s-%s-%s-%s', $namaClean, $pelatihanId, $tahun, $random);

        while (PendaftaranPelatihan::where('assessment_token', $token)->exists()) {
            $random = Str::upper(Str::random(4));
            $token  = sprintf('%s-%s-%s-%s', $namaClean, $pelatihanId, $tahun, $random);
        }

        return $token;
    }

    /**
     * Generate token massal (admin)
     */
    public function generateTokenMassal()
    {
        $pendaftarans = PendaftaranPelatihan::with('peserta')
            ->whereNull('assessment_token')
            ->get();

        $count = 0;
        $total = $pendaftarans->count();

        DB::transaction(function () use ($pendaftarans, &$count) {
            foreach ($pendaftarans as $pendaftaran) {
                if ($pendaftaran->peserta && $pendaftaran->pelatihan_id) {
                    $assessmentToken = $this->generateUniqueAssessmentToken(
                        $pendaftaran->peserta->nama,
                        $pendaftaran->pelatihan_id
                    );

                    $pendaftaran->assessment_token = $assessmentToken;
                    $pendaftaran->token_expires_at = now()->addMonths(3);
                    $pendaftaran->save();
                    $count++;
                }
            }
        });

        if ($count > 0) {
            return back()->with('success', "Berhasil men-generate {$count} token dari {$total} pendaftaran.");
        }

        return back()->with('error', 'Tidak ada pendaftaran baru yang perlu di-generate tokennya.');
    }

    /**
     * Download token assessment (fallback: return JSON)
     */
    public function downloadTokenAssessment(Request $request)
    {
        if (! auth()->check()) {
            return back()->with('error', 'Akses ditolak.');
        }

        $pendaftarans = PendaftaranPelatihan::with('peserta.user', 'pelatihan', 'bidang')
            ->whereNotNull('assessment_token')
            ->orderBy('pelatihan_id')
            ->get();

        if ($pendaftarans->isEmpty()) {
            return back()->with('error', 'Tidak ada Token Assessment yang terdaftar untuk di download.');
        }

        $exportData = $pendaftarans->map(function ($p) {
            $tanggalLahir = $p->peserta->tanggal_lahir ?? null;
            $password     = $tanggalLahir
                ? Carbon::parse($tanggalLahir)->format('dmY')
                : 'N/A';

            return [
                'ID Pendaftaran'       => $p->id,
                'Nomor Registrasi'     => $p->nomor_registrasi,
                'Nama Peserta'         => $p->peserta->nama ?? 'N/A',
                'Email'                => $p->peserta->user->email ?? 'N/A',
                'Pelatihan'            => $p->pelatihan->nama_pelatihan ?? 'N/A',
                'Bidang'               => $p->bidang->nama ?? 'N/A',
                'ASSESSMENT TOKEN'     => $p->assessment_token,
                'PASSWORD (DOB)'       => $password,
                'Token Berlaku Hingga' => $p->token_expires_at
                    ? Carbon::parse($p->token_expires_at)->format('d-m-Y')
                    : 'N/A',
            ];
        });

        return response()->json($exportData->toArray(), 200);
    }

    // =======================================================
    // # EXPORT / DOWNLOAD / PDF / ZIP UTILITIES
    // =======================================================

    public function generateMassal()
    {
        // Adjust Instruktur relation loading
        $instruktur = Instruktur::with(['kompetensi', 'pelatihan'])->get();
        $pdf = Pdf::loadView('Instruktur.cetak_massal', ['Instruktur' => $instruktur])
            ->setPaper('A4', 'portrait');

        $fileName = 'Biodata_Instruktur_Massal_' . Carbon::now()->format('Y-m-d') . '.pdf';

        return $pdf->stream($fileName);
    }

    public function download_file(Request $request): BinaryFileResponse
    {
        $request->validate(['path' => 'required|string']);

        $filePath = $request->input('path');

        abort_if(! Storage::disk('public')->exists($filePath), 404, 'File not found.');

        return response()->download(storage_path('app/public/' . $filePath));
    }

    public function download(Peserta $peserta)
    {
        $lampiran = $peserta->lampiran;

        if (! $lampiran) {
            return back()->with('error', 'Peserta tidak memiliki lampiran.');
        }

        $filesToZip = [
            $lampiran->pas_foto,
            $lampiran->fc_ktp,
            $lampiran->fc_ijazah,
            $lampiran->fc_surat_sehat,
            $lampiran->fc_surat_tugas,
        ];

        $zipFileName = 'lampiran-' . Str::slug($peserta->nama) . '.zip';
        $zipPath     = storage_path('app/temp/' . $zipFileName);

        $zip = new ZipArchive;

        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === true) {
            foreach ($filesToZip as $filePath) {
                if ($filePath && Storage::disk('public')->exists($filePath)) {
                    $zip->addFile(
                        storage_path('app/public/' . $filePath),
                        basename($filePath)
                    );
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
            'ids'          => 'required|array',
            'ids.*'        => 'integer|exists:peserta,id',
            'excelFileName'=> 'required|string',
        ]);

        $ids      = $request->input('ids');
        $fileName = $request->input('excelFileName') . '.xlsx';

        return Excel::download(new PesertaExport($ids), $fileName);
    }

    public function exportBulk(Pelatihan $pelatihan)
    {
        $pendaftarans = PendaftaranPelatihan::with(['peserta', 'pelatihan', 'kompetensi'])
            ->where('pelatihan_id', $pelatihan->id)
            ->get();

        if ($pendaftarans->isEmpty()) {
            return back()->with('danger', 'Belum ada pendaftaran untuk pelatihan ini.');
        }

        $tmpDir = storage_path('app/tmp/exports');
        if (! is_dir($tmpDir)) {
            @mkdir($tmpDir, 0775, true);
        }

        $zipPath = $tmpDir . '/pendaftaran-' . now()->format('Ymd-His') . '.zip';
        $zip     = new ZipArchive();
        $zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE);

        foreach ($pendaftarans as $pendaftaran) {
            $pdfPath = $this->generatePendaftaranPdf($pendaftaran);
            $zip->addFile($pdfPath, basename($pdfPath));
        }

        $zip->close();

        return response()->download($zipPath)->deleteFileAfterSend(true);
    }

    public function exportSample(Pelatihan $pelatihan)
    {
        $pendaftaran = PendaftaranPelatihan::with(['peserta', 'pelatihan', 'kompetensi'])
            ->where('pelatihan_id', $pelatihan->id)
            ->latest('id')
            ->firstOrFail();

        $pdfPath = $this->generatePendaftaranPdf($pendaftaran);

        return response()->download($pdfPath)->deleteFileAfterSend(true);
    }

    public function exportSingle(PendaftaranPelatihan $pendaftaran)
    {
        $pendaftaran->loadMissing(['peserta', 'pelatihan', 'kompetensi']);
        $pdfPath = $this->generatePendaftaranPdf($pendaftaran);

        return response()->download($pdfPath)->deleteFileAfterSend(true);
    }

    /**
     * Helper: isi template DOCX → render PDF → kembalikan path PDF di storage/tmp.
     */
    private function generatePendaftaranPdf(PendaftaranPelatihan $pendaftaran): string
    {
        $templatePath = Storage::path('templates/BIODATA_PESERTA_template.docx');

        $tp = new TemplateProcessor($templatePath);

        $pendaftaran->loadMissing(['peserta', 'pelatihan', 'kompetensi']);
        $p = $pendaftaran->peserta;
        $pl = $pendaftaran->pelatihan;
        $k = $pendaftaran->kompetensi;

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
        $tp->setValue('nama_kompetensi', $k->nama_kompetensi ?? ''); // Template variable might still be 'nama_kompetensi'
        $tp->setValue('nama_kompetensi', $k->nama_kompetensi ?? '');
        $tp->setValue('judul', $pl->nama_pelatihan ?? '');
        $tp->setValue(
            'tanggal_kegiatan',
            optional($pl->tanggal_mulai)?->translatedFormat('d F Y') ?? ''
        );

        $tmp = storage_path('app/tmp/exports');
        if (! is_dir($tmp)) {
            @mkdir($tmp, 0775, true);
        }

        $base = Str::slug(($p->nama ?? 'peserta') . '-' . ($pl->nama_pelatihan ?? 'pelatihan'));
        $docx = "$tmp/$base.docx";
        $pdf  = "$tmp/$base.pdf";

        $tp->saveAs($docx);

        $phpWord = IOFactory::load($docx);
        $writer  = IOFactory::createWriter($phpWord, 'PDF');
        $writer->save($pdf);

        unset($writer, $phpWord);
        gc_collect_cycles();

        $this->safeUnlink($docx);

        return $pdf;
    }

    public function testing()
    {
        $peserta = Peserta::with('instansi', 'lampiran')->get();

        return view('admin.testing', compact('peserta'));
    }

    // Placeholder CRUD methods (boleh diimplementasi nanti)
    public function show(string $id) {}
    public function edit(string $id) {}
    public function update(Request $request, string $id) {}
    public function destroy(string $id) {}

    // =======================================================
    // # HELPERS
    // =======================================================

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

    private function generateToken(int $pelatihanId, int $kompetensiId): array
    {
        // [Langkah 1] Memulai transaction, jika gagal akan di-rollback otomatis
        return DB::transaction(function () use ($pelatihanId, $kompetensiId) {
            $kompetensi     = Kompetensi::findOrFail($kompetensiId);
            $kodeKompetensi = $kompetensi->kode ?? $this->akronim($kompetensi->nama_kompetensi);

            PendaftaranPelatihan::where('pelatihan_id', $pelatihanId)
                ->where('kompetensi_id', $kompetensiId)
                ->select('id')
                ->lockForUpdate()
                ->get();

            $jumlah = PendaftaranPelatihan::where('pelatihan_id', $pelatihanId)
                ->where('kompetensi_id', $kompetensiId)
                ->count();

            $nextUrut = $jumlah + 1;
            $nomor    = sprintf('%d-%s-%03d', $pelatihanId, strtoupper($kodeKompetensi), $nextUrut);

            return ['nomor' => $nomor, 'urutan' => $nextUrut];
        }, 3);
    }

    private function akronim(string $nama): string
    {
        $words   = preg_split('/\s+/', trim($nama));
        $akronim = collect($words)
            ->map(fn ($w) => Str::substr($w, 0, 1))
            ->implode('');

        $akronim = preg_replace('/[^A-Za-z0-9]/', '', $akronim) ?: 'BDG';

        return strtoupper($akronim);
    }

    private function safeUnlink(string $path, int $retries = 10, int $sleepMs = 250): void
    {
        if (! file_exists($path)) {
            return;
        }

        for ($i = 0; $i < $retries; $i++) {
            if (@unlink($path)) {
                return;
            }

            clearstatcache(true, $path);
            gc_collect_cycles();
            usleep($sleepMs * 1000);
        }

        throw new \RuntimeException("Gagal unlink: {$path} setelah {$retries} percobaan");
    }
}
