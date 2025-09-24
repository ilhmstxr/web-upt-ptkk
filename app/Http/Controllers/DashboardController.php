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
use App\Models\PesertaSurvei;




class DashboardController extends Controller
{


    // ======================
    // DASHBOARD INDEX
    // ======================
    public function index(): View
    {
        $pesertaSurveiId = session('pesertaSurvei_id');

        $surveyStatus = 'pending';

        $preTestAttempts = Percobaan::where('tes_id', 1)
            ->where('pesertaSurvei_id', $pesertaSurveiId)
            ->count();

        $preTestMax = 1;
        $postTestMax = 1;
        $monevMax    = 1;

        $postTestDone = Percobaan::where('tes_id', 2)
            ->where('pesertaSurvei_id', $pesertaSurveiId)
            ->exists();

        $monevDone = Percobaan::where('tes_id', 3)
            ->where('pesertaSurvei_id', $pesertaSurveiId)
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

        // return $pesertaAktif;
        // gunakan pesertaSurvei_id untuk percobaan
        $pesertaSurveiId = session('pesertaSurvei_id');

        $preTestAttempts = Percobaan::where('tes_id', 1)
            ->where('pesertaSurvei_id', $pesertaSurveiId)
            ->count();

        $preTestMax = 1;
        $postTestMax = 1;
        $monevMax    = 1;

        $postTestDone = Percobaan::where('tes_id', 2)
            ->where('pesertaSurvei_id', $pesertaSurveiId)
            ->exists();

        $monevDone = Percobaan::where('tes_id', 3)
            ->where('pesertaSurvei_id', $pesertaSurveiId)
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
    // - mendukung dua mode:
    //   1) memilih peserta existing via peserta_id (select)
    //   2) menulis manual nama + sekolah (overlay) -> melakukan pencarian case-insensitive pada tabel peserta dan relasi instansi
    // Setelah ketemu: menyimpan session('peserta_id') = peserta->id dan redirect ke dashboard.home
    // ======================
    public function setPeserta(Request $request)
    {
        $request->validate([
            'nama'    => 'required|string|max:150',
            'sekolah' => 'nullable|string|max:150',
            'peserta_id' => 'nullable|exists:peserta,id', // ID dari tabel peserta (opsional)
        ]);

        // --- Jika langsung pilih peserta_id (misal dari dropdown) ---
        if ($request->filled('peserta_id')) {
            $peserta = Peserta::findOrFail($request->peserta_id);

            // buat atau ambil peserta_survei (pakai nama + sekolah atau email, bukan peserta_id)
            $pesertaSurvei = PesertaSurvei::firstOrCreate([
                'nama'    => $peserta->nama,
                'email'   => $peserta->email,
                'angkatan' => $peserta->angkatan ?? null,
            ]);

            session([
                'peserta_id'       => $peserta->id,
                'pesertaSurvei_id' => $pesertaSurvei->id,
            ]);
        }

        // --- Cari peserta berdasarkan nama + sekolah ---
        $nama    = Str::lower(trim($request->input('nama')));
        $sekolah = $request->filled('sekolah') ? Str::lower(trim($request->input('sekolah'))) : null;

        $query = Peserta::whereRaw('LOWER(nama) = ?', [$nama]);
        if ($sekolah) {
            $query->whereHas('instansi', function ($q) use ($sekolah) {
                $q->whereRaw('LOWER(asal_instansi) = ?', [$sekolah]);
            });
        }
        $peserta = $query->first();

        // --- Fallback LIKE search ---
        if (!$peserta) {
            $likeNama = '%' . str_replace(' ', '%', $nama) . '%';
            $query = Peserta::whereRaw('LOWER(nama) LIKE ?', [$likeNama]);
            if ($sekolah) {
                $likeSek = '%' . str_replace(' ', '%', $sekolah) . '%';
                $query->whereHas('instansi', function ($q) use ($likeSek) {
                    $q->whereRaw('LOWER(asal_instansi) LIKE ?', [$likeSek]);
                });
            }
            $peserta = $query->first();
        }

        if (!$peserta) {
            return redirect()->route('dashboard.home')
                ->with('error', 'Peserta tidak ditemukan.');
        }

        // --- Buat peserta_survei kalau belum ada (pakai nama/email) ---
        $pesertaSurvei = PesertaSurvei::firstOrCreate([
            'nama'    => $peserta->nama,
        ]);

        // Simpan id peserta + peserta_survei ke session
        session([
            'peserta_id'       => $peserta->id,
            'pesertaSurvei_id' => $pesertaSurvei->id,
        ]);

        return redirect()->route('dashboard.home')->with('success', 'Selamat masuk ke dashboard!');
    }




    // ======================
    // Logout - hapus session
    // ======================
    public function logout(Request $request)
    {
        $request->session()->forget(['peserta_id', 'pesertaSurvei_id']);
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
        $pesertaSurveiId = session('pesertaSurvei_id');
        if (!$pesertaSurveiId) {
            return redirect()->route('dashboard.home')->with('error', 'Silakan pilih peserta terlebih dahulu.');
        }

        // Langsung buat percobaan baru
        $percobaan = Percobaan::create([
            'pesertaSurvei_id' => $pesertaSurveiId,
            'tes_id'           => $tes->id,
            'waktu_mulai'      => now(),
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
        $percobaan->loadMissing(['jawabanUser.opsiJawaban', 'tes', 'pesertaSurvei']);
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
        $pesertaSurveiId = session('pesertaSurvei_id');
        if (!$pesertaSurveiId) {
            return redirect()->route('dashboard.home')->with('error', 'Silakan pilih peserta terlebih dahulu.');
        }

        // Langsung buat percobaan baru
        $percobaan = Percobaan::create([
            'pesertaSurvei_id' => $pesertaSurveiId,
            'tes_id'           => $tes->id,
            'waktu_mulai'      => now(),
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
        $percobaan->loadMissing(['jawabanUser.opsiJawaban', 'tes', 'pesertaSurvei']);
        return view('dashboard.pages.post-test.posttest-result', compact('percobaan'));
    }


    // ======================
    // MONEV
    // ======================
    public function monev()
    {
        // return true;
        $tes = Tes::where('sub_tipe', 'monev')->get();
        return $tes;
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
            'pesertaSurvei_id' => session('pesertaSurvei_id'),
            'tes_id'           => $tesId,
            'tipe'             => 'monev',
            'waktu_mulai'      => now(),
        ]);

        return redirect()->route('dashboard.monev.show', [
            'tes' => $tesId,
            'percobaan' => $percobaan->id,
        ]);
    }

    // ======================
    // SURVEY
    // ======================   
    public function survey()
    {
        // return true;
        $peserta = session('peserta_id');
        $pid = Peserta::findorFail($peserta);
        // return $p;
        // return $peserta;
        $tes = Tes::where('tipe', 'survei')->where('id', 9)->first();
        // return $tes;
        return redirect()->route('survey.step', [
            'peserta' => $pid,
            'order' => $tes->id
        ]);
        // return view('dashboard.pages.survey');
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
