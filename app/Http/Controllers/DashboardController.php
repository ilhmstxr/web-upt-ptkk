<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\Tes;
use App\Models\Peserta;
use App\Models\Percobaan;
use App\Models\JawabanUser;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    // ======================
    // DASHBOARD INDEX
    // ======================
    public function index(): View
    {
        $pesertaId = session('peserta_id');

        $surveyStatus = 'pending';

        $preTestAttempts = Percobaan::where('tes_id', 1)
            ->where('peserta_id', $pesertaId)
            ->count();

        $preTestMax = 1;
        $postTestMax = 1;
        $monevMax    = 1;

        $postTestDone = Percobaan::where('tes_id', 2)
            ->where('peserta_id', $pesertaId)
            ->exists();

        $monevDone = Percobaan::where('tes_id', 3)
            ->where('peserta_id', $pesertaId)
            ->exists();

        return view('dashboard.index', compact(
            'surveyStatus',
            'preTestAttempts',
            'preTestMax',
            'postTestMax',
            'monevMax',
            'postTestDone',
            'monevDone'
        ));
    }

    // ======================
    // HOME & PROFILE
    // ======================
    public function home(): View
    {
        // ambil semua peserta untuk ditampilkan di overlay
        $peserta = Peserta::all();

        // ambil peserta aktif dari session
        $pesertaId = session('peserta_id');
        $pesertaAktif = $pesertaId ? Peserta::find($pesertaId) : null;

        // gunakan peserta_id untuk percobaan
        $preTestAttempts = Percobaan::where('tes_id', 1)
            ->where('peserta_id', $pesertaId)
            ->count();

        $preTestMax = 1;
        $postTestMax = 1;
        $monevMax    = 1;

        $postTestDone = Percobaan::where('tes_id', 2)
            ->where('peserta_id', $pesertaId)
            ->exists();

        $monevDone = Percobaan::where('tes_id', 3)
            ->where('peserta_id', $pesertaId)
            ->exists();

        return view('dashboard.pages.home', compact(
            'peserta',
            'pesertaAktif',
            'preTestAttempts',
            'preTestMax',
            'postTestMax',
            'monevMax',
            'postTestDone',
            'monevDone'
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
        ]);

        $nama = mb_strtolower(trim($validated['nama']));

        // Cari exact match dulu
        $matches = Peserta::with('instansi:id,asal_instansi,kota')
            ->whereRaw('LOWER(nama) = ?', [$nama])
            ->get();

        // Fallback LIKE kalau belum ketemu
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

        // Kalau lebih dari satu, ambil yang pertama
        $peserta = $matches->first();

        // reset & regenerate seperti login
        $request->session()->forget(['peserta_id','instansi_id','instansi_nama','instansi_kota']);
        $request->session()->regenerate();

        // simpan peserta + sekolah ke session
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
            return response()->json(['ok'=>false,'message'=>'Nama kosong'], 422);
        }

        // Exact dulu
        $q = Peserta::with('instansi:id,asal_instansi,kota')
            ->whereRaw('LOWER(nama) = ?', [$nama]);

        $peserta = $q->first();

        // Fallback LIKE 1 kandidat teratas
        if (!$peserta) {
            $like = '%'.str_replace(' ','%',$nama).'%';
            $peserta = Peserta::with('instansi:id,asal_instansi,kota')
                ->whereRaw('LOWER(nama) LIKE ?', [$like])
                ->orderBy('nama')
                ->first();
        }

        if (!$peserta) {
            return response()->json(['ok'=>false,'message'=>'Peserta tidak ditemukan'], 404);
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
    // Logout - hapus session
    // ======================
    public function logout(Request $request)
    {
        $request->session()->forget(['peserta_id']);
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('dashboard.home')
            ->with('success', 'Logout berhasil.');
    }

    // ======================
    // PRE-TEST
    // ======================
    public function pretest(Request $request)
    {
        $pesertaId = session('peserta_id'); // ambil peserta dari session
        $peserta = \App\Models\Peserta::find($pesertaId);

        if (!$peserta) {
            return redirect()->route('dashboard.home')
                ->with('error', 'Silakan pilih peserta terlebih dahulu.');
        }

        // Ambil tes berdasarkan bidang peserta
        $tes = \App\Models\Tes::where('bidang_id', $peserta->bidang_id)->get();

        return view('dashboard.pages.pre-test.pretest', compact('tes', 'peserta'));
    }

    public function pretestStart(Tes $tes)
    {
        $pesertaId = session('peserta_id');
        if (!$pesertaId) {
            return redirect()->route('dashboard.home')->with('error', 'Silakan pilih peserta terlebih dahulu.');
        }

        // Langsung buat percobaan baru
        $percobaan = Percobaan::create([
            'peserta_id'      => $pesertaId,
            'tes_id'          => $tes->id,
            'waktu_mulai'     => now(),
        ]);

        return redirect()->route('dashboard.pretest.show', [
            'tes'       => $tes->id,
            'percobaan' => $percobaan->id,
        ])->with('success', 'Pre-test dimulai!');
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

        $duration = $tes->durasi_menit * 60;
        $elapsed = $percobaan->waktu_mulai ? now()->diffInSeconds($percobaan->waktu_mulai) : 0;
        $remaining = max($duration - $elapsed, 0);

        if ($remaining <= 0) {
            $percobaan->waktu_selesai = $percobaan->waktu_selesai ?? now();

            // Hitung skor langsung
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
            'tes',
            'pertanyaan',
            'percobaan',
            'pertanyaanList',
            'currentQuestionIndex',
            'remaining'
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

        // Hitung skor langsung
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
    // POST-TEST
    // ======================
    public function posttest(Request $request)
    {
        $pesertaId = session('peserta_id'); // ambil peserta dari session
        $peserta = \App\Models\Peserta::find($pesertaId);

        if (!$peserta) {
            return redirect()->route('dashboard.home')
                ->with('error', 'Silakan pilih peserta terlebih dahulu.');
        }

        // Ambil tes berdasarkan bidang peserta
        $tes = \App\Models\Tes::where('bidang_id', $peserta->bidang_id)->get();

        return view('dashboard.pages.post-test.posttest', compact('tes', 'peserta'));
    }

    public function posttestStart(Tes $tes)
    {
        $pesertaId = session('peserta_id');
        if (!$pesertaId) {
            return redirect()->route('dashboard.home')->with('error', 'Silakan pilih peserta terlebih dahulu.');
        }

        // Langsung buat percobaan baru
        $percobaan = Percobaan::create([
            'peserta_id'      => $pesertaId,
            'tes_id'          => $tes->id,
            'waktu_mulai'     => now(),
        ]);

        return redirect()->route('dashboard.posttest.show', [
            'tes'       => $tes->id,
            'percobaan' => $percobaan->id,
        ])->with('success', 'Post-test dimulai!');
    }

    public function posttestShow(Tes $tes, Request $request)
    {
        $percobaanId = (int) $request->query('percobaan');
        if (!$percobaanId) {
            return redirect()->route('dashboard.posttest.start', $tes->id)
                ->with('error', 'Pilih peserta terlebih dahulu untuk memulai post-test.');
        }

        $percobaan = Percobaan::findOrFail($percobaanId);
        if ($percobaan->tes_id !== $tes->id) abort(404);

        $duration = $tes->durasi_menit * 60;
        $elapsed = $percobaan->waktu_mulai ? now()->diffInSeconds($percobaan->waktu_mulai) : 0;
        $remaining = max($duration - $elapsed, 0);

        if ($remaining <= 0) {
            $percobaan->waktu_selesai = $percobaan->waktu_selesai ?? now();

            // Hitung skor langsung
            $jawaban = $percobaan->jawabanUser;
            $total = $jawaban->count();
            $benar = $jawaban->filter(fn($j) => $j->opsiJawaban && $j->opsiJawaban->apakah_benar)->count();
            $percobaan->skor = $total > 0 ? round(($benar / $total) * 100, 2) : 0;

            $percobaan->lulus = $percobaan->skor >= ($percobaan->tes->passing_score ?? 70);
            $percobaan->save();

            return redirect()->route('dashboard.posttest.result', ['percobaan' => $percobaan->id])
                ->with('error', 'Waktu tes sudah habis.');
        }

        $pertanyaanList = $tes->pertanyaan()->with('opsiJawaban')->get();
        $currentQuestionIndex = (int) $request->query('q', 0);
        $pertanyaan = $pertanyaanList->get($currentQuestionIndex);

        if (!$pertanyaan) {
            return redirect()->route('dashboard.posttest.result', ['percobaan' => $percobaan->id]);
        }

        return view('dashboard.pages.post-test.posttest-start', compact(
            'tes',
            'pertanyaan',
            'percobaan',
            'pertanyaanList',
            'currentQuestionIndex',
            'remaining'
        ));
    }

    public function posttestSubmit(Request $request, Percobaan $percobaan)
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
            return redirect()->route('dashboard.posttest.show', [
                'tes'       => $percobaan->tes_id,
                'percobaan' => $percobaan->id,
                'q'         => $nextQ
            ]);
        }

        $percobaan->waktu_selesai = $percobaan->waktu_selesai ?? now();

        // Hitung skor langsung
        $jawaban = $percobaan->jawabanUser;
        $total = $jawaban->count();
        $benar = $jawaban->filter(fn($j) => $j->opsiJawaban && $j->opsiJawaban->apakah_benar)->count();
        $percobaan->skor = $total > 0 ? round(($benar / $total) * 100, 2) : 0;

        $percobaan->lulus = $percobaan->skor >= ($percobaan->tes->passing_score ?? 70);
        $percobaan->save();

        return redirect()->route('dashboard.posttest.result', ['percobaan' => $percobaan->id]);
    }

    public function posttestResult(Percobaan $percobaan)
    {
        $percobaan->loadMissing(['jawabanUser.opsiJawaban', 'tes', 'peserta']);
        return view('dashboard.pages.post-test.posttest-result', compact('percobaan'));
    }

    // ======================
    // MONEV
    // ======================
    public function monev()
    {
        $tes = Tes::where('sub_tipe', 'monev')->get();
        return $tes; // (catatan: baris ini akan menghentikan eksekusi; hapus jika ingin menampilkan view)
        return view('dashboard.pages.monev.monev', compact('tes'));
    }

    public function monevStart(Tes $tes)
    {
        $pesertaId = session('peserta_id');
        if (!$pesertaId) {
            return redirect()->route('dashboard.home')->with('error', 'Silakan isi nama & sekolah terlebih dahulu.');
        }
        return redirect()->route('dashboard.monev.begin', ['tes' => $tes->id]);
    }

    public function monevBegin(Request $request, $tesId)
    {
        $pesertaId = session('peserta_id');
        if (!$pesertaId) {
            return redirect()->route('dashboard.home')->with('error', 'Silakan isi nama & sekolah terlebih dahulu.');
        }

        $percobaan = Percobaan::create([
            'peserta_id'  => $pesertaId,
            'tes_id'      => $tesId,
            'tipe'        => 'monev',
            'waktu_mulai' => now(),
        ]);

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
        $peserta = session('peserta_id');
        $pid = Peserta::findOrFail($peserta);
        $tes = Tes::where('tipe', 'survei')->where('id', 9)->first();

        return redirect()->route('survey.step', [
            'peserta' => $pid, // jika route butuh ID, ganti ke $pid->id
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
