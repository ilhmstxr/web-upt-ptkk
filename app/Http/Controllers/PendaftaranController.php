<?php

namespace App\Http\Controllers;

use App\Exports\PesertaExport;
use App\Models\Kompetensi;
use App\Models\CabangDinas;
use App\Models\Instansi;
use App\Models\Instruktur;
use App\Models\KompetensiPelatihan;
use App\Models\LampiranPeserta;
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
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\TemplateProcessor;
use ZipArchive;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class PendaftaranController extends Controller
{
    public const LAMPIRAN_DESTINATION = 'lampiran-peserta';

    /**
     * Halaman formulir pendaftaran (multi step).
     */
    public function index()
    {
        // Data master untuk dropdown
        $cabangDinas = CabangDinas::all();

        // Ambil SATU pelatihan aktif (status = 'aktif')
        $pelatihan = Pelatihan::with(['kompetensiPelatihan.kompetensi', 'kompetensiPelatihan.instrukturs'])
            ->where('status', 'aktif') // <= fix: pakai lowercase sesuai di Resource
            ->orderBy('tanggal_mulai', 'asc')
            ->first();

        if ($pelatihan) {
            // Jika ada pelatihan aktif → ambil kompetensi2 untuk pelatihan itu
            $kompetensi = KompetensiPelatihan::where('pelatihan_id', $pelatihan->id)->get();
            $pelatihans = null;
        } else {
            // Tidak ada pelatihan aktif → fallback: semua pelatihan
            $pelatihans = Pelatihan::orderBy('tanggal_mulai', 'asc')->get();

            $firstPel = $pelatihans->first();
            $kompetensi = $firstPel
                ? KompetensiPelatihan::where('pelatihan_id', $firstPel->id)->get()
                : collect();
        }

        return view('pages.daftar', compact('pelatihan', 'pelatihans', 'kompetensi', 'cabangDinas'));
    }

    /**
     * Store pendaftaran baru (full submit dari step 1–3).
     */
    public function store(Request $request)
    {
        // dd($request->all(), $request->files->all());

        $validatedData = $request->validate([
            // STEP 1: Data diri
            'nama' => 'required|string|max:150',
            'nik' => 'required|string|digits:16|max:20|unique:peserta,nik',
            'no_hp' => 'required|string|max:20',
            'email' => 'required|email|max:255|unique:users,email',
            'tempat_lahir' => 'required|string|max:100',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'agama' => 'required|string|max:50',
            'alamat' => 'required|string',

            // STEP 2: Data instansi/sekolah
            'asal_instansi' => 'required|string|max:255',
            'alamat_instansi' => 'required|string',
            'kota' => 'required|string|max:100',
            'kota_id' => 'required|integer',
            // ini sebenarnya id dari tabel kompetensi_pelatihan
            'kompetensi_keahlian' => 'required',
            'kelas' => 'required|string|max:100',
            'cabangDinas_id' => 'required',
            'pelatihan_id' => 'required', // hidden input

            // STEP 3: Lampiran
            'fc_ktp' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'fc_ijazah' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'fc_surat_tugas' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'fc_surat_sehat' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'pas_foto' => 'required|file|mimes:jpg,jpeg,png|max:2048',
            'nomor_surat_tugas' => 'nullable|string|max:100',
        ]);

        $validatedData['asal_instansi'] = $this->normalizeAsalInstansi($validatedData['asal_instansi']);

        try {
            $pendaftaran = DB::transaction(function () use ($validatedData, $request) {
                // =========================
                // INSTANSI
                // =========================
                $instansi = Instansi::firstOrCreate(
                    [
                        'asal_instansi' => $validatedData['asal_instansi'],
                        'alamat_instansi' => $validatedData['alamat_instansi'],
                        'kota' => $validatedData['kota'],
                        'kota_id' => $validatedData['kota_id'],
                    ],
                    [
                        'kompetensi_keahlian' => $validatedData['kompetensi_keahlian'],
                        'cabangDinas_id' => $validatedData['cabangDinas_id'],
                    ]
                );

                // =========================
                // USER
                // =========================
                $user = User::firstOrCreate(
                    ['email' => $validatedData['email']],
                    [
                        'name' => $validatedData['nama'],
                        'password' => Hash::make(
                            Carbon::parse($validatedData['tanggal_lahir'])->format('dmY')
                        ),
                        'phone' => $validatedData['no_hp'],
                    ]
                );

                if ($user->wasRecentlyCreated === false && $user->name !== $validatedData['nama']) {
                    $user->name = $validatedData['nama'];
                    $user->save();
                }

                // =========================
                // KOMPETENSI (pivot)
                // =========================
                $kpId = $validatedData['kompetensi_keahlian']; // id dari tabel kompetensi_pelatihan
                $kp = KompetensiPelatihan::findOrFail($kpId);
                $realKompetensiId = $kp->kompetensi_id;

                // =========================
                // PESERTA
                // =========================
                $pesertaData = [
                    'pelatihan_id' => $validatedData['pelatihan_id'],
                    'instansi_id' => $instansi->id,
                    'user_id' => $user->id,
                    'kompetensi_id' => $kpId, // atau $realKompetensiId, sesuaikan skema
                    'nama' => $validatedData['nama'],
                    'nik' => $validatedData['nik'],
                    'tempat_lahir' => $validatedData['tempat_lahir'],
                    'tanggal_lahir' => $validatedData['tanggal_lahir'],
                    'jenis_kelamin' => $validatedData['jenis_kelamin'],
                    'agama' => $validatedData['agama'],
                    'alamat' => $validatedData['alamat'],
                    'no_hp' => $validatedData['no_hp'],
                ];

                $peserta = Peserta::updateOrCreate(
                    ['nik' => $validatedData['nik']],
                    $pesertaData
                );

                // =========================
                // NOMOR REGISTRASI
                // =========================
                ['nomor' => $nomorReg, 'urutan' => $urutKompetensi] =
                    $this->generateToken($validatedData['pelatihan_id'], $realKompetensiId);

                // =========================
                // LAMPIRAN
                // =========================
                $lampiranData = [
                    'peserta_id' => $peserta->id,
                    'no_surat_tugas' => $validatedData['nomor_surat_tugas'] ?? null,
                ];

                $fileFields = ['fc_ktp', 'fc_ijazah', 'fc_surat_tugas', 'fc_surat_sehat', 'pas_foto'];

                foreach ($fileFields as $field) {
                    if ($request->hasFile($field)) {
                        $file = $request->file($field);
                        $fileName = $peserta->id . '_' . $peserta->instansi_id . '_' . $field . '.' . $file->extension();
                        $path = $file->storeAs(self::LAMPIRAN_DESTINATION, $fileName, 'public');
                        $lampiranData[$field] = $path;
                    }
                }

                LampiranPeserta::updateOrCreate(
                    ['peserta_id' => $peserta->id],
                    $lampiranData
                );

                // =========================
                // PENDAFTARAN
                // =========================
                $pendaftaranData = [
                    'peserta_id' => $peserta->id,
                    'pelatihan_id' => $validatedData['pelatihan_id'],
                    'kompetensi_pelatihan_id' => $kpId,
                    'kompetensi_id' => $realKompetensiId,
                    'kelas' => $validatedData['kelas'],

                    'nilai_pre_test' => 0,
                    'nilai_post_test' => 0,
                    'nilai_praktek' => 0,
                    'rata_rata' => 0,
                    'nilai_survey' => 0,
                    'status' => 'Belum Lulus',

                    'status_pendaftaran' => 'verifikasi',
                    'nomor_registrasi' => $nomorReg,
                    'tanggal_pendaftaran' => now(),
                ];

                $pendaftaranFinal = PendaftaranPelatihan::updateOrCreate(
                    [
                        'peserta_id' => $peserta->id,
                        'pelatihan_id' => $validatedData['pelatihan_id'],
                    ],
                    $pendaftaranData
                );

                // ⬇⬇⬇ PENTING: return dari closure supaya $pendaftaran terisi
                return $pendaftaranFinal;
            });

            // Send Email 1: Registration Received (without token)
            try {
                $pendaftaran->load(['peserta', 'pelatihan', 'kompetensiPelatihan.kompetensi']);
                \Mail::to($validatedData['email'])->send(new \App\Mail\RegistrationReceived($pendaftaran));
            } catch (\Exception $e) {
                \Log::error('Failed to send registration received email: ' . $e->getMessage());
            }

            return redirect()
                ->route('pendaftaran.selesai', ['id' => $pendaftaran->id])
                ->with('success', 'Pendaftaran berhasil!')
                ->with('pendaftaran', $pendaftaran);
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat memproses pendaftaran: ' . $e->getMessage());
        }
    }

    /**
     * Halaman selesai / receipt.
     */
    public function selesai(int $id)
    {
        $pendaftaran = session('pendaftaran');

        if (!$pendaftaran) {
            $pendaftaran = PendaftaranPelatihan::with('peserta', 'pelatihan', 'kompetensi')
                ->findOrFail($id);
        }

        return view('peserta.pendaftaran.selesai', compact('pendaftaran'));
    }

    // =======================================================
    // # EXPORT / DOWNLOAD / PDF / ZIP UTILITIES
    // =======================================================

    public function generateMassal()
    {
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

        abort_if(!Storage::disk('public')->exists($filePath), 404, 'File not found.');

        return response()->download(storage_path('app/public/' . $filePath));
    }

    public function download(Peserta $peserta)
    {
        $lampiran = $peserta->lampiran;

        if (!$lampiran) {
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
        $zipPath = storage_path('app/temp/' . $zipFileName);

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
            'ids' => 'required|array',
            'ids.*' => 'integer|exists:peserta,id',
            'excelFileName' => 'required|string',
        ]);

        $ids = $request->input('ids');
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
        if (!is_dir($tmpDir)) {
            @mkdir($tmpDir, 0775, true);
        }

        $zipPath = $tmpDir . '/pendaftaran-' . now()->format('Ymd-His') . '.zip';
        $zip = new ZipArchive();
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
        $tp->setValue('nama_kompetensi', $k->nama_kompetensi ?? '');
        $tp->setValue('judul', $pl->nama_pelatihan ?? '');
        $tp->setValue(
            'tanggal_kegiatan',
            optional($pl->tanggal_mulai)?->translatedFormat('d F Y') ?? ''
        );

        $tmp = storage_path('app/tmp/exports');
        if (!is_dir($tmp)) {
            @mkdir($tmp, 0775, true);
        }

        $base = Str::slug(($p->nama ?? 'peserta') . '-' . ($pl->nama_pelatihan ?? 'pelatihan'));
        $docx = "$tmp/$base.docx";
        $pdf = "$tmp/$base.pdf";

        $tp->saveAs($docx);

        $phpWord = IOFactory::load($docx);
        $writer = IOFactory::createWriter($phpWord, 'PDF');
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

    // Placeholder CRUD (ga kepake sekarang)
    public function show(string $id)
    {
    }
    public function edit(string $id)
    {
    }
    public function update(Request $request, string $id)
    {
    }
    public function destroy(string $id)
    {
    }

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
        return DB::transaction(function () use ($pelatihanId, $kompetensiId) {
            $kompetensi = Kompetensi::findOrFail($kompetensiId);
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
            $nomor = sprintf('%d-%s-%03d', $pelatihanId, strtoupper($kodeKompetensi), $nextUrut);

            return ['nomor' => $nomor, 'urutan' => $nextUrut];
        }, 3);
    }

    private function akronim(string $nama): string
    {
        $words = preg_split('/\s+/', trim($nama));
        $akronim = collect($words)
            ->map(fn($w) => Str::substr($w, 0, 1))
            ->implode('');

        $akronim = preg_replace('/[^A-Za-z0-9]/', '', $akronim) ?: 'BDG';

        return strtoupper($akronim);
    }

    private function safeUnlink(string $path, int $retries = 10, int $sleepMs = 250): void
    {
        if (!file_exists($path)) {
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
