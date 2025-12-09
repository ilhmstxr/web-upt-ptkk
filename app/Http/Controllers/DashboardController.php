<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use App\Models\Tes;
use App\Models\Peserta;
use App\Models\Percobaan;
use App\Models\JawabanUser;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * default fallback tes IDs (jika perlu fallback ke id statis)
     * sesuaikan bila DB Anda punya id berbeda untuk pre/post/monev
     */
    protected array $defaultTesIds = [
        'pre'  => 1,
        'post' => 2,
        'monev'=> 3,
    ];

    /**
     * helper: kembalikan key & id participant yang aktif.
     * returns ['key'=>'peserta_id'|'pesertaSurvei_id', 'id'=>int|null]
     */
    protected function getParticipantKeyAndId(): array
    {
        $pesertaId = session('peserta_id') ?? null;
        if ($pesertaId) {
            return ['key' => 'peserta_id', 'id' => $pesertaId];
        }

        $psId = session('pesertaSurvei_id') ?? null;
        if ($psId) {
            return ['key' => 'pesertaSurvei_id', 'id' => $psId];
        }

        return ['key' => null, 'id' => null];
    }

    /**
     * Cari Tes berdasarkan tipe logis: 'pre','post','monev'
     * 1) Cari berdasarkan judul (like)
     * 2) Jika ada kolom sub_tipe, cari berdasarkan sub_tipe
     * 3) Fallback ke id default (konfigurasi di $defaultTesIds)
     */
    protected function getTesByType(string $type): ?Tes
    {
        $type = strtolower($type);

        if ($type === 'pre') {
            // cari judul mengandung "pre test"
            $tes = Tes::whereRaw('LOWER(judul) LIKE ?', ['%pre test%'])->first();
            if ($tes) return $tes;

            // jika ada sub_tipe, coba cari
            if (Schema::hasColumn('tes', 'sub_tipe')) {
                $tes = Tes::where('sub_tipe', 'pre-test')->first();
                if ($tes) return $tes;
            }

            // fallback ke id default jika ada
            return Tes::find($this->defaultTesIds['pre']);
        }

        if ($type === 'post') {
            $tes = Tes::whereRaw('LOWER(judul) LIKE ?', ['%post test%'])->first();
            if ($tes) return $tes;

            if (Schema::hasColumn('tes', 'sub_tipe')) {
                $tes = Tes::where('sub_tipe', 'post-test')->first();
                if ($tes) return $tes;
            }

            return Tes::find($this->defaultTesIds['post']);
        }

        if ($type === 'monev') {
            if (Schema::hasColumn('tes', 'sub_tipe')) {
                $tes = Tes::where('sub_tipe', 'monev')->first();
                if ($tes) return $tes;
            }

            $tes = Tes::whereRaw('LOWER(judul) LIKE ?', ['%monev%'])->first();
            if ($tes) return $tes;

            return Tes::find($this->defaultTesIds['monev']);
        }

        return null;
    }

    /**
     * Hitung statistik singkat pre/post/monev untuk peserta aktif.
     * Menggunakan getTesByType sehingga fleksibel terhadap isi tabel tes.
     */
    private function getTestStats(): array
    {
        ['key' => $key, 'id' => $id] = $this->getParticipantKeyAndId();

        $stats = [
            'preTestAttempts'  => 0,
            'postTestAttempts' => 0,
            'monevAttempts'    => 0,
            'preTestScore'     => null,
            'postTestScore'    => null,
            'monevScore'       => null,
            'preTestDone'      => false,
            'postTestDone'     => false,
            'monevDone'        => false,
        ];

        if (! $key || ! $id) return $stats;

        $preTes = $this->getTesByType('pre');
        $postTes = $this->getTesByType('post');
        $monevTes = $this->getTesByType('monev');

        if ($preTes) {
            $stats['preTestAttempts'] = Percobaan::where('tes_id', $preTes->id)->where($key, $id)->count();
            $pre = Percobaan::where('tes_id', $preTes->id)->where($key, $id)->whereNotNull('waktu_selesai')->latest('waktu_selesai')->first();
            $stats['preTestScore'] = $pre->skor ?? null;
            $stats['preTestDone'] = (bool)$pre;
        }

        if ($postTes) {
            $stats['postTestAttempts'] = Percobaan::where('tes_id', $postTes->id)->where($key, $id)->count();
            $post = Percobaan::where('tes_id', $postTes->id)->where($key, $id)->whereNotNull('waktu_selesai')->latest('waktu_selesai')->first();
            $stats['postTestScore'] = $post->skor ?? null;
            $stats['postTestDone'] = (bool)$post;
        }

        if ($monevTes) {
            $stats['monevAttempts'] = Percobaan::where('tes_id', $monevTes->id)->where($key, $id)->count();
            $mon = Percobaan::where('tes_id', $monevTes->id)->where($key, $id)->whereNotNull('waktu_selesai')->latest('waktu_selesai')->first();
            $stats['monevScore'] = $mon->skor ?? null;
            $stats['monevDone'] = (bool)$mon;
        }

        return $stats;
    }

    // ======================
    // DASHBOARD INDEX
    // ======================
    public function index(): View
    {
        $stats = $this->getTestStats();
        $surveyStatus = 'pending';
        return view('dashboard.index', array_merge(['surveyStatus' => $surveyStatus], $stats));
    }

    // ======================
    // HOME & PROFILE
    // ======================
    public function home(): View
    {
        $peserta = Peserta::all();
        $participant = $this->getParticipantKeyAndId();
        $pesertaAktif = ($participant['key'] === 'peserta_id') ? Peserta::find($participant['id']) : null;
        $stats = $this->getTestStats();
        return view('dashboard.pages.home', array_merge(
            ['peserta' => $peserta, 'pesertaAktif' => $pesertaAktif],
            $stats
        ));
    }

    public function profile()
    {
        return view('dashboard.pages.profile');
    }

    public function materi()
    {
        return view('dashboard.pages.materi');
    }

    public function materiShow($materi)
    {
        return view('dashboard.pages.materi-show', compact('materi'));
    }

    // ======================
    // SET / STORE PESERTA dari MODAL HOME
    // ======================
    public function setPeserta(Request $request)
    {
        $validated = $request->validate([
            'nama' => ['required','string','max:150'],
            'peserta_id' => 'nullable|exists:peserta,id',
        ]);

        // Jika peserta_id diberikan langsung (hidden) — pilih langsung
        if ($request->filled('peserta_id')) {
            $peserta = Peserta::findOrFail($request->peserta_id);
            session(['peserta_id' => $peserta->id]);
            return redirect()->route('dashboard.home')->with('success','Peserta dipilih: '.$peserta->nama);
        }

        $namaRaw = trim($validated['nama']);
        $nama = mb_strtolower($namaRaw);

        $matches = Peserta::with('instansi:id,asal_instansi,kota')
            ->whereRaw('LOWER(nama) = ?', [$nama])
            ->get();

        if ($matches->isEmpty()) {
            $like = '%'.str_replace(' ','%',$nama).'%';
            $matches = Peserta::with('instansi:id,asal_instansi,kota')
                ->whereRaw('LOWER(nama) LIKE ?', [$like])
                ->orderBy('nama')
                ->get();
        }

        if ($matches->isEmpty()) {
            return back()->withInput()->with('error','Peserta tidak ditemukan.');
        }

        // ambil pertama (atau implementasikan pilihan multiple later)
        $peserta = $matches->first();

        // aman: hapus session peserta lama, regenerate session id untuk keamanan
        $request->session()->forget(['peserta_id','instansi_id','instansi_nama','instansi_kota']);
        $request->session()->regenerate();

        session([
            'peserta_id'    => $peserta->id,
            'instansi_id'   => optional($peserta->instansi)->id,
            'instansi_nama' => optional($peserta->instansi)->asal_instansi,
            'instansi_kota' => optional($peserta->instansi)->kota,
        ]);

        return redirect()->route('dashboard.home')
            ->with('success', 'Peserta dipilih: '.$peserta->nama.' — '.(session('instansi_nama') ?? ''));
    }

    /**
     * AJAX: lookup instansi & peserta by nama (dipakai di modal/home)
     * Mengembalikan 'nama' dan 'nama_lower' sehingga JS bisa melakukan perbandingan case-insensitive
     */
    public function lookupInstansiByNama(Request $request)
    {
        $namaRaw = trim($request->get('nama',''));
        $nama = mb_strtolower($namaRaw);

        if ($nama === '') {
            return response()->json(['ok' => false, 'message' => 'Nama kosong']);
        }

        // cari exact match dulu
        $peserta = Peserta::with('instansi:id,asal_instansi,kota')
            ->whereRaw('LOWER(nama) = ?', [$nama])
            ->first();

        // kalau gak ada exact, gunakan LIKE
        if (!$peserta) {
            $like = '%'.str_replace(' ','%',$nama).'%';
            $peserta = Peserta::with('instansi:id,asal_instansi,kota')
                ->whereRaw('LOWER(nama) LIKE ?', [$like])
                ->orderBy('nama')
                ->first();
        }

        if (!$peserta) {
            return response()->json(['ok' => false, 'message' => 'Peserta tidak ditemukan']);
        }

        // kembalikan nama asli (untuk ditampilkan) dan juga versi lower
        return response()->json([
            'ok' => true,
            'data' => [
                'peserta_id'   => $peserta->id,
                'nama'         => $peserta->nama,               // nama asli (untuk ditampilkan)
                'nama_lower'   => mb_strtolower($peserta->nama), // versi lower untuk perbandingan
                'instansi'     => optional($peserta->instansi)->asal_instansi,
                'kota'         => optional($peserta->instansi)->kota,
            ]
        ]);
    }

    // ======================
    // Logout - hapus session
    // ======================
    public function logout(Request $request)
    {
        $request->session()->forget(['peserta_id','pesertaSurvei_id']);
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('dashboard.home')
            ->with('success', 'Logout berhasil.');
    }

    // ======================
    // GENERIC START TEST HELPER
    // ======================
    /**
     * Membuat/meresume percobaan untuk jenis tes tertentu.
     * $typeLabel: 'Pre-Test' | 'Post-Test' | 'Monev'
     * $typeKey: 'pre'|'post'|'monev' (dipakai untuk mencari Tes)
     * $showRoute: route name untuk menampilkan (contoh 'dashboard.pretest.show')
     * $resultRoute: route name untuk result (contoh 'dashboard.pretest.result')
     */
    protected function startTest(string $typeKey, string $typeLabel, string $showRoute, string $resultRoute)
    {
        $participant = $this->getParticipantKeyAndId();
        $key = $participant['key']; $id = $participant['id'];

        if (!$key || !$id) {
            return back()->with('error', 'Silakan pilih peserta terlebih dahulu.');
        }

        $tes = $this->getTesByType($typeKey);
        if (!$tes) {
            return back()->with('error', "{$typeLabel} tidak ditemukan.");
        }

        // cek sudah selesai
        $done = Percobaan::where('tes_id', $tes->id)
            ->where($key, $id)
            ->whereNotNull('waktu_selesai')
            ->exists();

        if ($done) {
            $perc = Percobaan::where('tes_id', $tes->id)
                ->where($key, $id)
                ->whereNotNull('waktu_selesai')
                ->latest('waktu_selesai')
                ->first();

            return redirect()->route($resultRoute, ['percobaan' => $perc->id])
                ->with('success', "Anda sudah mengerjakan {$typeLabel}. Berikut hasilnya.");
        }

        // cek running
        $running = Percobaan::where('tes_id', $tes->id)
            ->where($key, $id)
            ->whereNull('waktu_selesai')
            ->first();

        if ($running) {
            return redirect()->route($showRoute, ['tes' => $tes->id, 'percobaan' => $running->id]);
        }

        // buat percobaan baru
        $data = [
            'tes_id' => $tes->id,
            'waktu_mulai' => now(),
        ];
        $data[$key] = $id;

        // jika tabel percobaan punya kolom 'tipe', simpan juga (opsional)
        if (Schema::hasColumn('percobaan', 'tipe')) {
            $data['tipe'] = $typeKey;
        }

        $percobaan = Percobaan::create($data);

        return redirect()->route($showRoute, ['tes' => $tes->id, 'percobaan' => $percobaan->id])
            ->with('success', "{$typeLabel} dimulai!");
    }

    // ======================
    // NEW: SUBMIT ATTEMPT (gabungan & penyesuaian)
    // ======================
    /**
     * Endpoint untuk menyimpan jawaban / menyelesaikan percobaan peserta.
     * Menerapkan:
     * - cek max attempts (dari Filament resource constants)
     * - cek window ketersediaan pre/post berdasarkan tes->pelatihan
     * - durasi pengerjaan (contoh: 30 menit)
     * - menyimpan jawaban (jika ada) dan menghitung skor
     */
    public function submitAttempt(Request $request)
    {
        // pastikan peserta terpilih (mengikuti flow DashboardController)
        $participant = $this->getParticipantKeyAndId();
        $key = $participant['key']; $id = $participant['id'];

        if (!$key || !$id) {
            return response()->json(['ok' => false, 'message' => 'Peserta belum dipilih.'], 422);
        }

        // validasi input dasar
        $request->validate([
            'tes_id' => 'required|integer|exists:tes,id',
            // 'jawaban' => 'array', // opsional
            // 'skor' => 'nullable|numeric',
        ]);

        $tes = Tes::findOrFail($request->tes_id);

        // cek berapa kali peserta sudah mengerjakan tes ini
        $attemptCount = Percobaan::where('tes_id', $tes->id)
            ->where($key, $id)
            ->count();

        // ambil konstanta dari Filament resource (safe dipanggil secara fully-qualified)
        $maxAttempts = \App\Filament\Resources\TesPercobaanResource::MAX_ATTEMPTS_PER_TES ?? 1;
        if ($attemptCount >= $maxAttempts) {
            return response()->json(['ok' => false, 'message' => 'Maksimal percobaan tercapai'], 422);
        }

        // cek jadwal ketersediaan (asumsi tes->pelatihan->tanggal_mulai/dates)
        $pel = $tes->pelatihan ?? null;
        if ($pel) {
            $now = now();
            // ambil jenis/tipe tes dari kolom yang tersedia
            $jenis = strtolower($tes->jenis ?? $tes->tipe ?? '');
            if ($jenis === 'pretest' || str_contains($jenis, 'pre')) {
                $from = Carbon::parse($pel->tanggal_mulai)->addDays(\App\Filament\Resources\TesPercobaanResource::PRETEST_AVAILABLE_FROM_OFFSET_DAYS);
                $to   = Carbon::parse($pel->tanggal_mulai)->addDays(\App\Filament\Resources\TesPercobaanResource::PRETEST_AVAILABLE_TO_OFFSET_DAYS)->endOfDay();
            } elseif ($jenis === 'posttest' || str_contains($jenis, 'post')) {
                $from = Carbon::parse($pel->tanggal_selesai)->addDays(\App\Filament\Resources\TesPercobaanResource::POSTTEST_AVAILABLE_FROM_OFFSET_DAYS);
                $to   = Carbon::parse($pel->tanggal_selesai)->addDays(\App\Filament\Resources\TesPercobaanResource::POSTTEST_AVAILABLE_TO_OFFSET_DAYS)->endOfDay();
            } else {
                $from = now()->subYear();
                $to = now()->addYear();
            }

            if (! ($now->between($from, $to)) ) {
                return response()->json(['ok'=>false, 'message'=>'Tes belum tersedia pada jadwal saat ini'], 422);
            }
        }

        // cari percobaan "running" (belum selesai) untuk peserta ini & tes ini
        $running = Percobaan::where('tes_id', $tes->id)
            ->where($key, $id)
            ->whereNull('waktu_selesai')
            ->first();

        // durasi dibaca dari resource constant (menit)
        $durationMinutes = \App\Filament\Resources\TesPercobaanResource::ATTEMPT_DURATION_MINUTES ?? 30;

        if ($running) {
            // jika ada percobaan berjalan, cek apakah masih dalam durasi
            $startAt = $running->waktu_mulai ? Carbon::parse($running->waktu_mulai) : Carbon::now();
            $allowedFinish = $startAt->copy()->addMinutes($durationMinutes);

            if (Carbon::now()->greaterThan($allowedFinish)) {
                // waktu habis -> tandai selesai & hitung skor otomatis (jika jawaban ada)
                $running->waktu_selesai = $running->waktu_selesai ?? now();
                $running->skor = $this->hitungSkor($running);
                $running->lulus = $running->skor >= ($running->tes->passing_score ?? 70);
                $running->save();

                return response()->json(['ok' => false, 'message' => 'Waktu pengerjaan telah habis', 'data' => $running], 422);
            }
        } else {
            // buat percobaan baru (participant key bisa 'peserta_id' atau 'pesertaSurvei_id')
            $data = [
                'tes_id' => $tes->id,
                'waktu_mulai' => now(),
            ];
            $data[$key] = $id;

            if (Schema::hasColumn('percobaan', 'tipe')) {
                $data['tipe'] = strtolower($tes->jenis ?? $tes->tipe ?? ''); // simpan tipe jika kolom ada
            }

            $running = Percobaan::create($data);
        }

        // Simpan jawaban apabila dikirim dalam request (struktur jawaban: ['pertanyaan_id' => opsi_id, ...])
        $jawabanInput = $request->input('jawaban', []);
        if (is_array($jawabanInput) && count($jawabanInput) > 0) {
            foreach ($jawabanInput as $pertanyaanId => $opsiId) {
                JawabanUser::updateOrCreate(
                    [
                        'percobaan_id'  => $running->id,
                        'pertanyaan_id' => $pertanyaanId,
                    ],
                    [
                        'opsi_jawaban_id' => $opsiId,
                    ]
                );
            }
        }

        // Jika client mengirim skor langsung (mis. proctoring), gunakan itu; jika tidak, hitung
        if ($request->filled('skor')) {
            $running->skor = (float) $request->input('skor');
        } else {
            // gunakan helper hitungSkor (mengandalkan jawabanUser & opsiJawaban.apakah_benar)
            $running->skor = $this->hitungSkor($running);
        }

        $running->waktu_selesai = now();
        $running->lulus = $running->skor >= ($running->tes->passing_score ?? 70);
        $running->save();

        return response()->json(['ok' => true, 'message' => 'Tersimpan', 'data' => $running]);
    }

    // ======================
    // PRE-TEST endpoints (reuse existing methods)
    // ======================
    public function pretest(Request $request)
    {
        $participant = $this->getParticipantKeyAndId();
        $key = $participant['key']; $id = $participant['id'];
        if (!$key || !$id) return redirect()->route('dashboard.home')->with('error','Silakan pilih peserta terlebih dahulu.');

        $peserta = ($key === 'peserta_id') ? Peserta::find($id) : null;
        if (!$peserta) return redirect()->route('dashboard.home')->with('error','Silakan pilih peserta terlebih dahulu.');

        $tes = Tes::where('kompetensi_id', $peserta->kompetensi_id)->get();
        return view('dashboard.pages.pre-test.pretest', compact('tes','peserta'));
    }

    public function startPreTest()
    {
        return $this->startTest('pre', 'Pre-Test', 'dashboard.pretest.show', 'dashboard.pretest.result');
    }

    public function pretestStart(Tes $tes)
    {
        return $this->startTest('pre', 'Pre-Test', 'dashboard.pretest.show', 'dashboard.pretest.result');
    }

    public function pretestShow(Tes $tes, Request $request)
    {
        $percobaanId = (int) $request->query('percobaan');
        if (!$percobaanId) {
            return redirect()->route('dashboard.pretest.start', $tes->id)
                ->with('error', 'Pilih peserta terlebih dahulu untuk memulai pre-test.');
        }

        $percobaan = Percobaan::findOrFail($percobaanId);
        if ($percobaan->tes_id !== $tes->id) abort(404);

        if ($percobaan->waktu_selesai) {
            return redirect()->route('dashboard.pretest.result', ['percobaan' => $percobaan->id]);
        }

        $duration = ($tes->durasi_menit ?? 0) * 60;
        $elapsed = $percobaan->waktu_mulai ? now()->diffInSeconds($percobaan->waktu_mulai) : 0;
        $remaining = max($duration - $elapsed, 0);

        if ($remaining <= 0) {
            $percobaan->waktu_selesai = $percobaan->waktu_selesai ?? now();
            $jawaban = $percobaan->jawabanUser;
            $total = $jawaban->count();
            $benar = $jawaban->filter(fn($j) => $j->opsiJawaban && $j->opsiJawaban->apakah_benar)->count();
            $percobaan->skor = $total > 0 ? round(($benar / $total) * 100, 2) : 0;
            $percobaan->lulus = $percobaan->skor >= ($percobaan->tes->passing_score ?? 70);
            $percobaan->save();

            return redirect()->route('dashboard.pretest.result', ['percobaan' => $percobaan->id])
                ->with('error', 'Waktu tes sudah habis.');
        }

        $pertanyaanList = $tes->pertanyaan()->with('opsiJawaban')->get();
        $currentQuestionIndex = (int) $request->query('q', 0);
        $pertanyaan = $pertanyaanList->get($currentQuestionIndex);

        if (!$pertanyaan) {
            return redirect()->route('dashboard.pretest.result', ['percobaan' => $percobaan->id]);
        }

        return view('dashboard.pages.pre-test.pretest-start', compact(
            'tes','pertanyaan','percobaan','pertanyaanList','currentQuestionIndex','remaining'
        ));
    }

    public function pretestSubmit(Request $request, Percobaan $percobaan)
    {
        $data = $request->input('jawaban', []);
        foreach ($data as $pertanyaanId => $opsiId) {
            JawabanUser::updateOrCreate(
                [
                    'percobaan_id'   => $percobaan->id,
                    'pertanyaan_id'  => $pertanyaanId,
                ],
                [
                    'opsi_jawaban_id' => $opsiId,
                ]
            );
        }

        $nextQ = $request->input('next_q');
        $totalQuestions = $percobaan->tes->pertanyaan()->count();

        if ($nextQ !== null && $nextQ < $totalQuestions) {
            return redirect()->route('dashboard.pretest.show', [
                'tes'       => $percobaan->tes_id,
                'percobaan' => $percobaan->id,
                'q'         => $nextQ
            ]);
        }

        $percobaan->waktu_selesai = $percobaan->waktu_selesai ?? now();

        $jawaban = $percobaan->jawabanUser;
        $total = $jawaban->count();
        $benar = $jawaban->filter(fn($j) => $j->opsiJawaban && $j->opsiJawaban->apakah_benar)->count();
        $percobaan->skor = $total > 0 ? round(($benar / $total) * 100, 2) : 0;
        $percobaan->lulus = $percobaan->skor >= ($percobaan->tes->passing_score ?? 70);
        $percobaan->save();

        return redirect()->route('dashboard.pretest.result', ['percobaan' => $percobaan->id]);
    }

    public function pretestResult(Percobaan $percobaan)
    {
        $percobaan->loadMissing(['jawabanUser.opsiJawaban', 'tes', 'peserta']);
        return view('dashboard.pages.pre-test.pretest-result', compact('percobaan'));
    }

    // ======================
    // POST-TEST endpoints
    // ======================
    public function posttest(Request $request)
    {
        $participant = $this->getParticipantKeyAndId();
        $key = $participant['key']; $id = $participant['id'];
        if (!$key || !$id) return redirect()->route('dashboard.home')->with('error','Silakan pilih peserta terlebih dahulu.');

        $peserta = ($key === 'peserta_id') ? Peserta::find($id) : null;
        if (!$peserta) return redirect()->route('dashboard.home')->with('error','Silakan pilih peserta terlebih dahulu.');

        $tes = Tes::where('kompetensi_id', $peserta->kompetensi_id)->get();

        $tesWithStatus = $tes->map(function($t) use ($key, $id) {
            $done = Percobaan::where('tes_id',$t->id)->where($key,$id)->whereNotNull('waktu_selesai')->exists();
            $latest = Percobaan::where('tes_id',$t->id)->where($key,$id)->whereNotNull('waktu_selesai')->latest('waktu_selesai')->first();
            $t->__already_done = $done;
            $t->__last_score = $latest->skor ?? null;
            return $t;
        });

        return view('dashboard.pages.post-test.posttest', ['tes' => $tesWithStatus, 'peserta' => $peserta]);
    }

    public function startPostTest()
    {
        return $this->startTest('post', 'Post-Test', 'dashboard.posttest.show', 'dashboard.posttest.result');
    }

    public function posttestStart(Tes $tes)
    {
        return $this->startTest('post', 'Post-Test', 'dashboard.posttest.show', 'dashboard.posttest.result');
    }

    public function posttestShow(Tes $tes, Request $request)
    {
        // reuse logic dari pretestShow (mereka sama secara proses)
        return $this->pretestShow($tes, $request);
    }

    public function posttestSubmit(Request $request, Percobaan $percobaan)
    {
        return $this->pretestSubmit($request, $percobaan);
    }

    public function posttestResult(Percobaan $percobaan)
    {
        $percobaan->loadMissing(['jawabanUser.opsiJawaban', 'tes', 'peserta']);
        $jawabanCollection = $percobaan->jawabanUser ?? collect();
        $counts = $jawabanCollection->groupBy('opsi_jawaban_id')->map->count()->toArray();

        return view('dashboard.pages.post-test.posttest-result', [
            'percobaan' => $percobaan,
            'chartData' => json_encode($counts),
        ]);
    }

    // ======================
    // MONEV
    // ======================
    public function monev()
    {
        if (Schema::hasColumn('tes','sub_tipe')) {
            $tes = Tes::where('sub_tipe', 'monev')->get();
        } else {
            $tes = Tes::whereRaw('LOWER(judul) LIKE ?', ['%monev%'])->get();
        }
        return view('dashboard.pages.monev.monev', compact('tes'));
    }

    public function monevStart(Tes $tes)
    {
        $participant = $this->getParticipantKeyAndId();
        $key = $participant['key']; $id = $participant['id'];
        if (!$key || !$id) {
            return redirect()->route('dashboard.home')->with('error', 'Silakan isi nama & sekolah terlebih dahulu.');
        }
        return redirect()->route('dashboard.monev.begin', ['tes' => $tes->id]);
    }

    public function monevBegin(Request $request, $tesId)
    {
        $participant = $this->getParticipantKeyAndId();
        $key = $participant['key']; $id = $participant['id'];
        if (!$key || !$id) {
            return redirect()->route('dashboard.home')->with('error', 'Silakan isi nama & sekolah terlebih dahulu.');
        }

        $data = ['tes_id' => $tesId, 'waktu_mulai' => now()];
        $data[$key] = $id;

        // optional: jika column 'tipe' ada pada percobaan, simpan 'monev'
        if (Schema::hasColumn('percobaan', 'tipe')) {
            $data['tipe'] = 'monev';
        }

        $percobaan = Percobaan::create($data);

        return redirect()->route('dashboard.monev.show', [
            'tes'       => $tesId,
            'percobaan' => $percobaan->id,
        ]);
    }

    // ======================
    // SURVEY
    // ======================
    public function survey()
    {
        $participant = $this->getParticipantKeyAndId();
        $key = $participant['key']; $id = $participant['id'];
        if (!$key || !$id) return redirect()->route('dashboard.home')->with('error','Silakan pilih peserta terlebih dahulu.');

        $pid = ($key === 'peserta_id') ? Peserta::findOrFail($id) : null;

        $tes = Tes::where('tipe', 'survei')->where('id', 9)->first();
        if (!$tes) return redirect()->route('dashboard.home')->with('error','Survey tidak ditemukan.');

        return redirect()->route('survey.step', [
            'peserta' => $pid ? $pid->id : $id,
            'order'   => $tes->id
        ]);
    }

    public function surveySubmit(Request $request)
    {
        return redirect()->route('dashboard.survey')->with('success', 'Survey berhasil dikerjakan!');
    }

    // ======================
    // Helper: hitung skor
    // ======================
    protected function hitungSkor(Percobaan $percobaan): int
    {
        $percobaan->loadMissing(['jawabanUser.opsiJawaban']);
        $jawabanCollection = $percobaan->jawabanUser ?? collect();

        $total = $jawabanCollection->count();
        if ($total === 0) {
            return 0;
        }

        $benar = $jawabanCollection->filter(fn($j) => ($j->opsiJawaban->apakah_benar ?? false))->count();
        return (int) round(($benar / $total) * 100);
    }
}
