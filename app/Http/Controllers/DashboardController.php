<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
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
     * HELPER: peserta aktif dari session (login assessment)
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
     * HELPER: pastikan session pelatihan/kompetensi dari login
     * ========================================================= */
    protected function ensureActiveTrainingSession(?Peserta $pesertaAktif = null): void
    {
        // kalau sudah ada dari login assessment => selesai
        if (session('pendaftaran_pelatihan_id') && session('pelatihan_id')) {
            return;
        }

        // fallback safety kalau session ilang
        if ($pesertaAktif) {
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
     * HOME DASHBOARD (FIX return type)
     * ========================================================= */
    public function home(): View|RedirectResponse
    {
        ['key' => $key, 'id' => $id] = $this->getParticipantKeyAndId();

        if (!$key || !$id) {
            return redirect()->route('assessment.login')
                ->with('error', 'Silakan login dahulu.');
        }

        $pesertaAktif = ($key === 'peserta_id')
            ? Peserta::with('instansi:id,asal_instansi,kota')->find($id)
            : null;

        $this->ensureActiveTrainingSession($pesertaAktif);

        $pelatihanId = session('pelatihan_id');

        // ===== Materi =====
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

            $doneIds = [];
            if ($pendaftaranId) {
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
     * START TEST GENERIC
     * ========================================================= */
    protected function startTest(string $typeKey, string $typeLabel, string $showRoute, string $resultRoute)
    {
        ['key' => $key, 'id' => $id] = $this->getParticipantKeyAndId();
        if (!$key || !$id) {
            return redirect()->route('assessment.login')
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        $pesertaAktif = ($key === 'peserta_id') ? Peserta::find($id) : null;
        $this->ensureActiveTrainingSession($pesertaAktif);

        $tes = $this->getTesByType($typeKey);
        if (!$tes) {
            return back()->with('error', "{$typeLabel} tidak ditemukan untuk pelatihan ini.");
        }

        $basePerc = $this->basePercobaanQuery()->where('tes_id', $tes->id);

        $done = (clone $basePerc)->whereNotNull('waktu_selesai')->exists();
        if ($done) {
            $perc = (clone $basePerc)->whereNotNull('waktu_selesai')
                ->latest('waktu_selesai')->first();

            return redirect()->route($resultRoute, ['percobaan' => $perc->id])
                ->with('success', "Anda sudah mengerjakan {$typeLabel}. Berikut hasilnya.");
        }

        $running = (clone $basePerc)->whereNull('waktu_selesai')->first();
        if ($running) {
            return redirect()->route($showRoute, [
                'tes'       => $tes->id,
                'percobaan' => $running->id,
            ]);
        }

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
            return redirect()->route('assessment.login')
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        $pesertaAktif = ($key === 'peserta_id') ? Peserta::find($id) : null;
        $this->ensureActiveTrainingSession($pesertaAktif);

        $pelatihanId  = session('pelatihan_id');
        $kompetensiId = session('kompetensi_id');

        if (!$pelatihanId || !$kompetensiId) {
            return redirect()->route('dashboard.home')
                ->with('error', 'Pelatihan/kompetensi aktif tidak ditemukan. Login ulang.');
        }

        // ✅ ambil PRE-TEST hanya yg sesuai pelatihan & kompetensi aktif
        $tes = Tes::query()
            ->where('pelatihan_id', $pelatihanId)
            ->where('kompetensi_id', $kompetensiId)
            ->where('tipe', 'pre-test')
            ->orderBy('id') // urutan gak ada di tabel tes
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

            return $t;
        });

        return view('dashboard.pages.pre-test.pretest', [
            'tes'     => $tesWithStatus,
            'peserta' => $pesertaAktif,
        ]);
    }

    /**
     * START PRETEST:
     * - Kalau sudah selesai => redirect ke hasil terakhir
     * - Kalau ada running => lanjutkan
     * - Kalau belum ada => buat percobaan baru
     */
    public function pretestStart(Tes $tes)
    {
        ['key' => $key, 'id' => $id] = $this->getParticipantKeyAndId();
        if (!$key || !$id) {
            return redirect()->route('assessment.login')
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        $pesertaAktif = ($key === 'peserta_id') ? Peserta::find($id) : null;
        $this->ensureActiveTrainingSession($pesertaAktif);

        // SECURITY: tes harus sesuai scope pelatihan peserta
        $allowedTes = $this->baseTesQuery()
            ->where('id', $tes->id)
            ->when(Schema::hasColumn('tes', 'tipe'), fn($q) => $q->where('tipe', 'pre-test'))
            ->exists();

        if (!$allowedTes) {
            return redirect()->route('dashboard.pretest.index')
                ->with('error', 'Tes Pre-Test ini tidak tersedia untuk pelatihan Anda.');
        }

        $basePerc = $this->basePercobaanQuery()->where('tes_id', $tes->id);

        // kalau sudah pernah selesai => lompat ke hasil
        $done = (clone $basePerc)
            ->whereNotNull('waktu_selesai')
            ->latest('waktu_selesai')
            ->first();

        if ($done) {
            return redirect()->route('dashboard.pretest.result', ['percobaan' => $done->id])
                ->with('success', 'Anda sudah mengerjakan Pre-Test. Berikut hasilnya.');
        }

        // kalau ada yg sedang berjalan => lanjutkan
        $running = (clone $basePerc)->whereNull('waktu_selesai')->first();
        if ($running) {
            return redirect()->route('dashboard.pretest.show', [
                'tes'       => $tes->id,
                'percobaan' => $running->id
            ]);
        }

        // buat percobaan baru
        $data = [
            'tes_id'      => $tes->id,
            'waktu_mulai' => now(),
            $key          => $id,
        ];

        if (Schema::hasColumn('percobaan', 'pelatihan_id')) {
            $data['pelatihan_id'] = session('pelatihan_id');
        }

        if (Schema::hasColumn('percobaan', 'tipe')) {
            $data['tipe'] = 'pre-test';
        }

        $percobaan = Percobaan::create($data);

        return redirect()->route('dashboard.pretest.show', [
            'tes'       => $tes->id,
            'percobaan' => $percobaan->id
        ])->with('success', 'Pre-Test dimulai!');
    }


    public function pretestShow(Tes $tes, Request $request)
    {
        // SECURITY: tes harus sesuai scope pelatihan peserta
        $allowedTes = $this->baseTesQuery()
            ->where('id', $tes->id)
            ->when(Schema::hasColumn('tes', 'tipe'), fn($q) => $q->where('tipe', 'pre-test'))
            ->exists();

        if (!$allowedTes) abort(404);

        return $this->handleTesShow($tes, $request, 'pre-test', 'dashboard.pretest.result');
    }


    public function pretestSubmit(Request $request, Percobaan $percobaan)
    {
        // SECURITY: percobaan harus milik peserta & scope pelatihan aktif
        $allowedPerc = $this->basePercobaanQuery()
            ->where('id', $percobaan->id)
            ->exists();

        if (!$allowedPerc) abort(403);

        return $this->processTesSubmit($request, $percobaan, 'dashboard.pretest.show', 'dashboard.pretest.result');
    }


    public function pretestResult(Percobaan $percobaan)
    {
        // SECURITY: hasil harus milik peserta & scope pelatihan aktif
        $allowedPerc = $this->basePercobaanQuery()
            ->where('id', $percobaan->id)
            ->whereNotNull('waktu_selesai')
            ->exists();

        if (! $allowedPerc) {
            abort(403);
        }

        // ✅ FIX relasi:
        // JawabanUser -> opsiJawaban (singular)
        $percobaan->loadMissing([
            'jawabanUser.opsiJawaban',
            'tes',
            'peserta',
            'pesertaSurvei',
        ]);

        return view('dashboard.pages.pre-test.pretest-result', [
            'percobaan' => $percobaan,
        ]);
    }


    /* =========================================================
     * POSTTEST
     * ========================================================= */

        public function posttest()
    {
        ['key' => $key, 'id' => $id] = $this->getParticipantKeyAndId();
        if (!$key || !$id) {
            return redirect()->route('assessment.login')
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        $pesertaAktif = ($key === 'peserta_id') ? Peserta::find($id) : null;
        $this->ensureActiveTrainingSession($pesertaAktif);

        $pelatihanId  = session('pelatihan_id');
        $kompetensiId = session('kompetensi_id');

        if (!$pelatihanId || !$kompetensiId) {
            return redirect()->route('dashboard.home')
                ->with('error', 'Pelatihan/kompetensi aktif tidak ditemukan. Login ulang.');
        }

        // ✅ ambil POST-TEST hanya yg sesuai pelatihan & kompetensi aktif
        $tes = Tes::query()
            ->where('pelatihan_id', $pelatihanId)
            ->where('kompetensi_id', $kompetensiId)
            ->where('tipe', 'post-test')
            ->orderBy('id')
            ->get();

        $basePerc = $this->basePercobaanQuery();

        // pretest terakhir untuk improvement
        $preTes = Tes::query()
            ->where('pelatihan_id', $pelatihanId)
            ->where('kompetensi_id', $kompetensiId)
            ->where('tipe', 'pre-test')
            ->latest('id')
            ->first();

        $preScore = null;
        if ($preTes) {
            $preAttempt = (clone $basePerc)
                ->where('tes_id', $preTes->id)
                ->whereNotNull('waktu_selesai')
                ->latest('waktu_selesai')
                ->first();

            $preScore = $preAttempt?->skor;
        }

        $tesWithStatus = $tes->map(function ($t) use ($basePerc, $preScore) {

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

            $t->__pre_score = $preScore;

            if ($preScore !== null && $t->__last_score !== null) {
                $t->__improvement_points  = round($t->__last_score - $preScore, 2);
                $t->__improvement_percent = $preScore > 0
                    ? round((($t->__last_score - $preScore) / $preScore) * 100, 2)
                    : null;
            } else {
                $t->__improvement_points  = null;
                $t->__improvement_percent = null;
            }

            $passing = $t->passing_score ?? 70;
            $t->__above_avg = $t->__last_score !== null
                ? ($t->__last_score >= $passing)
                : false;

            return $t;
        });

        return view('dashboard.pages.post-test.posttest', [
            'tes'      => $tesWithStatus,
            'preScore' => $preScore,
            'peserta'  => $pesertaAktif,
        ]);
    }

    public function startPostTest()
    {
        return $this->startTest(
            'post',
            'Post-Test',
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
            'dashboard.posttest.result'
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
        // ✅ FIX relasi:
        $percobaan->loadMissing([
            'jawabanUser.opsiJawaban',
            'tes',
            'peserta',
            'pesertaSurvei',
        ]);

        // safety: pastikan hasil itu milik pelatihan aktif user
        $pelatihanAktif = session('pelatihan_id');

        // pelatihan_id bisa nempel di percobaan atau ambil dari tes
        $pelatihanPercobaan = $percobaan->pelatihan_id
            ?? $percobaan->tes?->pelatihan_id;

        if ($pelatihanAktif && $pelatihanPercobaan != $pelatihanAktif) {
            abort(403, 'Anda tidak berhak melihat hasil ini.');
        }

        $preScore = null;
        $improvementPoints = null;
        $improvementPercent = null;

        // cari pre-test yang sesuai pelatihan aktif
        $preTes = $this->getTesByType('pre');

        if ($preTes) {
            $preAttempt = (clone $this->basePercobaanQuery())
                ->where('tes_id', $preTes->id)
                ->whereNotNull('waktu_selesai')
                ->latest('waktu_selesai')
                ->first();

            if ($preAttempt && $preAttempt->skor !== null && $percobaan->skor !== null) {
                $preScore  = (float) $preAttempt->skor;
                $postScore = (float) $percobaan->skor;

                $improvementPoints  = round($postScore - $preScore, 2);
                $improvementPercent = $preScore > 0
                    ? round((($postScore - $preScore) / $preScore) * 100, 2)
                    : null;
            }
        }

        return view('dashboard.pages.post-test.posttest-result', [
            'percobaan'          => $percobaan,
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

        $pertanyaanList = $tes->pertanyaan()->with('opsiJawabans')->get();
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
            return redirect()->route('assessment.login')
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        return redirect()->route('dashboard.monev.begin', ['tes' => $tes->id]);
    }

    public function monevBegin(Request $request, $tesId)
    {
        ['key' => $key, 'id' => $id] = $this->getParticipantKeyAndId();
        if (!$key || !$id) {
            return redirect()->route('assessment.login')
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
        $percobaan->loadMissing(['jawabanUser.opsiJawabans', 'tes', 'peserta', 'pesertaSurvei']);

        return view('dashboard.pages.tes.result', [
            'percobaan' => $percobaan,
            'mode'      => 'survey',
        ]);
    }

    public function materi()
    {
        $pelatihanId = session('pelatihan_id');

        if (! $pelatihanId) {
            return redirect()
                ->route('dashboard.home')
                ->with('error', 'Pelatihan aktif tidak ditemukan. Login ulang.');
        }

        // ✅ ambil real materi
        $materis = MateriPelatihan::query()
            ->where('pelatihan_id', $pelatihanId)
            ->where('is_published', true)
            ->orderBy('urutan')
            ->get();

        $isDummy = false;

        // ✅ fallback dummy kalau DB kosong
        if ($materis->isEmpty()) {
            $isDummy = true;
            $materis = $this->dummyMateris();
        }

        // ✅ tandai progress selesai
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


    /* =========================================================
     * MATERI SHOW (REAL + DUMMY)
     * ========================================================= */
    public function materiShow(string $materi)
    {
        /**
         * ==========================================================
         * 1) DUMMY MODE (tanpa DB)
         * ==========================================================
         */
        if (str_starts_with($materi, 'dummy-')) {

            // ambil dummy, urutkan supaya sidebar rapi
            $dummyMateris = $this->dummyMateris()
                ->sortBy('urutan')
                ->values();

            // cari materi aktif dari list yang sudah terurut
            $m = $dummyMateris->firstWhere('id', $materi);
            abort_if(! $m, 404);

            // inject is_done default false biar sidebar aman
            $dummyMateris->each(function ($dm) {
                $dm->is_done = false;
            });

            return view('dashboard.pages.materi.materi-show', [
                'materi'         => $m,
                'materiProgress' => null,
                'progress'       => null, // alias aman di blade
                'relatedMateris' => $dummyMateris,
                'isDummy'        => true,
            ]);
        }

        /**
         * ==========================================================
         * 2) REAL MODE (ambil dari DB)
         * slug optional -> hanya dipakai kalau kolomnya ada
         * ==========================================================
         */
        $m = MateriPelatihan::query()
            ->when(
                Schema::hasColumn('materi_pelatihan', 'slug'),
                fn ($q) => $q->where('slug', $materi)
            )
            ->orWhere('id', $materi)
            ->firstOrFail();

        /**
         * ==========================================================
         * 3) PROGRESS materi aktif
         * ==========================================================
         */
        $pendaftaranId = session('pendaftaran_pelatihan_id');

        $progress = $pendaftaranId
            ? MateriProgress::query()
                ->where('pendaftaran_pelatihan_id', $pendaftaranId)
                ->where('materi_id', $m->id)
                ->first()
            : null;

        /**
         * ==========================================================
         * 4) DONE IDS (materi yang sudah selesai)
         * ==========================================================
         */
        $doneIds = $pendaftaranId
            ? MateriProgress::query()
                ->where('pendaftaran_pelatihan_id', $pendaftaranId)
                ->where('is_completed', true)
                ->pluck('materi_id')
                ->all()
            : [];

        /**
         * ==========================================================
         * 5) RELATED MATERI + inject is_done
         * ==========================================================
         */
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
            'progress'       => $progress, // alias aman
            'relatedMateris' => $relatedMateris,
            'isDummy'        => false,
        ]);
    }

    /* =========================================================
     * MATERI COMPLETE (REAL + DUMMY)
     * ========================================================= */
   public function materiComplete(Request $request, string $materi)
{
    /**
     * ✅ DUMMY COMPLETE: gak sentuh DB
     */
    if (str_starts_with($materi, 'dummy-')) {
        return redirect()
            ->route('dashboard.materi.show', $materi)
            ->with('success', 'Dummy materi ditandai selesai (mode demo).');
    }

    /**
     * ✅ Ambil materi (slug optional)
     */
    $query = MateriPelatihan::query();

    if (Schema::hasColumn('materi_pelatihan', 'slug')) {
        $query->where('slug', $materi);
    }

    $materiModel = $query->orWhere('id', $materi)->firstOrFail();

    /**
     * ✅ Pastikan peserta login assessment
     */
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
    /* =========================================================
     * PRIVATE: DUMMY DATA
     * ========================================================= */
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
     * HITUNG SKOR
     * ========================================================= */
    protected function hitungSkor(Percobaan $percobaan): int
    {
        // ✅ JawabanUser belongsTo OpsiJawaban (singular)
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
}
