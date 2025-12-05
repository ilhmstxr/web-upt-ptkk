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
use PhpOffice\PhpWord\Settings;
use PhpOffice\PhpWord\TemplateProcessor;
use ZipArchive;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class PendaftaranController extends Controller
{
    public const LAMPIRAN_DESTINATION = 'pertanyaan/opsi';

   public function showDaftar()
{
    // data master untuk form (dropdown)
    $bidang      = Bidang::all();
    $cabangDinas = CabangDinas::all();

    // 👉 ambil SATU pelatihan aktif, yang tanggalnya masih jalan
    $pelatihan = Pelatihan::with(['bidangPelatihan.bidang', 'bidangPelatihan.instruktur'])
        ->where('status', 'aktif')
        ->whereDate('tanggal_selesai', '>=', now())
        ->orderBy('tanggal_mulai', 'asc')
        ->first();

    // kalau kamu tetap mau pakai variabel ini buat wizard di view, biarkan saja
    $currentStep = 1;
    $allowedStep = 1;
    $formData    = [];

    return view('pages.daftar', compact(
        'bidang',
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
        $bidang      = Bidang::all();
        $cabangDinas = CabangDinas::all();

        // 👉 ambil SATU pelatihan aktif, yang tanggalnya masih jalan
        $pelatihan = Pelatihan::with(['bidangPelatihan.bidang', 'bidangPelatihan.instruktur'])
            ->where('status', 'aktif')
            ->whereDate('tanggal_selesai', '>=', now())
            ->orderBy('tanggal_mulai', 'asc')
            ->first();

        // kalau kamu tetap mau pakai variabel ini buat wizard di view, biarkan saja
        $currentStep = 1;
        $allowedStep = 1;
        $formData    = [];

        return view('pages.daftar', compact(
            'bidang',
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
                $pelatihan = Pelatihan::all();
                return view('peserta.pendaftaran.bio-peserta', compact('currentStep', 'allowedStep', 'formData', 'cabangDinas'));
            case 2:
                $pelatihan = Pelatihan::where('status', 'aktif')->get();
                $bidang = Bidang::all();
                $cabangDinas = CabangDinas::all();
                // return $pelatihan;
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
        // 1. Validasi Semua Data Sekaligus
        $validatedData = $request->validate([
            // Step 1: Data Diri
            'nama' => 'required|string|max:150',
            'nik' => 'required|string|digits:16|max:20|unique:peserta,nik',
            'no_hp' => 'required|string|max:20',
            'email' => 'required|email|max:255|unique:users,email',
            'tempat_lahir' => 'required|string|max:100',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'agama' => 'required|string|max:50',
            'alamat' => 'required|string',

            // Step 2: Data Instansi
            'asal_instansi' => 'required|string|max:255',
            'alamat_instansi' => 'required|string',
            'kota' => 'required|string|max:100',
            'kota_id' => 'required|integer',
            'bidang_keahlian' => 'required|string|max:255',
            'kelas' => 'required|string|max:100',
            'cabangDinas_id' => 'required|string|max:255',
            'pelatihan_id' => 'required|string', // Hidden input

            // Step 3: Lampiran
            'fc_ktp' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'fc_ijazah' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'fc_surat_tugas' => 'file|mimes:pdf,jpg,jpeg,png|max:2048',
            'fc_surat_sehat' => 'file|mimes:pdf,jpg,jpeg,png|max:2048',
            'pas_foto' => 'required|file|mimes:jpg,jpeg,png|max:2048',
            'nomor_surat_tugas' => 'nullable|string|max:100',
        ]);

        $validatedData['asal_instansi'] = $this->normalizeAsalInstansi($validatedData['asal_instansi']);

        // 2. Simpan ke DB dalam transaction
        $pendaftaran = DB::transaction(function () use ($validatedData, $request) {
            // A. Instansi
            $instansi = Instansi::firstOrCreate(
                [
                    'asal_instansi'     => $validatedData['asal_instansi'],
                    'alamat_instansi'   => $validatedData['alamat_instansi'],
                    'kota'              => $validatedData['kota'],
                    'kota_id'           => $validatedData['kota_id'],
                ],
                [
                    'bidang_keahlian' => $validatedData['bidang_keahlian'],
                    'cabangDinas_id'  => $validatedData['cabangDinas_id'],
                ]
            );

            // B. User
            $user = User::firstOrCreate(
                ['email' => $validatedData['email']],
                [
                    'name'     => $validatedData['nama'],
                    'password' => Hash::make(Carbon::parse($validatedData['tanggal_lahir'])->format('dmY')),
                ]
            );

            if ($user->wasRecentlyCreated === false && $user->name !== $validatedData['nama']) {
                $user->name = $validatedData['nama'];
                $user->save();
            }

            // C. Peserta
            $peserta = Peserta::create([
                'pelatihan_id'  => $validatedData['pelatihan_id'],
                'instansi_id'   => $instansi->id,
                'bidang_id'     => $validatedData['bidang_keahlian'],
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
            ['nomor' => $nomorReg, 'urutan' => $urutBidang] = $this->generateToken($validatedData['pelatihan_id'], $validatedData['bidang_keahlian']);

            // E. Lampiran
            $lampiranData = [
                'peserta_id'      => $peserta->id,
                'no_surat_tugas'  => $validatedData['nomor_surat_tugas'] ?? null,
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

            // F. PendaftaranPelatihan
            PendaftaranPelatihan::create([
                'peserta_id'            => $peserta->id,
                'pelatihan_id'          => $validatedData['pelatihan_id'],
                'bidang_id'             => $validatedData['bidang_keahlian'],
                'urutan_per_bidang'     => $urutBidang,
                'nomor_registrasi'      => $nomorReg,
                'tanggal_pendaftaran'   => now(),
                'kelas'                 => $validatedData['kelas'],
            ]);

            return PendaftaranPelatihan::with('peserta', 'pelatihan', 'bidang')
                ->latest('id')
                ->first();
        });

        // Hapus session jika ada
        $request->session()->forget('pendaftaran_data');
        $request->session()->forget('pendaftaran_step');

        return redirect()
            ->route('pendaftaran.selesai', ['id' => $pendaftaran->id])
            ->with('pendaftaran', $pendaftaran);
    }

    // public function selesai(int $id)
    public function selesai(int $id)
    {
        // return $id;
        $pendaftaran = session('pendaftaran');

        // Kalau tidak ada (misal user reload / akses ulang lewat URL), ambil dari DB
        if (!$pendaftaran) {
            $pendaftaran = PendaftaranPelatihan::with('peserta', 'pelatihan', 'bidang')
                ->findOrFail($id);
        }

        // return $pendaftaran;
        return view('peserta.pendaftaran.selesai', compact('pendaftaran'));
    }

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

    /**
     * Helper: isi template DOCX → render PDF → kembalikan path PDF di storage/tmp.
     */
    private function generatePendaftaranPdf(PendaftaranPelatihan $pendaftaran): string
    {
        $templatePath = Storage::path('templates/BIODATA_PESERTA_template.docx');

        $tp = new TemplateProcessor($templatePath);

        $pendaftaran->loadMissing(['peserta', 'pelatihan', 'bidang']);
        $p = $pendaftaran->peserta;
        $pl = $pendaftaran->pelatihan;
        $b = $pendaftaran->bidang;

        // isi placeholder sesuai template
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

        // simpan DOCX hasil merge
        $tp->saveAs($docx);

        // load DOCX → render ke PDF
        $phpWord = IOFactory::load($docx);
        $writer  = IOFactory::createWriter($phpWord, 'PDF'); // ← akan pakai DomPDF karena sudah diset di AppServiceProvider
        $writer->save($pdf);
        try {
            $writer->save($pdf);
        } finally {
            // pastikan semua handle/stream dilepas sebelum unlink
            unset($writer, $phpWord);
            gc_collect_cycles();   // bantu bebaskan resource di Windows
        }

        // 2) Hapus DOCX dengan retry (atasi "Resource temporarily unavailable")
        function safeUnlink(string $path, int $retries = 10, int $sleepMs = 250): void
        {
            if (!file_exists($path)) return;

            for ($i = 0; $i < $retries; $i++) {
                if (@unlink($path)) {
                    return; // sukses
                }
                clearstatcache(true, $path);
                gc_collect_cycles();
                usleep($sleepMs * 1000); // tunggu sebentar biar lock lepas
            }

            throw new \RuntimeException("Gagal unlink: {$path} setelah {$retries} percobaan");
        }

        safeUnlink($docx);

        return $pdf;
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
        // [Langkah 1] Memulai transaction, jika gagal akan di-rollback otomatis
        return DB::transaction(function () use ($pelatihanId, $bidangId) {
            $bidang     = Bidang::findOrFail($bidangId);
            $kodeBidang = $bidang->kode ?? $this->akronim($bidang->nama);

            // [Langkah 2] Kunci semua baris yang relevan
            // Proses lain yang mencoba mengakses baris ini akan disuruh MENUNGGU
            PendaftaranPelatihan::where('pelatihan_id', $pelatihanId)
                ->where('bidang_id', $bidangId)
                ->select('id')
                ->lockForUpdate()
                ->get();


            // [Langkah 3] Hitung jumlah pendaftar
            // Karena proses lain menunggu, hitungan ini DIJAMIN akurat
            $jumlah = PendaftaranPelatihan::where('pelatihan_id', $pelatihanId)
                ->where('bidang_id', $bidangId)
                ->count();

            // [Langkah 4] Buat nomor unik yang sudah pasti aman
            $nextUrut = $jumlah + 1;
            $nomor    = sprintf('%d-%s-%03d', $pelatihanId, strtoupper($kodeBidang), $nextUrut);

            return ['nomor' => $nomor, 'urutan' => $nextUrut];
        }, 3); // Coba ulang 3 kali jika terjadi deadlock
    }


    private function akronim(string $nama): string
    {
        $words = preg_split('/\s+/', trim($nama));
        $akronim = collect($words)->map(fn($w) => \Illuminate\Support\Str::substr($w, 0, 1))->implode('');
        $akronim = preg_replace('/[^A-Za-z0-9]/', '', $akronim) ?: 'BDG';
        return strtoupper($akronim);
    }
}
