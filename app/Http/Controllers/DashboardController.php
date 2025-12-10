<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

use App\Models\Tes;
use App\Models\Peserta;
use App\Models\PendaftaranPelatihan;
use App\Models\Percobaan;
use App\Models\JawabanUser;

use App\Models\MateriPelatihan;
use App\Models\MateriProgress;

// legacy ajax instansi (kalau modelnya ada di project kamu)
use App\Models\Instansi;

class DashboardController extends Controller
{
    /**
     * fallback id tes kalau memang butuh,
     * tapi tetap akan dipaksa dalam scope pelatihan/kompetensi aktif.
     */
    protected array $defaultTesIds = [
        'pre'   => 1,
        'post'  => 2,
        'monev' => 3,
    ];

    public function __construct()
    {
        // sama dengan middleware alias di routes kamu
        //$this->middleware(['web', 'assessment', 'training.session']);
    }

    /* =======================
     * HELPER: tipe map
     * ======================= */
    protected function mapTypeKeyToTipe(string $typeKey): string
    {
        return match (strtolower($typeKey)) {
            'pre'   => 'pre-test',
            'post'  => 'post-test',
            'monev' => 'survei',
            default => strtolower($typeKey),
        };
    }

    /* =======================
     * HELPER: peserta session
     * ======================= */
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

    protected function requireLogin(): array|RedirectResponse
    {
        ['key' => $key, 'id' => $id] = $this->getParticipantKeyAndId();

        if (!$key || !$id) {
            return redirect()->route('assessment.login')
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        return ['key' => $key, 'id' => $id];
    }

    /* =======================
     * HELPER: set training session (legacy compat)
     * Sekarang sebenarnya sudah dijamin middleware training.session,
     * tapi ini dipertahankan biar gak ada kode lain yang rusak.
     * ======================= */
    protected function ensureActiveTrainingSession(?Peserta $pesertaAktif = null): void
    {
        if (!$pesertaAktif) return;

        // ✅ cuma nambah ini (sesuai permintaanmu)
        session([
            'kompetensi_id' => $pesertaAktif->kompetensi_id,
            'pelatihan_id'  => $pesertaAktif->pelatihan_id,
        ]);

        if (
            session('pendaftaran_pelatihan_id') &&
            session('pelatihan_id') &&
            session('kompetensi_id')
        ) {
            return;
        }

        $pendaftaran = PendaftaranPelatihan::with(['pelatihan', 'kompetensiPelatihan'])
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

    /* =======================
     * base tes query scope
     * ======================= */
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

    protected function getTesByType(string $typeKey): ?Tes
    {
        $base = $this->baseTesQuery();
        $tipe = $this->mapTypeKeyToTipe($typeKey);

        $tes = (clone $base)->where('tipe', $tipe)->first();
        if ($tes) return $tes;

        // fallback id default TAPI masih dalam scope
        $fallbackId = $this->defaultTesIds[$typeKey] ?? null;
        if ($fallbackId) {
            return (clone $base)->where('id', $fallbackId)->first();
        }

        return null;
    }

    /* =======================
     * base percobaan query scope
     * ======================= */
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
     * HOME
     * ========================================================= */
    public function home(): View|RedirectResponse
    {
        $login = $this->requireLogin();
        if ($login instanceof RedirectResponse) return $login;

        ['key' => $key, 'id' => $id] = $login;

        $pesertaAktif = ($key === 'peserta_id')
            ? Peserta::with('instansi:id,asal_instansi,kota')->find($id)
            : null;

        // legacy compat
        $this->ensureActiveTrainingSession($pesertaAktif);

        $pelatihanId = session('pelatihan_id');

        $materiList = collect();
        $materiDoneCount = 0;
        $totalMateri = 0;

        if ($pelatihanId) {
            $materiList = MateriPelatihan::where('pelatihan_id', $pelatihanId)
                ->orderBy('urutan')
                ->get([
                    'id','pelatihan_id','judul','deskripsi',
                    'tipe','estimasi_menit','urutan','created_at'
                ]);

            $totalMateri = $materiList->count();
            $pendaftaranId = session('pendaftaran_pelatihan_id');

            $doneIds = $pendaftaranId
                ? MateriProgress::where('pendaftaran_pelatihan_id', $pendaftaranId)
                    ->where('is_completed', true)
                    ->pluck('materi_id')
                    ->toArray()
                : [];

            $materiDoneCount = count($doneIds);

            $materiList = $materiList->map(function ($m) use ($doneIds) {
                $m->is_done = in_array($m->id, $doneIds);
                return $m;
            });
        }

        $preTes   = $this->getTesByType('pre');
        $postTes  = $this->getTesByType('post');
        $monevTes = $this->getTesByType('monev');

        $stats = $this->getTestStats();

        return view('dashboard.pages.home', array_merge([
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
     * STATS
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

        foreach ([
            'pre' => $preTes,
            'post' => $postTes,
            'monev' => $monevTes
        ] as $k => $t) {
            if (!$t) continue;

            $done = (clone $basePerc)->where('tes_id', $t->id)
                ->whereNotNull('waktu_selesai')
                ->latest('waktu_selesai')
                ->first();

            $stats[$k.'TestAttempts'] = (clone $basePerc)->where('tes_id', $t->id)->count();
            $stats[$k.'TestScore']    = $done?->skor;
            $stats[$k.'TestDone']     = (bool) $done;
        }

        return $stats;
    }

    /* =========================================================
     * GENERIC START HANDLER
     * ========================================================= */
    protected function startByTes(Tes $tes, string $mode, string $indexRoute, string $showRoute, string $resultRoute)
    {
        $login = $this->requireLogin();
        if ($login instanceof RedirectResponse) return $login;
        ['key' => $key, 'id' => $id] = $login;

        $pesertaAktif = ($key === 'peserta_id') ? Peserta::find($id) : null;
        $this->ensureActiveTrainingSession($pesertaAktif);
        $tesTipe = ($mode === 'survey') ? 'survei' : $mode;
        // ✅ tes harus dalam scope + tipe benar
        $allowed = $this->baseTesQuery()
            ->where('id', $tes->id)
            ->where('tipe', $mode)
            ->exists();

        if (!$allowed) {
            return redirect()->route($indexRoute)
                ->with('error', 'Tes ini tidak tersedia untuk pelatihan Anda.');
        }

        $basePerc = $this->basePercobaanQuery()->where('tes_id', $tes->id);

        $done = (clone $basePerc)->whereNotNull('waktu_selesai')
            ->latest('waktu_selesai')->first();
        if ($done) {
            return redirect()->route($resultRoute, ['percobaan' => $done->id])
                ->with('success', 'Anda sudah mengerjakan tes ini. Berikut hasilnya.');
        }

        $running = (clone $basePerc)->whereNull('waktu_selesai')->first();
        if ($running) {
            return redirect()->route($showRoute, [
                'tes' => $tes->id,
                'percobaan' => $running->id
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
            $data['tipe'] = $mode;
        }

        $percobaan = Percobaan::create($data);

        return redirect()->route($showRoute, [
            'tes' => $tes->id,
            'percobaan' => $percobaan->id
        ])->with('success', 'Tes dimulai!');
    }

    protected function baseMonevTesQuery()
{
    $pelatihanId = session('pelatihan_id');

    return Tes::query()
        ->when(
            $pelatihanId && Schema::hasColumn('tes', 'pelatihan_id'),
            fn ($q) => $q->where('pelatihan_id', $pelatihanId)
        );
}
    /* =========================================================
     * PRETEST
     * ========================================================= */
   public function pretest()
    {
        $login = $this->requireLogin();
        if ($login instanceof RedirectResponse) return $login;
        ['key' => $key, 'id' => $id] = $login;

        $pesertaAktif = ($key === 'peserta_id') ? Peserta::find($id) : null;
        $this->ensureActiveTrainingSession($pesertaAktif);

        // Ambil semua tes pre-test sesuai filter base query kamu
        $tes = (clone $this->baseTesQuery())
            ->where('tipe', 'pre-test')
            ->get();

        $basePerc = $this->basePercobaanQuery();

        $tesWithStatus = $tes->map(function ($t) use ($basePerc) {
            $latestDone = (clone $basePerc)
                ->where('tes_id', $t->id)
                ->whereNotNull('waktu_selesai')
                ->latest('waktu_selesai')
                ->first();

            $running = (clone $basePerc)
                ->where('tes_id', $t->id)
                ->whereNull('waktu_selesai')
                ->latest('waktu_mulai')
                ->first();

            $t->__already_done = (bool) $latestDone;
            $t->__last_score   = $latestDone?->skor;
            $t->__running_id   = $running?->id;
            $t->__done_id      = $latestDone?->id;

            $passing = $t->passing_score ?? 70;
            $t->__above_avg = $latestDone
                ? ((int) $latestDone->skor >= (int) $passing)
                : false;

            return $t;
        });

        return view('dashboard.pages.pre-test.pretest', [
            'tes'     => $tesWithStatus,
            'peserta' => $pesertaAktif,
        ]);
    }


    public function pretestStart(Tes $tes)
    {
        return $this->startByTes(
            $tes,
            'pre-test',
            'dashboard.pretest.index',
            'dashboard.pretest.show',
            'dashboard.pretest.result'
        );
    }

    public function pretestShow(Tes $tes, Request $request)
    {
        return $this->handleTesShow(
            $tes,
            $request,
            'pre-test',
            'dashboard.pretest.start',
            'dashboard.pretest.result',
            'dashboard.pages.pre-test.pretest-start'
        );
    }

    public function pretestSubmit(Request $request, Percobaan $percobaan)
    {
        return $this->processTesSubmit(
            $request,
            $percobaan,
            'dashboard.pretest.show',
            'dashboard.pretest.result'
        );
    }

    public function pretestResult(Percobaan $percobaan)
    {
        return $this->showResult(
            $percobaan,
            'dashboard.pages.pre-test.pretest-result',
            'pre-test'
        );
    }

    /* =========================================================
     * POSTTEST
     * ========================================================= */
    public function posttest()
    {
        $login = $this->requireLogin();
        if ($login instanceof RedirectResponse) return $login;
        ['key' => $key, 'id' => $id] = $login;

        $pesertaAktif = ($key === 'peserta_id') ? Peserta::find($id) : null;

        $tes = (clone $this->baseTesQuery())
            ->where('tipe', 'post-test')
            ->get();

        $basePerc = $this->basePercobaanQuery();

        $preTes = (clone $this->baseTesQuery())
            ->where('tipe', 'pre-test')
            ->latest('id')
            ->first();

        $preScore = null;
        if ($preTes) {
            $preAttempt = (clone $basePerc)->where('tes_id', $preTes->id)
                ->whereNotNull('waktu_selesai')
                ->latest('waktu_selesai')
                ->first();
            $preScore = $preAttempt?->skor;
        }

        $tesWithStatus = $tes->map(function ($t) use ($basePerc, $preScore) {
            $latestDone = (clone $basePerc)->where('tes_id', $t->id)
                ->whereNotNull('waktu_selesai')
                ->latest('waktu_selesai')
                ->first();

            $running = (clone $basePerc)->where('tes_id', $t->id)
                ->whereNull('waktu_selesai')
                ->latest('waktu_mulai')
                ->first();

            $t->__already_done = (bool)$latestDone;
            $t->__last_score   = $latestDone?->skor;
            $t->__running_id   = $running?->id;
            $t->__done_id      = $latestDone?->id;
            $t->__pre_score    = $preScore;

            if ($latestDone && $preScore !== null) {
                $points = (int)$latestDone->skor - (int)$preScore;
                $t->__improvement_points = $points;
                $t->__improvement_percent = ((int)$preScore > 0)
                    ? (int) round(($points / (int)$preScore) * 100)
                    : null;
            } else {
                $t->__improvement_points = null;
                $t->__improvement_percent = null;
            }

            $passing = $t->passing_score ?? 70;
            $t->__above_avg = $latestDone ? ((int)$latestDone->skor >= (int)$passing) : false;

            return $t;
        });

        return view('dashboard.pages.post-test.posttest', [
            'tes'      => $tesWithStatus,
            'preScore' => $preScore,
            'peserta'  => $pesertaAktif,
        ]);
    }

    public function posttestStart(Tes $tes)
    {
        return $this->startByTes(
            $tes,
            'post-test',
            'dashboard.posttest.index',
            'dashboard.posttest.show',
            'dashboard.posttest.result'
        );
    }

    public function posttestShow(Tes $tes, Request $request)
    {
        return $this->handleTesShow(
            $tes,
            $request,
            'post-test',
            'dashboard.posttest.start',
            'dashboard.posttest.result',
            'dashboard.pages.post-test.posttest-start'
        );
    }

    public function posttestSubmit(Request $request, Percobaan $percobaan)
    {
        return $this->processTesSubmit(
            $request,
            $percobaan,
            'dashboard.posttest.show',
            'dashboard.posttest.result'
        );
    }

    public function posttestResult(Percobaan $percobaan)
    {
        return $this->showResult(
            $percobaan,
            'dashboard.pages.post-test.posttest-result',
            'post-test'
        );
    }
// ======================================================
// MONEV / SURVEY
// ======================================================

/**
 * Halaman daftar Monev (tes tipe survei).
 * Catatan:
 * - tabel tes pakai value: 'survei'
 * - tidak pakai kompetensi (hanya pelatihan)
 */
    public function monev()
    {
        $login = $this->requireLogin();
        if ($login instanceof RedirectResponse) return $login;

        // ✅ monev: tes tipe survei, tanpa filter kompetensi
        $tes = (clone $this->baseMonevTesQuery())
            ->where('tipe', 'survei')
            ->get();

        // ✅ fallback kalau pelatihan ini gak punya survei
        if ($tes->isEmpty()) {
            $fallback = Tes::query()
                ->where('tipe', 'survei')
                ->first();

            $tes = $fallback ? collect([$fallback]) : collect();
        }

        return view('dashboard.pages.monev.monev', compact('tes'));
    }

    public function monevBegin(Tes $tes)
    {
        return $this->monevStart($tes);
    }

    public function monevStart(Tes $tes)
    {
        // ✅ kalau yg diklik bukan survei, cari survei yg bisa dipakai
        if (($tes->tipe ?? null) !== 'survei') {
            $tes = (clone $this->baseMonevTesQuery())
                ->where('tipe', 'survei')
                ->first()
                ?? Tes::query()->where('tipe', 'survei')->first();

            abort_if(!$tes, 404, 'Tes survei tidak ditemukan.');
        }

        return $this->startByTes(
            $tes,
            'survey',                 // ✅ percobaan enum
            'dashboard.monev.index',
            'dashboard.monev.show',
            'dashboard.monev.result'
        );
    }

    public function monevShow(Tes $tes, Request $request)
    {
        // ✅ pastikan tetap survei, kalau bukan -> fallback
        if (($tes->tipe ?? null) !== 'survei') {
            $tes = (clone $this->baseMonevTesQuery())
                ->where('tipe', 'survei')
                ->first()
                ?? Tes::query()->where('tipe', 'survei')->first();

            abort_if(!$tes, 404, 'Tes survei tidak ditemukan.');
        }

        return $this->handleTesShow(
            $tes,
            $request,
            'survey',                  // ✅ percobaan enum
            'dashboard.monev.start',
            'dashboard.monev.result',
            'dashboard.pages.monev.monev-start'
        );
    }

    public function monevSubmit(Request $request, Percobaan $percobaan)
    {
        if (($percobaan->tipe ?? null) !== 'survey') {
            abort(404, 'Percobaan bukan survey.');
        }

        return $this->processTesSubmit(
            $request,
            $percobaan,
            'dashboard.monev.show',
            'dashboard.monev.result'
        );
    }

    public function monevResult(Percobaan $percobaan)
    {
        if (($percobaan->tipe ?? null) !== 'survey') {
            abort(404, 'Percobaan bukan survey.');
        }

        return $this->showResult(
            $percobaan,
            'dashboard.pages.monev.monev-result',
            'survey'
        );
    }

    /* =========================================================
     * SURVEY legacy dashboard (route masih ada)
     * ========================================================= */
    public function survey()
    {
        // kalau dashboard survey masih dipakai, tampilkan tipe survei dalam scope
        $tes = (clone $this->baseTesQuery())
            ->where('tipe', 'survei')
            ->get();

        return view('dashboard.pages.monev.monev', compact('tes'));
    }

    public function surveySubmit(Request $request)
    {
        // legacy submit survey, arahkan ke monev index
        return redirect()->route('dashboard.monev.index');
    }

    /* =========================================================
     * HANDLE SHOW (per-soal)
     * ========================================================= */
    protected function handleTesShow(
        Tes $tes,
        Request $request,
        string $mode,
        string $startRouteName,
        string $resultRouteName,
        string $viewPath
    ){
        $login = $this->requireLogin();
        if ($login instanceof RedirectResponse) return $login;

        // ✅ tes harus scope + tipe benar
        $allowedTes = $this->baseTesQuery()
            ->where('id', $tes->id)
            ->where('tipe', $mode)
            ->exists();

        if (!$allowedTes) {
            abort(403, 'Tes tidak tersedia untuk kompetensi/pelatihan Anda.');
        }

        $percobaanId = (int) $request->query('percobaan');
        if (!$percobaanId) {
            return redirect()->route($startRouteName, ['tes' => $tes->id])
                ->with('error', 'Silakan mulai tes terlebih dahulu.');
        }

        $percobaan = $this->basePercobaanQuery()
            ->where('id', $percobaanId)
            ->firstOrFail();

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
            $percobaan->lulus = $percobaan->skor >= ($tes->passing_score ?? 70);
            $percobaan->save();

            return redirect()->route($resultRouteName, ['percobaan' => $percobaan->id])
                ->with('error', 'Waktu tes sudah habis.');
        }

        $pertanyaanList = $tes->pertanyaan()->with('opsiJawabans')->get();
        $currentQuestionIndex = (int) $request->query('q', 0);
        $pertanyaan = $pertanyaanList->get($currentQuestionIndex);

        if (!$pertanyaan) {
            return redirect()->route($resultRouteName, ['percobaan' => $percobaan->id]);
        }

        return view($viewPath, [
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
     * SUBMIT TES
     * ========================================================= */
    protected function processTesSubmit(
        Request $request,
        Percobaan $percobaan,
        string $showRouteName,
        string $resultRouteName
    ){
        $allowedPerc = $this->basePercobaanQuery()
            ->where('id', $percobaan->id)
            ->exists();

        if (!$allowedPerc) abort(403);

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

        return redirect()->route($resultRouteName, ['percobaan' => $percobaan->id]);
    }

    /* =========================================================
     * RESULT VIEW PER MODE
     * ========================================================= */
    protected function showResult(Percobaan $percobaan, string $viewPath, string $mode = null)
    {
        $allowedPerc = $this->basePercobaanQuery()
            ->where('id', $percobaan->id)
            ->whereNotNull('waktu_selesai')
            ->exists();

        if (!$allowedPerc) abort(403);

        $percobaan->loadMissing([
            'jawabanUser.opsiJawaban',
            'tes',
            'peserta',
            'pesertaSurvei'
        ]);

        return view($viewPath, [
            'percobaan' => $percobaan,
            'mode'      => $mode,
        ]);
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
            ->filter(fn ($j) => (bool) optional($j->opsiJawaban)->apakah_benar)
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

    /* =========================================================
     * MATERI (GABUNGAN FIX)
     * ========================================================= */
    public function materi()
    {
        $pelatihanId = session('pelatihan_id');

        if (! $pelatihanId) {
            return redirect()
                ->route('dashboard.home')
                ->with('error', 'Pelatihan aktif tidak ditemukan. Login ulang.');
        }

        $materis = MateriPelatihan::query()
            ->where('pelatihan_id', $pelatihanId)
            ->where('is_published', true)
            ->orderBy('urutan')
            ->get();

        $isDummy = false;

        if ($materis->isEmpty()) {
            $isDummy = true;
            $materis = $this->dummyMateris();
        }

        $pendaftaranId = session('pendaftaran_pelatihan_id');

        $doneIds = $pendaftaranId
            ? MateriProgress::query()
                ->where('pendaftaran_pelatihan_id', $pendaftaranId)
                ->where('is_completed', true)
                ->pluck('materi_id')
                ->all()
            : [];

        $materis->each(function ($m) use ($doneIds) {
            $m->is_done = in_array($m->id, $doneIds);
        });

        return view('dashboard.pages.materi.materi-index', [
            'materiList' => $materis,
            'isDummy'    => $isDummy,
        ]);
    }

    public function materiShow(string $materi)
    {
        if (str_starts_with($materi, 'dummy-')) {

            $dummyMateris = $this->dummyMateris()
                ->sortBy('urutan')
                ->values();

            $m = $dummyMateris->firstWhere('id', $materi);
            abort_if(! $m, 404);

            $dummyMateris->each(function ($dm) {
                $dm->is_done = false;
            });

            return view('dashboard.pages.materi.materi-show', [
                'materi'         => $m,
                'materiProgress' => null,
                'progress'       => null,
                'relatedMateris' => $dummyMateris,
                'isDummy'        => true,
            ]);
        }

        $m = MateriPelatihan::query()
            ->when(
                Schema::hasColumn('materi_pelatihan', 'slug'),
                fn ($q) => $q->where('slug', $materi)
            )
            ->orWhere('id', $materi)
            ->firstOrFail();

        $pendaftaranId = session('pendaftaran_pelatihan_id');

        $progress = $pendaftaranId
            ? MateriProgress::query()
                ->where('pendaftaran_pelatihan_id', $pendaftaranId)
                ->where('materi_id', $m->id)
                ->first()
            : null;

        $doneIds = $pendaftaranId
            ? MateriProgress::query()
                ->where('pendaftaran_pelatihan_id', $pendaftaranId)
                ->where('is_completed', true)
                ->pluck('materi_id')
                ->all()
            : [];

        $relatedMateris = MateriPelatihan::query()
            ->where('pelatihan_id', $m->pelatihan_id)
            ->where('is_published', true)
            ->orderBy('urutan')
            ->get()
            ->each(function ($rm) use ($doneIds) {
                $rm->is_done = in_array($rm->id, $doneIds);
            });

        return view('dashboard.pages.materi.materi-show', [
            'materi'         => $m,
            'materiProgress' => $progress,
            'progress'       => $progress,
            'relatedMateris' => $relatedMateris,
            'isDummy'        => false,
        ]);
    }

    public function materiComplete(Request $request, string $materi)
    {
        if (str_starts_with($materi, 'dummy-')) {
            return redirect()
                ->route('dashboard.materi.show', $materi)
                ->with('success', 'Dummy materi ditandai selesai (mode demo).');
        }

        $query = MateriPelatihan::query();
        if (Schema::hasColumn('materi_pelatihan', 'slug')) {
            $query->where('slug', $materi);
        }

        $materiModel = $query->orWhere('id', $materi)->firstOrFail();

        ['key' => $key, 'id' => $id] = $this->getParticipantKeyAndId();
        if (! $key || ! $id) {
            return redirect()
                ->route('assessment.login')
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        $pendaftaranId = session('pendaftaran_pelatihan_id');

        if (! $pendaftaranId) {
            $pendaftaran = PendaftaranPelatihan::query()
                ->where('peserta_id', $id)
                ->where('pelatihan_id', $materiModel->pelatihan_id)
                ->latest('tanggal_pendaftaran')
                ->first();

            if ($pendaftaran) {
                $pendaftaranId = $pendaftaran->id;
                session(['pendaftaran_pelatihan_id' => $pendaftaranId]);
            }
        }

        if (! $pendaftaranId) {
            return redirect()
                ->route('dashboard.materi.show', $materiModel->slug ?? $materiModel->id)
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

        return redirect()
            ->route('dashboard.materi.show', $materiModel->slug ?? $materiModel->id)
            ->with('success', 'Materi ditandai selesai. Terima kasih.');
    }

    private function dummyMateris()
    {
        $now = now();

        return collect([
            (object)[
                'id' => 'dummy-1',
                'judul' => 'Pengenalan Keselamatan Kerja',
                'deskripsi' => 'Materi dasar mengenai aturan keselamatan kerja di workshop.',
                'tipe' => 'teks',
                'estimasi_menit' => 15,
                'urutan' => 1,
                'kategori' => 'Dasar',
                'file_path' => null,
                'video_url' => null,
                'link_url' => null,
                'teks' => '<p>Contoh isi materi dummy...</p>',
                'is_published' => true,
                'pelatihan_id' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            (object)[
                'id' => 'dummy-2',
                'judul' => 'Video Teknik Dasar Pengelasan',
                'deskripsi' => 'Video praktik teknik pengelasan untuk pemula.',
                'tipe' => 'video',
                'estimasi_menit' => 20,
                'urutan' => 2,
                'kategori' => 'Praktik',
                'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'file_path' => null,
                'link_url' => null,
                'teks' => null,
                'is_published' => true,
                'pelatihan_id' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            (object)[
                'id' => 'dummy-3',
                'judul' => 'Modul Mesin Bubut (PDF)',
                'deskripsi' => 'Dokumen modul lengkap tentang pengoperasian mesin bubut.',
                'tipe' => 'file',
                'estimasi_menit' => 30,
                'urutan' => 3,
                'kategori' => 'Modul',
                'file_path' => 'materi/dummy-modul-mesin-bubut.pdf',
                'video_url' => null,
                'link_url' => null,
                'teks' => null,
                'is_published' => true,
                'pelatihan_id' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            (object)[
                'id' => 'dummy-4',
                'judul' => 'Referensi External CNC',
                'deskripsi' => 'Link referensi pembelajaran CNC resmi.',
                'tipe' => 'link',
                'estimasi_menit' => 10,
                'urutan' => 4,
                'kategori' => 'Referensi',
                'link_url' => 'https://example.com/referensi-cnc',
                'file_path' => null,
                'video_url' => null,
                'teks' => null,
                'is_published' => true,
                'pelatihan_id' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }

    /* =========================================================
     * LEGACY: setPeserta (route masih ada)
     * ========================================================= */
    public function setPeserta(Request $request)
    {
        // legacy UI: dulu buat pilih peserta manual.
        // sekarang assessment login sudah fix peserta_id.
        // biar aman: hanya izinkan set ke id yang sama.
        $request->validate([
            'peserta_id' => ['required', 'integer'],
        ]);

        $current = (int) session('peserta_id');
        $target  = (int) $request->peserta_id;

        if ($current !== $target) {
            abort(403, 'Tidak diizinkan mengganti peserta.');
        }

        return back()->with('success', 'Peserta aktif sudah sesuai.');
    }

    /* =========================================================
     * LEGACY: Ajax lookup instansi by nama
     * ========================================================= */
    public function lookupInstansiByNama(Request $request)
    {
        $nama = trim((string) $request->query('nama', ''));
        if ($nama === '') {
            return response()->json([]);
        }

        if (!class_exists(Instansi::class)) {
            return response()->json([]);
        }

        $list = Instansi::query()
            ->where('asal_instansi', 'like', "%{$nama}%")
            ->limit(10)
            ->get(['id', 'asal_instansi', 'kota']);

        return response()->json($list);
    }
}
