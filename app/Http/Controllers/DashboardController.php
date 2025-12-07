<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;
use App\Models\Tes;
use App\Models\Peserta;
use App\Models\Percobaan;
use App\Models\JawabanUser;
use App\Models\PendaftaranPelatihan;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * fallback tes IDs (kalau db belum lengkap)
     */
    protected array $defaultTesIds = [
        'pre'  => 1,
        'post' => 2,
        'monev' => 3,
    ];

    /**
     * helper ambil peserta aktif dari session:
     * return ['key'=>'peserta_id'|'pesertaSurvei_id', 'id'=>int|null]
     */
    protected function getParticipantKeyAndId(): array
    {
        $pesertaId = session('peserta_id') ?? null;
        if ($pesertaId) return ['key' => 'peserta_id', 'id' => $pesertaId];

        $psId = session('pesertaSurvei_id') ?? null;
        if ($psId) return ['key' => 'pesertaSurvei_id', 'id' => $psId];

        return ['key' => null, 'id' => null];
    }

    /**
     * basis query Tes yang selalu terikat pelatihan / kompetensi aktif session
     */
    protected function baseTesQuery()
    {
        $pelatihanId  = session('pelatihan_id');
        $kompetensiId = session('kompetensi_id');

        return Tes::query()
            ->when($pelatihanId && Schema::hasColumn('tes','pelatihan_id'),
                fn($q) => $q->where('pelatihan_id', $pelatihanId)
            )
            ->when($kompetensiId && Schema::hasColumn('tes','kompetensi_id'),
                fn($q) => $q->where('kompetensi_id', $kompetensiId)
            );
    }

    /**
     * Cari Tes berdasarkan tipe logis pre/post/monev tapi tetap scope ke pelatihan aktif
     */
    protected function getTesByType(string $type): ?Tes
    {
        $type = strtolower($type);
        $base = $this->baseTesQuery();

        if ($type === 'pre') {
            $tes = (clone $base)->whereRaw('LOWER(judul) LIKE ?', ['%pre test%'])->first();
            if ($tes) return $tes;

            if (Schema::hasColumn('tes', 'sub_tipe')) {
                $tes = (clone $base)->where('sub_tipe', 'pre-test')->first();
                if ($tes) return $tes;
            }

            return (clone $base)->find($this->defaultTesIds['pre']);
        }

        if ($type === 'post') {
            $tes = (clone $base)->whereRaw('LOWER(judul) LIKE ?', ['%post test%'])->first();
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

            $tes = (clone $base)->whereRaw('LOWER(judul) LIKE ?', ['%monev%'])->first();
            if ($tes) return $tes;

            return (clone $base)->find($this->defaultTesIds['monev']);
        }

        return null;
    }

    /**
     * Statistik ringkas pre/post/monev untuk peserta aktif (session-aware)
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

        if (!$key || !$id) return $stats;

        $preTes  = $this->getTesByType('pre');
        $postTes = $this->getTesByType('post');
        $monevTes= $this->getTesByType('monev');

        if ($preTes) {
            $stats['preTestAttempts'] = Percobaan::where('tes_id', $preTes->id)->where($key, $id)->count();
            $pre = Percobaan::where('tes_id', $preTes->id)->where($key, $id)
                ->whereNotNull('waktu_selesai')->latest('waktu_selesai')->first();
            $stats['preTestScore'] = $pre->skor ?? null;
            $stats['preTestDone'] = (bool) $pre;
        }

        if ($postTes) {
            $stats['postTestAttempts'] = Percobaan::where('tes_id', $postTes->id)->where($key, $id)->count();
            $post = Percobaan::where('tes_id', $postTes->id)->where($key, $id)
                ->whereNotNull('waktu_selesai')->latest('waktu_selesai')->first();
            $stats['postTestScore'] = $post->skor ?? null;
            $stats['postTestDone'] = (bool) $post;
        }

        if ($monevTes) {
            $stats['monevAttempts'] = Percobaan::where('tes_id', $monevTes->id)->where($key, $id)->count();
            $mon = Percobaan::where('tes_id', $monevTes->id)->where($key, $id)
                ->whereNotNull('waktu_selesai')->latest('waktu_selesai')->first();
            $stats['monevScore'] = $mon->skor ?? null;
            $stats['monevDone'] = (bool) $mon;
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
    // HOME
    // ======================
    public function home(): View
    {
        $participant = $this->getParticipantKeyAndId();
        $pesertaAktif = ($participant['key'] === 'peserta_id')
            ? Peserta::with('instansi')->find($participant['id'])
            : null;

        $pelatihanId = session('pelatihan_id');

        // ✅ peserta yang tampil hanya yang terdaftar di pelatihan aktif
        $peserta = Peserta::query()
            ->when($pelatihanId, function($q) use ($pelatihanId) {
                $q->whereHas('pendaftaranPelatihan', function($qq) use ($pelatihanId){
                    $qq->where('pelatihan_id', $pelatihanId)
                       ->where('status_pendaftaran', 'diterima');
                });
            })
            ->with('instansi:id,asal_instansi,kota')
            ->orderBy('nama')
            ->get();

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
    // SET PESERTA dari modal HOME
    // ======================
    public function setPeserta(Request $request)
    {
        $validated = $request->validate([
            'nama' => ['required','string','max:150'],
            'peserta_id' => 'nullable|exists:peserta,id',
        ]);

        // kalau pilih dari dropdown
        if ($request->filled('peserta_id')) {
            $peserta = Peserta::with('instansi')->findOrFail($request->peserta_id);

            // ✅ jangan reset pelatihan/kompetensi session
            session(['peserta_id' => $peserta->id]);

            session([
                'instansi_id'   => optional($peserta->instansi)->id,
                'instansi_nama' => optional($peserta->instansi)->asal_instansi,
                'instansi_kota' => optional($peserta->instansi)->kota,
            ]);

            return redirect()->route('dashboard.home')->with('success','Peserta dipilih: '.$peserta->nama);
        }

        $nama = mb_strtolower(trim($validated['nama']));

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

        $peserta = $matches->first();

        // ✅ jangan hapus pelatihan_id / kompetensi_id (hasil login token)
        $request->session()->forget(['peserta_id','instansi_id','instansi_nama','instansi_kota']);

        session([
            'peserta_id'    => $peserta->id,
            'instansi_id'   => optional($peserta->instansi)->id,
            'instansi_nama' => optional($peserta->instansi)->asal_instansi,
            'instansi_kota' => optional($peserta->instansi)->kota,
        ]);

        return redirect()->route('dashboard.home')
            ->with('success', 'Peserta dipilih: '.$peserta->nama.' — '.(session('instansi_nama') ?? ''));
    }

    public function lookupInstansiByNama(Request $request)
    {
        $nama = mb_strtolower(trim($request->get('nama','')));

        if ($nama === '') {
            return response()->json(['ok' => false, 'message' => 'Nama kosong']);
        }

        $peserta = Peserta::with('instansi:id,asal_instansi,kota')
            ->whereRaw('LOWER(nama) = ?', [$nama])
            ->first();

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

        return response()->json([
            'ok' => true,
            'data' => [
                'peserta_id' => $peserta->id,
                'nama'       => $peserta->nama,
                'instansi'   => optional($peserta->instansi)->asal_instansi,
                'kota'       => optional($peserta->instansi)->kota,
            ]
        ]);
    }

    // ======================
    // LOGOUT
    // ======================
    public function logout(Request $request)
    {
        $request->session()->forget([
            'peserta_id','pesertaSurvei_id',
            'pendaftaran_pelatihan_id','pelatihan_id','kompetensi_id',
            'instansi_id','instansi_nama','instansi_kota'
        ]);
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('dashboard.home')
            ->with('success', 'Logout berhasil.');
    }

    // ======================
    // GENERIC START TEST
    // ======================
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
            'tes_id'       => $tes->id,
            'waktu_mulai'  => now(),
        ];
        $data[$key] = $id;

        if (Schema::hasColumn('percobaan', 'tipe')) {
            $data['tipe'] = $typeKey;
        }

        $percobaan = Percobaan::create($data);

        return redirect()->route($showRoute, ['tes' => $tes->id, 'percobaan' => $percobaan->id])
            ->with('success', "{$typeLabel} dimulai!");
    }

    // ======================
    // SUBMIT ATTEMPT (API)
    // ======================
    public function submitAttempt(Request $request)
    {
        $participant = $this->getParticipantKeyAndId();
        $key = $participant['key']; $id = $participant['id'];

        if (!$key || !$id) {
            return response()->json(['ok' => false, 'message' => 'Peserta belum dipilih.'], 422);
        }

        $request->validate([
            'tes_id' => 'required|integer|exists:tes,id',
        ]);

        $tes = Tes::findOrFail($request->tes_id);

        $attemptCount = Percobaan::where('tes_id', $tes->id)
            ->where($key, $id)->count();

        $maxAttempts = \App\Filament\Resources\TesPercobaanResource::MAX_ATTEMPTS_PER_TES ?? 1;
        if ($attemptCount >= $maxAttempts) {
            return response()->json(['ok' => false, 'message' => 'Maksimal percobaan tercapai'], 422);
        }

        $pel = $tes->pelatihan ?? null;
        if ($pel) {
            $now = now();
            $jenis = strtolower($tes->jenis ?? $tes->tipe ?? '');

            if ($jenis === 'pretest' || str_contains($jenis, 'pre')) {
                $from = Carbon::parse($pel->tanggal_mulai)
                    ->addDays(\App\Filament\Resources\TesPercobaanResource::PRETEST_AVAILABLE_FROM_OFFSET_DAYS);
                $to   = Carbon::parse($pel->tanggal_mulai)
                    ->addDays(\App\Filament\Resources\TesPercobaanResource::PRETEST_AVAILABLE_TO_OFFSET_DAYS)
                    ->endOfDay();
            } elseif ($jenis === 'posttest' || str_contains($jenis, 'post')) {
                $from = Carbon::parse($pel->tanggal_selesai)
                    ->addDays(\App\Filament\Resources\TesPercobaanResource::POSTTEST_AVAILABLE_FROM_OFFSET_DAYS);
                $to   = Carbon::parse($pel->tanggal_selesai)
                    ->addDays(\App\Filament\Resources\TesPercobaanResource::POSTTEST_AVAILABLE_TO_OFFSET_DAYS)
                    ->endOfDay();
            } else {
                $from = now()->subYear();
                $to   = now()->addYear();
            }

            if (! $now->between($from, $to)) {
                return response()->json(['ok'=>false, 'message'=>'Tes belum tersedia pada jadwal saat ini'], 422);
            }
        }

        $running = Percobaan::where('tes_id', $tes->id)
            ->where($key, $id)
            ->whereNull('waktu_selesai')
            ->first();

        $durationMinutes = \App\Filament\Resources\TesPercobaanResource::ATTEMPT_DURATION_MINUTES ?? 30;

        if ($running) {
            $startAt = $running->waktu_mulai ? Carbon::parse($running->waktu_mulai) : Carbon::now();
            $allowedFinish = $startAt->copy()->addMinutes($durationMinutes);

            if (Carbon::now()->greaterThan($allowedFinish)) {
                $running->waktu_selesai = $running->waktu_selesai ?? now();
                $running->skor  = $this->hitungSkor($running);
                $running->lulus = $running->skor >= ($running->tes->passing_score ?? 70);
                $running->save();

                return response()->json(['ok' => false, 'message' => 'Waktu pengerjaan telah habis', 'data' => $running], 422);
            }
        } else {
            $data = ['tes_id' => $tes->id, 'waktu_mulai' => now()];
            $data[$key] = $id;
            if (Schema::hasColumn('percobaan', 'tipe')) {
                $data['tipe'] = strtolower($tes->jenis ?? $tes->tipe ?? '');
            }
            $running = Percobaan::create($data);
        }

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

        $running->skor = $request->filled('skor')
            ? (float) $request->input('skor')
            : $this->hitungSkor($running);

        $running->waktu_selesai = now();
        $running->lulus = $running->skor >= ($running->tes->passing_score ?? 70);
        $running->save();

        return response()->json(['ok' => true, 'message' => 'Tersimpan', 'data' => $running]);
    }

    // ======================
    // PRETEST
    // ======================
    public function pretest(Request $request)
    {
        $participant = $this->getParticipantKeyAndId();
        $key = $participant['key']; $id = $participant['id'];

        if (!$key || !$id)
            return redirect()->route('dashboard.home')->with('error','Silakan pilih peserta terlebih dahulu.');

        $peserta = ($key === 'peserta_id') ? Peserta::find($id) : null;
        if (!$peserta)
            return redirect()->route('dashboard.home')->with('error','Silakan pilih peserta terlebih dahulu.');

        $tes = $this->baseTesQuery()->get();

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

    protected function handleTesShow(Tes $tes, Request $request, string $mode, string $resultRouteName)
    {
        $percobaanId = (int) $request->query('percobaan');
        if (!$percobaanId) {
            $startRoute = $mode === 'post-test' ? 'dashboard.posttest.start' : 'dashboard.pretest.start';
            return redirect()->route($startRoute, $tes->id)
                ->with('error', 'Pilih peserta terlebih dahulu untuk memulai tes.');
        }

        $percobaan = Percobaan::findOrFail($percobaanId);
        if ($percobaan->tes_id !== $tes->id) abort(404);

        if ($percobaan->waktu_selesai) {
            return redirect()->route($resultRouteName, ['percobaan' => $percobaan->id]);
        }

        $duration = ($tes->durasi_menit ?? 0) * 60;
        $elapsed  = $percobaan->waktu_mulai ? now()->diffInSeconds($percobaan->waktu_mulai) : 0;
        $remaining = max($duration - $elapsed, 0);

        if ($duration > 0 && $remaining <= 0) {
            $percobaan->waktu_selesai = $percobaan->waktu_selesai ?? now();
            $jawaban = $percobaan->jawabanUser;
            $total = $jawaban->count();
            $benar = $jawaban->filter(fn($j) => $j->opsiJawaban && $j->opsiJawaban->apakah_benar)->count();
            $percobaan->skor = $total > 0 ? round(($benar / $total) * 100, 2) : 0;
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

    public function pretestShow(Tes $tes, Request $request)
    {
        return $this->handleTesShow($tes, $request, 'pre-test', 'dashboard.pretest.result');
    }

    public function posttestShow(Tes $tes, Request $request)
    {
        return $this->handleTesShow($tes, $request, 'post-test', 'dashboard.posttest.result');
    }

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

        if ($request->boolean('cheat_flag')) {
            session()->put('cheat_'.$percobaan->id, true);
        }

        return redirect()->route($resultRouteName, ['percobaan' => $percobaan->id]);
    }

    public function pretestSubmit(Request $request, Percobaan $percobaan)
    {
        return $this->processTesSubmit($request, $percobaan, 'dashboard.pretest.show', 'dashboard.pretest.result');
    }

    // ======================
    // POSTTEST
    // ======================
    public function posttest(Request $request)
    {
        $participant = $this->getParticipantKeyAndId();
        $key = $participant['key']; $id = $participant['id'];

        if (!$key || !$id)
            return redirect()->route('dashboard.home')->with('error','Silakan pilih peserta terlebih dahulu.');

        $peserta = ($key === 'peserta_id') ? Peserta::find($id) : null;
        if (!$peserta)
            return redirect()->route('dashboard.home')->with('error','Silakan pilih peserta terlebih dahulu.');

        $tes = $this->baseTesQuery()->get();

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

    public function posttestSubmit(Request $request, Percobaan $percobaan)
    {
        return $this->processTesSubmit($request, $percobaan, 'dashboard.posttest.show', 'dashboard.posttest.result');
    }

    public function pretestResult(Percobaan $percobaan)
    {
        $percobaan->loadMissing(['jawabanUser.opsiJawaban', 'tes', 'peserta']);

        return view('dashboard.pages.tes.result', [
            'percobaan' => $percobaan,
            'mode'      => 'pre-test',
        ]);
    }

    public function posttestResult(Percobaan $percobaan)
    {
        $percobaan->loadMissing(['jawabanUser.opsiJawaban', 'tes', 'peserta', 'pesertaSurvei']);

        $participant = $this->getParticipantKeyAndId();
        $key = $participant['key'];
        $id  = $participant['id'];

        $preScore = null;
        $improvementPoints = null;
        $improvementPercent = null;

        if ($key && $id) {
            $preAttempt = Percobaan::where($key, $id)
                ->where('tes_id', $percobaan->tes_id)
                ->where(function ($q) {
                    $q->where('tipe', 'pre')
                      ->orWhere('tipe', 'pre-test')
                      ->orWhereNull('tipe');
                })
                ->whereNotNull('waktu_selesai')
                ->orderByDesc('waktu_selesai')
                ->first();

            if ($preAttempt && $preAttempt->skor !== null && $percobaan->skor !== null) {
                $preScore = (float) $preAttempt->skor;
                $postScore = (float) $percobaan->skor;
                $improvementPoints = $postScore - $preScore;

                $improvementPercent = $preScore > 0
                    ? round((($postScore - $preScore) / $preScore) * 100, 2)
                    : null;
            }
        }

        return view('dashboard.pages.tes.result', [
            'percobaan'          => $percobaan,
            'mode'               => 'post-test',
            'preScore'           => $preScore,
            'improvementPoints'  => $improvementPoints,
            'improvementPercent' => $improvementPercent,
        ]);
    }

    // ======================
    // MONEV
    // ======================
    public function monev()
    {
        $base = $this->baseTesQuery();

        if (Schema::hasColumn('tes','sub_tipe')) {
            $tes = (clone $base)->where('sub_tipe', 'monev')->get();
        } else {
            $tes = (clone $base)->whereRaw('LOWER(judul) LIKE ?', ['%monev%'])->get();
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

        if (!$key || !$id)
            return redirect()->route('dashboard.home')->with('error','Silakan pilih peserta terlebih dahulu.');

        $pid = ($key === 'peserta_id') ? Peserta::findOrFail($id) : null;

        // ✅ survey juga scope pelatihan aktif
        $tes = $this->baseTesQuery()
            ->where('tipe', 'survei')
            ->first();

        if (!$tes)
            return redirect()->route('dashboard.home')->with('error','Survey tidak ditemukan.');

        return redirect()->route('survey.step', [
            'peserta' => $pid ? $pid->id : $id,
            'order'   => $tes->id
        ]);
    }

    public function surveySubmit(Request $request)
    {
        return redirect()->route('dashboard.survey')
            ->with('success', 'Survey berhasil dikerjakan!');
    }

    // ======================
    // Helper skor
    // ======================
    protected function hitungSkor(Percobaan $percobaan): int
    {
        $percobaan->loadMissing(['jawabanUser.opsiJawaban']);
        $jawabanCollection = $percobaan->jawabanUser ?? collect();

        $total = $jawabanCollection->count();
        if ($total === 0) return 0;

        $benar = $jawabanCollection
            ->filter(fn($j) => ($j->opsiJawaban->apakah_benar ?? false))
            ->count();

        return (int) round(($benar / $total) * 100);
    }
}
