<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

use App\Models\Tes;
use App\Models\Peserta;
use App\Models\PendaftaranPelatihan;
use App\Models\Percobaan;
use App\Models\JawabanUser;

// Materi
use App\Models\MateriPelatihan;
use App\Models\MateriProgress;

class DashboardController extends Controller
{
    /**
     * fallback tes IDs (kalau tabel tes belum lengkap)
     */
    protected array $defaultTesIds = [
        'pre'   => 1,
        'post'  => 2,
        'monev' => 3,
    ];

    /* =========================================================
     * HELPER: peserta aktif dari session (login)
     * return ['key'=>'peserta_id'|'pesertaSurvei_id', 'id'=>int|null]
     * ========================================================= */
    protected function getParticipantKeyAndId(): array
    {
        if (session()->has('peserta_id')) {
            return ['key' => 'peserta_id', 'id' => (int) session('peserta_id')];
        }

        if (session()->has('pesertaSurvei_id')) {
            return ['key' => 'pesertaSurvei_id', 'id' => (int) session('pesertaSurvei_id')];
        }

        return ['key' => null, 'id' => null];
    }

    /* =========================================================
     * HELPER: pastikan session pelatihan/kompetensi dari pendaftaran
     * dipanggil di home & start test biar selalu konsisten
     * ========================================================= */
    protected function ensureActiveTrainingSession(?Peserta $pesertaAktif = null): void
    {
        if (session('pendaftaran_pelatihan_id') && session('pelatihan_id')) {
            return;
        }

        if ($pesertaAktif) {
            $pendaftaran = PendaftaranPelatihan::with(['pelatihan','kompetensiPelatihan'])
                ->where('peserta_id', $pesertaAktif->id)
                ->latest('tanggal_pendaftaran')
                ->first();

            if ($pendaftaran) {
                session([
                    'pendaftaran_pelatihan_id' => $pendaftaran->id,
                    'pelatihan_id'            => $pendaftaran->pelatihan_id,
                    'kompetensi_id'           => $pendaftaran->kompetensi_id,
                ]);
            }
        }
    }

    /* =========================================================
     * Basis query Tes scoped pelatihan/kompetensi aktif
     * ========================================================= */
    protected function baseTesQuery()
    {
        $pelatihanId  = session('pelatihan_id');
        $kompetensiId = session('kompetensi_id');

        return Tes::query()
            ->when(
                $pelatihanId && Schema::hasColumn('tes', 'pelatihan_id'),
                fn ($q) => $q->where('pelatihan_id', $pelatihanId)
            )
            ->when(
                $kompetensiId && Schema::hasColumn('tes', 'kompetensi_id'),
                fn ($q) => $q->where('kompetensi_id', $kompetensiId)
            );
    }

    /* =========================================================
     * Cari Tes by type (pre/post/monev)
     * ========================================================= */
    protected function getTesByType(string $type): ?Tes
    {
        $type = strtolower($type);
        $base = $this->baseTesQuery();

        $byJudul = function ($cloneBase, array $keywords) {
            foreach ($keywords as $kw) {
                $tes = (clone $cloneBase)
                    ->whereRaw('LOWER(judul) LIKE ?', ["%{$kw}%"])
                    ->first();
                if ($tes) return $tes;
            }
            return null;
        };

        if ($type === 'pre') {
            if (Schema::hasColumn('tes', 'tipe')) {
                $tes = (clone $base)->where('tipe', 'pre-test')->first();
                if ($tes) return $tes;
            }

            $tes = $byJudul($base, ['pre test', 'pre-test', 'pretest']);
            if ($tes) return $tes;

            if (Schema::hasColumn('tes', 'sub_tipe')) {
                $tes = (clone $base)->where('sub_tipe', 'pre-test')->first();
                if ($tes) return $tes;
            }

            return (clone $base)->find($this->defaultTesIds['pre']);
        }

        if ($type === 'post') {
            if (Schema::hasColumn('tes', 'tipe')) {
                $tes = (clone $base)->where('tipe', 'post-test')->first();
                if ($tes) return $tes;
            }

            $tes = $byJudul($base, ['post test', 'post-test', 'posttest']);
            if ($tes) return $tes;

            if (Schema::hasColumn('tes', 'sub_tipe')) {
                $tes = (clone $base)->where('sub_tipe', 'post-test')->first();
                if ($tes) return $tes;
            }

            return (clone $base)->find($this->defaultTesIds['post']);
        }

        if ($type === 'monev') {
            if (Schema::hasColumn('tes', 'sub_tipe')) {
                $tes = (clone $base)->where('sub_tipe', 'monev')->first();
                if ($tes) return $tes;
            }

            if (Schema::hasColumn('tes', 'tipe')) {
                $tes = (clone $base)->where('tipe', 'survey')->first();
                if ($tes) return $tes;
            }

            $tes = $byJudul($base, ['monev', 'survey', 'survei']);
            if ($tes) return $tes;

            return (clone $base)->find($this->defaultTesIds['monev']);
        }

        return null;
    }

    /* =========================================================
     * Base Percobaan query (scoped + non legacy)
     * ========================================================= */
    protected function basePercobaanQuery()
    {
        ['key' => $key, 'id' => $id] = $this->getParticipantKeyAndId();
        $pelatihanId = session('pelatihan_id');

        $q = Percobaan::query();

        if ($key && $id) {
            $q->where($key, $id);
        }

        if ($pelatihanId && Schema::hasColumn('percobaan', 'pelatihan_id')) {
            $q->where('pelatihan_id', $pelatihanId);
        }

        if (Schema::hasColumn('percobaan', 'is_legacy')) {
            $q->where('is_legacy', false);
        }

        return $q;
    }

    /* =========================================================
     * Statistik pre/post/monev
     * ========================================================= */
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

        if (!$key || !$id) return $stats;

        $preTes   = $this->getTesByType('pre');
        $postTes  = $this->getTesByType('post');
        $monevTes = $this->getTesByType('monev');

        $basePerc = $this->basePercobaanQuery();

        if ($preTes) {
            $stats['preTestAttempts'] = (clone $basePerc)->where('tes_id', $preTes->id)->count();
            $pre = (clone $basePerc)->where('tes_id', $preTes->id)
                ->whereNotNull('waktu_selesai')->latest('waktu_selesai')->first();
            $stats['preTestScore'] = $pre?->skor;
            $stats['preTestDone']  = (bool) $pre;
        }

        if ($postTes) {
            $stats['postTestAttempts'] = (clone $basePerc)->where('tes_id', $postTes->id)->count();
            $post = (clone $basePerc)->where('tes_id', $postTes->id)
                ->whereNotNull('waktu_selesai')->latest('waktu_selesai')->first();
            $stats['postTestScore'] = $post?->skor;
            $stats['postTestDone']  = (bool) $post;
        }

        if ($monevTes) {
            $stats['monevAttempts'] = (clone $basePerc)->where('tes_id', $monevTes->id)->count();
            $mon = (clone $basePerc)->where('tes_id', $monevTes->id)
                ->whereNotNull('waktu_selesai')->latest('waktu_selesai')->first();
            $stats['monevScore'] = $mon?->skor;
            $stats['monevDone']  = (bool) $mon;
        }

        return $stats;
    }

    /* =========================================================
     * HOME DASHBOARD
     * ========================================================= */
    public function home(): View
    {
        ['key' => $key, 'id' => $id] = $this->getParticipantKeyAndId();

        $pesertaAktif = ($key === 'peserta_id' && $id)
            ? Peserta::with('instansi:id,asal_instansi,kota')->find($id)
            : null;

        $this->ensureActiveTrainingSession($pesertaAktif);

        $pelatihanId = session('pelatihan_id');

        // peserta dropdown (yang diterima di pelatihan)
        $peserta = Peserta::query()
            ->when($pelatihanId, function ($q) use ($pelatihanId) {
                $q->whereHas('pendaftaranPelatihan', function ($qq) use ($pelatihanId) {
                    $qq->where('pelatihan_id', $pelatihanId)
                       ->where('status_pendaftaran', 'Diterima');
                });
            })
            ->with('instansi:id,asal_instansi,kota')
            ->orderBy('nama')
            ->get();

        // ===== Materi =====
        $materiList = collect();
        $materiDoneCount = 0;
        $totalMateri = 0;

        if ($pelatihanId && class_exists(MateriPelatihan::class)) {
            $materiList = MateriPelatihan::where('pelatihan_id', $pelatihanId)
                ->orderBy('urutan')
                ->get(['id','pelatihan_id','slug','judul','deskripsi','tipe','estimasi_menit','urutan','created_at']);

            $totalMateri = $materiList->count();

            $pendaftaranId = session('pendaftaran_pelatihan_id');

            $doneIds = [];
            if ($pendaftaranId && class_exists(MateriProgress::class)) {
                $doneIds = MateriProgress::where('pendaftaran_pelatihan_id', $pendaftaranId)
                    ->where('is_completed', true)
                    ->pluck('materi_id')
                    ->toArray();
            }

            $materiDoneCount = count($doneIds);

            $materiList = $materiList->map(function ($m) use ($doneIds) {
                $m->is_done = in_array($m->id, $doneIds);
                return $m;
            });
        }

        // Optional tes object untuk blade
        $preTes   = $this->getTesByType('pre');
        $postTes  = $this->getTesByType('post');
        $monevTes = $this->getTesByType('monev');

        $stats = $this->getTestStats();

        return view('dashboard.pages.home', array_merge([
            'peserta'          => $peserta,
            'pesertaAktif'     => $pesertaAktif,
            'materiList'       => $materiList,
            'materiDoneCount'  => $materiDoneCount,
            'totalMateri'      => $totalMateri,
            'preTes'           => $preTes,
            'postTes'          => $postTes,
            'monevTes'         => $monevTes,
        ], $stats));
    }

    /* =========================================================
     * SET PESERTA (dropdown / input nama) + redirect_to
     * ========================================================= */
    public function setPeserta(Request $request)
    {
        $validated = $request->validate([
            'peserta_id'  => ['nullable', 'exists:peserta,id'],
            'nama'        => ['nullable', 'string', 'max:150'],
            'redirect_to' => ['nullable', 'in:materi,pretest,posttest,monev'],
        ]);

        // 1) pilih via peserta_id (dropdown)
        if ($request->filled('peserta_id')) {
            $peserta = Peserta::with('instansi:id,asal_instansi,kota')->findOrFail($request->peserta_id);

            session([
                'peserta_id'    => $peserta->id,
                'peserta_nama'  => $peserta->nama,
                'instansi_id'   => optional($peserta->instansi)->id,
                'instansi_nama' => optional($peserta->instansi)->asal_instansi,
                'instansi_kota' => optional($peserta->instansi)->kota,
            ]);

            $this->ensureActiveTrainingSession($peserta);

            $redirectTo = $request->input('redirect_to');

            return match ($redirectTo) {
                'materi'   => redirect()->route('dashboard.materi.index'),
                'pretest'  => redirect()->route('dashboard.pretest.index'),
                'posttest' => redirect()->route('dashboard.posttest.index'),
                'monev'    => redirect()->route('dashboard.monev.index'),
                default    => redirect()->route('dashboard.home'),
            }->with('success', 'Peserta dipilih: ' . $peserta->nama);
        }

        // 2) fallback via input nama
        if (!$request->filled('nama')) {
            return back()->withInput()->with('error', 'Nama atau Peserta wajib diisi.');
        }

        $nama = mb_strtolower(trim($validated['nama']));

        $matches = Peserta::with('instansi:id,asal_instansi,kota')
            ->whereRaw('LOWER(nama) = ?', [$nama])
            ->get();

        if ($matches->isEmpty()) {
            $like = '%' . str_replace(' ', '%', $nama) . '%';
            $matches = Peserta::with('instansi:id,asal_instansi,kota')
                ->whereRaw('LOWER(nama) LIKE ?', [$like])
                ->orderBy('nama')
                ->get();
        }

        if ($matches->isEmpty()) {
            return back()->withInput()->with('error', 'Peserta tidak ditemukan.');
        }

        $peserta = $matches->first();

        session([
            'peserta_id'    => $peserta->id,
            'peserta_nama'  => $peserta->nama,
            'instansi_id'   => optional($peserta->instansi)->id,
            'instansi_nama' => optional($peserta->instansi)->asal_instansi,
            'instansi_kota' => optional($peserta->instansi)->kota,
        ]);

        $this->ensureActiveTrainingSession($peserta);

        $redirectTo = $request->input('redirect_to');

        return match ($redirectTo) {
            'materi'   => redirect()->route('dashboard.materi.index'),
            'pretest'  => redirect()->route('dashboard.pretest.index'),
            'posttest' => redirect()->route('dashboard.posttest.index'),
            'monev'    => redirect()->route('dashboard.monev.index'),
            default    => redirect()->route('dashboard.home'),
        }->with('success', 'Peserta dipilih: ' . $peserta->nama);
    }

    public function lookupInstansiByNama(Request $request)
    {
        $nama = mb_strtolower(trim($request->get('nama', '')));
        if ($nama === '') {
            return response()->json(['ok' => false, 'message' => 'Nama kosong']);
        }

        $peserta = Peserta::with('instansi:id,asal_instansi,kota')
            ->whereRaw('LOWER(nama) = ?', [$nama])
            ->first();

        if (!$peserta) {
            $like = '%' . str_replace(' ', '%', $nama) . '%';
            $peserta = Peserta::with('instansi:id,asal_instansi,kota')
                ->whereRaw('LOWER(nama) LIKE ?', [$like])
                ->orderBy('nama')
                ->first();
        }

        if (!$peserta) {
            return response()->json(['ok' => false, 'message' => 'Peserta tidak ditemukan']);
        }

        return response()->json([
            'ok' => true,
            'data' => [
                'peserta_id' => $peserta->id,
                'nama'       => $peserta->nama,
                'instansi'   => optional($peserta->instansi)->asal_instansi,
                'kota'       => optional($peserta->instansi)->kota,
            ],
        ]);
    }

    /* =========================================================
     * LOGOUT
     * ========================================================= */
    public function logout(Request $request)
    {
        $request->session()->forget([
            'peserta_id', 'pesertaSurvei_id', 'peserta_nama',
            'pendaftaran_pelatihan_id', 'pelatihan_id', 'kompetensi_id',
            'instansi_id', 'instansi_nama', 'instansi_kota',
        ]);

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('dashboard.home')->with('success', 'Logout berhasil.');
    }

    /* =========================================================
     * START TEST GENERIC
     * ========================================================= */
    protected function startTest(string $typeKey, string $typeLabel, string $showRoute, string $resultRoute)
    {
        ['key' => $key, 'id' => $id] = $this->getParticipantKeyAndId();
        if (!$key || !$id) {
            return back()->with('error', 'Silakan login terlebih dahulu.');
        }

        $pesertaAktif = ($key === 'peserta_id') ? Peserta::find($id) : null;
        $this->ensureActiveTrainingSession($pesertaAktif);

        $tes = $this->getTesByType($typeKey);
        if (!$tes) {
            return back()->with('error', "{$typeLabel} tidak ditemukan untuk pelatihan ini.");
        }

        $basePerc = $this->basePercobaanQuery()->where('tes_id', $tes->id);

        // sudah selesai -> tidak boleh ulang, arahkan hasil
        $done = (clone $basePerc)->whereNotNull('waktu_selesai')->exists();
        if ($done) {
            $perc = (clone $basePerc)->whereNotNull('waktu_selesai')
                ->latest('waktu_selesai')->first();

            return redirect()->route($resultRoute, ['percobaan' => $perc->id])
                ->with('success', "Anda sudah mengerjakan {$typeLabel}. Berikut hasilnya.");
        }

        // masih running -> lanjutkan
        $running = (clone $basePerc)->whereNull('waktu_selesai')->first();
        if ($running) {
            return redirect()->route($showRoute, [
                'tes'       => $tes->id,
                'percobaan' => $running->id,
            ]);
        }

        // buat baru
        $data = [
            'tes_id'      => $tes->id,
            'waktu_mulai' => now(),
        ];

        if (Schema::hasColumn('percobaan', 'pelatihan_id')) {
            $data['pelatihan_id'] = session('pelatihan_id');
        }

        $data[$key] = $id;

        if (Schema::hasColumn('percobaan', 'tipe')) {
            $data['tipe'] = match ($typeKey) {
                'pre'   => 'pre-test',
                'post'  => 'post-test',
                default => 'survey',
            };
        }

        $percobaan = Percobaan::create($data);

        return redirect()->route($showRoute, [
            'tes'       => $tes->id,
            'percobaan' => $percobaan->id,
        ])->with('success', "{$typeLabel} dimulai!");
    }

    /* =========================================================
     * PRETEST
     * ========================================================= */
    public function pretest()
    {
        ['key' => $key, 'id' => $id] = $this->getParticipantKeyAndId();
        if (!$key || !$id) {
            return redirect()->route('dashboard.home')
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        $tes = $this->baseTesQuery()
            ->when(Schema::hasColumn('tes', 'tipe'), fn($q) => $q->where('tipe','pre-test'))
            ->get();

        return view('dashboard.pages.pre-test.pretest', [
            'tes'     => $tes,
            'peserta' => ($key === 'peserta_id') ? Peserta::find($id) : null,
        ]);
    }

    public function startPreTest()
    {
        return $this->startTest('pre', 'Pre-Test', 'dashboard.pretest.show', 'dashboard.pretest.result');
    }

    public function pretestShow(Tes $tes, Request $request)
    {
        return $this->handleTesShow($tes, $request, 'pre-test', 'dashboard.pretest.result');
    }

    public function pretestSubmit(Request $request, Percobaan $percobaan)
    {
        return $this->processTesSubmit($request, $percobaan, 'dashboard.pretest.show', 'dashboard.pretest.result');
    }

    public function pretestResult(Percobaan $percobaan)
    {
        $percobaan->loadMissing(['jawabanUser.opsiJawaban', 'tes', 'peserta']);

        return view('dashboard.pages.tes.result', [
            'percobaan' => $percobaan,
            'mode'      => 'pre-test',
        ]);
    }

    /* =========================================================
     * POSTTEST
     * ========================================================= */
    public function posttest()
    {
        ['key' => $key, 'id' => $id] = $this->getParticipantKeyAndId();
        if (!$key || !$id) {
            return redirect()->route('dashboard.home')
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        $tes = $this->baseTesQuery()
            ->when(Schema::hasColumn('tes', 'tipe'), fn($q) => $q->where('tipe','post-test'))
            ->get();

        $basePerc = $this->basePercobaanQuery();

        $tesWithStatus = $tes->map(function ($t) use ($basePerc) {
            $latest = (clone $basePerc)->where('tes_id', $t->id)
                ->whereNotNull('waktu_selesai')
                ->latest('waktu_selesai')
                ->first();

            $t->__already_done = (bool) $latest;
            $t->__last_score   = $latest?->skor;

            return $t;
        });

        return view('dashboard.pages.post-test.posttest', [
            'tes'     => $tesWithStatus,
            'peserta' => ($key === 'peserta_id') ? Peserta::find($id) : null,
        ]);
    }

    public function startPostTest()
    {
        return $this->startTest('post', 'Post-Test', 'dashboard.posttest.show', 'dashboard.posttest.result');
    }

    public function posttestShow(Tes $tes, Request $request)
    {
        return $this->handleTesShow($tes, $request, 'post-test', 'dashboard.posttest.result');
    }

    public function posttestSubmit(Request $request, Percobaan $percobaan)
    {
        return $this->processTesSubmit($request, $percobaan, 'dashboard.posttest.show', 'dashboard.posttest.result');
    }

    public function posttestResult(Percobaan $percobaan)
    {
        $percobaan->loadMissing(['jawabanUser.opsiJawaban', 'tes', 'peserta', 'pesertaSurvei']);

        $preScore = null;
        $improvementPoints = null;
        $improvementPercent = null;

        $preAttempt = (clone $this->basePercobaanQuery())
            ->whereNotNull('waktu_selesai')
            ->where(function ($q) {
                $q->where('tipe','pre-test')
                  ->orWhere('tipe','pre')
                  ->orWhereNull('tipe');
            })
            ->latest('waktu_selesai')
            ->first();

        if ($preAttempt && $preAttempt->skor !== null && $percobaan->skor !== null) {
            $preScore  = (float) $preAttempt->skor;
            $postScore = (float) $percobaan->skor;

            $improvementPoints  = $postScore - $preScore;
            $improvementPercent = $preScore > 0
                ? round((($postScore - $preScore) / $preScore) * 100, 2)
                : null;
        }

        return view('dashboard.pages.tes.result', [
            'percobaan'          => $percobaan,
            'mode'               => 'post-test',
            'preScore'           => $preScore,
            'improvementPoints'  => $improvementPoints,
            'improvementPercent' => $improvementPercent,
        ]);
    }

    /* =========================================================
     * HANDLE TES SHOW (PRE/POST/MONEV)
     * ========================================================= */
    protected function handleTesShow(Tes $tes, Request $request, string $mode, string $resultRouteName)
    {
        $percobaanId = (int) $request->query('percobaan');

        if (!$percobaanId) {
            $startRoute = $mode === 'post-test'
                ? 'dashboard.posttest.start'
                : ($mode === 'survey'
                    ? 'dashboard.monev.begin'
                    : 'dashboard.pretest.start'
                );

            // PATCH C: startRoute tanpa param tes id
            return redirect()->route($startRoute)
                ->with('error', 'Silakan mulai tes terlebih dahulu.');
        }

        $percobaan = Percobaan::findOrFail($percobaanId);
        if ($percobaan->tes_id !== $tes->id) abort(404);

        if ($percobaan->waktu_selesai) {
            return redirect()->route($resultRouteName, ['percobaan' => $percobaan->id]);
        }

        $duration  = (int) ($tes->durasi_menit ?? 0) * 60;
        $startAt   = $percobaan->waktu_mulai ? Carbon::parse($percobaan->waktu_mulai) : now();
        $elapsed   = now()->diffInSeconds($startAt);
        $remaining = max($duration - $elapsed, 0);

        if ($duration > 0 && $remaining <= 0) {
            $percobaan->waktu_selesai = now();
            $percobaan->skor  = $this->hitungSkor($percobaan);
            $percobaan->lulus = $percobaan->skor >= ($percobaan->tes->passing_score ?? 70);
            $percobaan->save();

            return redirect()->route($resultRouteName, ['percobaan' => $percobaan->id])
                ->with('error', 'Waktu tes sudah habis.');
        }

        $pertanyaanList = $tes->pertanyaan()->with('opsiJawaban')->get();
        $currentQuestionIndex = (int) $request->query('q', 0);
        $pertanyaan = $pertanyaanList->get($currentQuestionIndex);

        if (!$pertanyaan) {
            return redirect()->route($resultRouteName, ['percobaan' => $percobaan->id]);
        }

        return view('dashboard.pages.tes.do', [
            'tes'                  => $tes,
            'pertanyaan'           => $pertanyaan,
            'percobaan'            => $percobaan,
            'pertanyaanList'       => $pertanyaanList,
            'currentQuestionIndex' => $currentQuestionIndex,
            'remaining'            => $remaining,
            'mode'                 => $mode,
        ]);
    }

    /* =========================================================
     * SUBMIT TES (PRE/POST/MONEV)
     * ========================================================= */
    protected function processTesSubmit(Request $request, Percobaan $percobaan, string $showRouteName, string $resultRouteName)
    {
        $data = $request->input('jawaban', []);
        foreach ($data as $pertanyaanId => $opsiId) {
            JawabanUser::updateOrCreate(
                ['percobaan_id' => $percobaan->id, 'pertanyaan_id' => $pertanyaanId],
                ['opsi_jawaban_id' => $opsiId]
            );
        }

        $nextQ = $request->input('next_q');
        $totalQuestions = $percobaan->tes->pertanyaan()->count();

        if ($nextQ !== null && $nextQ < $totalQuestions) {
            return redirect()->route($showRouteName, [
                'tes'       => $percobaan->tes_id,
                'percobaan' => $percobaan->id,
                'q'         => $nextQ,
            ]);
        }

        $percobaan->waktu_selesai = now();
        $percobaan->skor  = $this->hitungSkor($percobaan);
        $percobaan->lulus = $percobaan->skor >= ($percobaan->tes->passing_score ?? 70);
        $percobaan->save();

        if ($request->boolean('cheat_flag')) {
            session()->put('cheat_' . $percobaan->id, true);
        }

        return redirect()->route($resultRouteName, ['percobaan' => $percobaan->id]);
    }

    /* =========================================================
     * MONEV INDEX/START/BEGIN (Survey)
     * ========================================================= */
    public function monev()
    {
        $base = $this->baseTesQuery();

        $tes = (clone $base)
            ->when(
                Schema::hasColumn('tes', 'sub_tipe'),
                fn($q) => $q->where('sub_tipe', 'monev'),
                fn($q) => $q->whereRaw('LOWER(judul) LIKE ?', ['%monev%'])
            )
            ->get();

        return view('dashboard.pages.monev.monev', compact('tes'));
    }

    public function monevStart(Tes $tes)
    {
        ['key' => $key, 'id' => $id] = $this->getParticipantKeyAndId();
        if (!$key || !$id) {
            return redirect()->route('dashboard.home')
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        return redirect()->route('dashboard.monev.begin', ['tes' => $tes->id]);
    }

    // PATCH D: monevBegin pola done/running/baru
    public function monevBegin(Request $request, $tesId)
    {
        ['key' => $key, 'id' => $id] = $this->getParticipantKeyAndId();
        if (!$key || !$id) {
            return redirect()->route('dashboard.home')
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        $tes = Tes::findOrFail($tesId);

        $basePerc = $this->basePercobaanQuery()->where('tes_id', $tes->id);

        $done = (clone $basePerc)->whereNotNull('waktu_selesai')->latest('waktu_selesai')->first();
        if ($done) {
            return redirect()->route('dashboard.monev.result', ['percobaan' => $done->id])
                ->with('success', 'Anda sudah mengisi Monev. Berikut hasilnya.');
        }

        $running = (clone $basePerc)->whereNull('waktu_selesai')->first();
        if ($running) {
            return redirect()->route('dashboard.monev.show', [
                'tes'       => $tes->id,
                'percobaan' => $running->id,
            ]);
        }

        $data = [
            'tes_id'      => $tes->id,
            'waktu_mulai' => now(),
            $key          => $id,
        ];

        if (Schema::hasColumn('percobaan', 'pelatihan_id')) {
            $data['pelatihan_id'] = session('pelatihan_id');
        }

        if (Schema::hasColumn('percobaan', 'tipe')) {
            $data['tipe'] = 'survey';
        }

        $percobaan = Percobaan::create($data);

        return redirect()->route('dashboard.monev.show', [
            'tes'       => $tes->id,
            'percobaan' => $percobaan->id,
        ])->with('success', 'Monev dimulai.');
    }

    // PATCH D: tambahan show/submit/result monev supaya route nyambung
    public function monevShow(Tes $tes, Request $request)
    {
        return $this->handleTesShow($tes, $request, 'survey', 'dashboard.monev.result');
    }

    public function monevSubmit(Request $request, Percobaan $percobaan)
    {
        return $this->processTesSubmit($request, $percobaan, 'dashboard.monev.show', 'dashboard.monev.result');
    }

    public function monevResult(Percobaan $percobaan)
    {
        $percobaan->loadMissing(['jawabanUser.opsiJawaban', 'tes', 'peserta', 'pesertaSurvei']);

        return view('dashboard.pages.tes.result', [
            'percobaan' => $percobaan,
            'mode'      => 'survey',
        ]);
    }

    /* =========================================================
     * MATERI INDEX
     * ========================================================= */
    public function materi()
    {
        $pelatihanId = session('pelatihan_id');
        if (!$pelatihanId) {
            return redirect()->route('dashboard.home')
                ->with('error', 'Pilih peserta / pelatihan terlebih dahulu.');
        }

        $materis = MateriPelatihan::where('pelatihan_id', $pelatihanId)
            ->orderBy('urutan')
            ->get();

        $pendaftaranId = session('pendaftaran_pelatihan_id');

        $doneIds = [];
        if ($pendaftaranId && class_exists(MateriProgress::class)) {
            $doneIds = MateriProgress::where('pendaftaran_pelatihan_id', $pendaftaranId)
                ->where('is_completed', true)
                ->pluck('materi_id')
                ->toArray();
        }

        $materis = $materis->map(function ($m) use ($doneIds) {
            $m->is_done = in_array($m->id, $doneIds);
            return $m;
        });

        return view('dashboard.pages.materi.materi-index', [
            'materiList' => $materis,
        ]);
    }

    /* =========================================================
     * MATERI SHOW
     * ========================================================= */
    public function materiShow($materi)
    {
        $m = $materi instanceof MateriPelatihan
            ? $materi
            : MateriPelatihan::where('slug', $materi)
                ->orWhere('id', $materi)
                ->firstOrFail();

        $pendaftaranId = session('pendaftaran_pelatihan_id');
        $progress = null;

        if ($pendaftaranId && class_exists(MateriProgress::class)) {
            $progress = MateriProgress::where('pendaftaran_pelatihan_id', $pendaftaranId)
                ->where('materi_id', $m->id)
                ->first();
        }

        return view('dashboard.pages.materi.materi-show', [
            'materi'         => $m,
            'materiProgress' => $progress,
            'relatedMateris' => MateriPelatihan::where('pelatihan_id', $m->pelatihan_id)
                ->orderBy('urutan')
                ->get(),
        ]);
    }

    /* =========================================================
     * MATERI COMPLETE
     * ========================================================= */
    public function materiComplete(Request $request, $materi)
    {
        $materiModel = $materi instanceof MateriPelatihan
            ? $materi
            : MateriPelatihan::findOrFail($materi);

        ['key' => $key, 'id' => $id] = $this->getParticipantKeyAndId();
        if (!$key || !$id) {
            return redirect()->route('dashboard.home')
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        $pendaftaranId = session('pendaftaran_pelatihan_id');

        if (!$pendaftaranId) {
            $pendaftaran = PendaftaranPelatihan::where('peserta_id', $id)
                ->where('pelatihan_id', $materiModel->pelatihan_id)
                ->latest('tanggal_pendaftaran')
                ->first();

            if ($pendaftaran) {
                $pendaftaranId = $pendaftaran->id;
                session(['pendaftaran_pelatihan_id' => $pendaftaranId]);
            }
        }

        if (!$pendaftaranId) {
            return redirect()->route('dashboard.materi.show', $materiModel->slug ?? $materiModel->id)
                ->with('error', 'Tidak menemukan pendaftaran peserta untuk pelatihan ini.');
        }

        MateriProgress::updateOrCreate(
            [
                'pendaftaran_pelatihan_id' => $pendaftaranId,
                'materi_id'                => $materiModel->id,
            ],
            [
                'is_completed' => true,
                'completed_at' => now(),
            ]
        );

        return redirect()->route('dashboard.materi.show', $materiModel->slug ?? $materiModel->id)
            ->with('success', 'Materi ditandai selesai. Terima kasih.');
    }

    /* =========================================================
     * HITUNG SKOR
     * ========================================================= */
    protected function hitungSkor(Percobaan $percobaan): int
    {
        $percobaan->loadMissing(['jawabanUser.opsiJawaban']);
        $jawabanCollection = $percobaan->jawabanUser ?? collect();

        $total = $jawabanCollection->count();
        if ($total === 0) return 0;

        $benar = $jawabanCollection
            ->filter(fn ($j) => ($j->opsiJawaban->apakah_benar ?? false))
            ->count();

        return (int) round(($benar / $total) * 100);
    }

    /* =========================================================
     * PROFILE
     * ========================================================= */
    public function profile(): View
    {
        return view('dashboard.pages.profile');
    }
}
