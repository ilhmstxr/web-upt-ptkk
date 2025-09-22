<?php

namespace App\Http\Controllers;

use App\Models\JawabanUser;
use App\Models\Percobaan;
use App\Models\Peserta;
use App\Models\Tes;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class DashboardController extends Controller
{
    // ... (Semua fungsi dari index() sampai testSubmit() tetap sama persis) ...

    // ======================
    // DASHBOARD & HOME
    // ======================
    public function index(): View
    {
        $testStatus = $this->getTestStatus(session('peserta_id'));

        return view('dashboard.index', [
            'surveyStatus' => 'pending', // Anda bisa sesuaikan logika ini jika perlu
            'preTestAttempts' => $testStatus['preTestAttempts'],
            'preTestMax' => 1,
            'postTestMax' => 1,
            'monevMax' => 1,
            'postTestDone' => $testStatus['postTestDone'],
            'monevDone' => $testStatus['monevDone'],
        ]);
    }

    public function home(): View
    {
        $pesertaId = session('peserta_id');
        $pesertaAktif = $pesertaId ? Peserta::find($pesertaId) : null;
        $testStatus = $this->getTestStatus($pesertaId);

        return view('dashboard.pages.home', [
            'peserta' => Peserta::all(),
            'pesertaAktif' => $pesertaAktif,
            'preTestAttempts' => $testStatus['preTestAttempts'],
            'preTestMax' => 1,
            'postTestMax' => 1,
            'monevMax' => 1,
            'postTestDone' => $testStatus['postTestDone'],
            'monevDone' => $testStatus['monevDone'],
        ]);
    }

    // ======================
    // PROFILE & MATERI
    // ======================
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
    // SET/STORE PESERTA & LOGOUT
    // ======================
    public function setPeserta(Request $request)
    {
        // return $request;
        $Peserta = Peserta::query()
            ->join('instansi', 'instansi.id', '=', 'peserta.instansi_id')
            ->where('peserta.nama', 'LIKE', '%' . $request->input('nama') . '%')
            ->where('instansi.asal_instansi', 'LIKE', '%' . $request->input('sekolah') . '%')
            ->select('peserta.*')
            ->first();
        // return $Peserta;
        $request->validate([
            'nama' => 'required_without:peserta_id|string|max:150',
            'sekolah' => 'nullable|string|max:150',
            'peserta_id' => 'required_without:nama|exists:peserta,id',
        ]);

        $peserta = null;

        if ($request->filled('peserta_id')) {
            $peserta = Peserta::find($request->peserta_id);
        } else {
            $nama = Str::lower(trim($request->input('nama')));
            $sekolah = $request->filled('sekolah') ? Str::lower(trim($request->input('sekolah'))) : null;

            $query = Peserta::whereRaw('LOWER(nama) = ?', [$nama]);
            if ($sekolah) {
                $query->whereHas('instansi', function ($q) use ($sekolah) {
                    $q->whereRaw('LOWER(asal_instansi) = ?', [$sekolah]);
                });
            }
            $peserta = $query->first();

            // Fallback dengan pencarian LIKE jika tidak ditemukan
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
        }

        if (!$peserta) {
            return redirect()->route('dashboard.home')->with('error', 'Peserta tidak ditemukan.');
        }

        session(['peserta_id' => $peserta->id]);

        return redirect()->route('dashboard.home')->with('success', 'Selamat datang di dashboard!');
    }

    public function logout(Request $request)
    {
        $request->session()->forget('peserta_id');
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('dashboard.home')->with('success', 'Logout berhasil.');
    }


    // ================================================================
    // GENERIC TEST CONTROLLER (untuk PRE-TEST & POST-TEST)
    // $tipe bisa berisi 'pre-test' atau 'post-test'
    // ================================================================

    public function test(Request $request, $tipe)
    {
        $pesertaId = session('peserta_id');
        $peserta = Peserta::find($pesertaId);

        if (!$peserta) {
            return redirect()->route('dashboard.home')->with('error', 'Silakan pilih peserta terlebih dahulu.');
        }

        // Ambil tes berdasarkan bidang peserta
        $tes = Tes::where('bidang_id', $peserta->bidang_id)->get();

        return view("dashboard.pages.{$tipe}.{$tipe}", compact('tes', 'peserta', 'tipe'));
    }

    public function testStart($tipe, Tes $tes)
    {
        $pesertaId = session('peserta_id');
        if (!$pesertaId) {
            return redirect()->route('dashboard.home')->with('error', 'Silakan pilih peserta terlebih dahulu.');
        }

        // Langsung buat percobaan baru
        $percobaan = Percobaan::create([
            'peserta_id' => $pesertaId,
            'tes_id' => $tes->id,
            'waktu_mulai' => now(),
        ]);

        return redirect()->route("dashboard.{$tipe}.show", [
            'tipe' => $tipe,
            'tes' => $tes->id,
            'percobaan' => $percobaan->id,
        ])->with('success', Str::title(str_replace('-', ' ', $tipe)) . ' dimulai!');
    }

    public function testShow(Request $request, $tipe, Tes $tes)
    {
        $percobaanId = (int) $request->query('percobaan');
        $percobaan = Percobaan::find($percobaanId);

        // Pengecekan keamanan ini sudah benar. Peserta yang aktif
        // seharusnya hanya bisa melanjutkan tes yang ia mulai sendiri (yang punya peserta_id).
        if (!$percobaan || $percobaan->tes_id !== $tes->id || $percobaan->peserta_id !== session('peserta_id')) {
            return redirect()->route("dashboard.{$tipe}.start", ['tipe' => $tipe, 'tes' => $tes->id])
                ->with('error', 'Terjadi kesalahan atau sesi tes tidak valid.');
        }

        $remaining = $this->calculateRemainingTime($percobaan);

        if ($remaining <= 0) {
            $this->finalizeTest($percobaan);
            return redirect()->route("dashboard.{$tipe}.result", ['tipe' => $tipe, 'percobaan' => $percobaan->id])
                ->with('error', 'Waktu tes sudah habis.');
        }

        $pertanyaanList = $tes->pertanyaan()->with('opsiJawaban')->get();
        $currentQuestionIndex = (int) $request->query('q', 0);
        $pertanyaan = $pertanyaanList->get($currentQuestionIndex);

        if (!$pertanyaan) {
            $this->finalizeTest($percobaan);
            return redirect()->route("dashboard.{$tipe}.result", ['tipe' => $tipe, 'percobaan' => $percobaan->id]);
        }

        return view("dashboard.pages.{$tipe}.{$tipe}-start", compact(
            'tipe',
            'tes',
            'pertanyaan',
            'percobaan',
            'pertanyaanList',
            'currentQuestionIndex',
            'remaining'
        ));
    }

    public function testSubmit(Request $request, $tipe, Percobaan $percobaan)
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
            return redirect()->route("dashboard.{$tipe}.show", [
                'tipe' => $tipe,
                'tes' => $percobaan->tes_id,
                'percobaan' => $percobaan->id,
                'q' => $nextQ
            ]);
        }

        $this->finalizeTest($percobaan);
        return redirect()->route("dashboard.{$tipe}.result", ['tipe' => $tipe, 'percobaan' => $percobaan->id]);
    }

    public function testResult($tipe, Percobaan $percobaan)
    {
        // Pengecekan keamanan ini juga sudah benar.
        // Hanya user yang memiliki sesi yang bisa melihat hasil tesnya sendiri.
        if ($percobaan->peserta_id && $percobaan->peserta_id !== session('peserta_id')) {
            abort(403, 'Akses ditolak.');
        }

        // ===== SATU-SATUNYA PERUBAHAN ADA DI SINI =====
        // Kita muat kedua kemungkinan relasi agar accessor 'participant' efisien.
        $percobaan->loadMissing(['jawabanUser.opsiJawaban', 'tes', 'peserta', 'pesertaSurvei']);

        return view("dashboard.pages.{$tipe}.{$tipe}-result", compact('percobaan', 'tipe'));
    }


    // ======================
    // MONEV
    // ======================
    public function monev()
    {
        $tes = Tes::where('sub_tipe', 'monev')->get();
        return view('dashboard.pages.monev', compact('tes'));
    }

    public function monevStart(Tes $tes)
    {
        return view('dashboard.pages.monev.monev-start-form', compact('tes'));
    }

    public function monevBegin(Request $request, $tesId)
    {
        $pesertaId = session('peserta_id');
        if (!$pesertaId) {
            return redirect()->route('dashboard.home')->with('error', 'Silakan isi nama & sekolah terlebih dahulu.');
        }

        $percobaan = Percobaan::create([
            'peserta_id' => $pesertaId,
            'tes_id' => $tesId,
            'tipe' => 'monev',
            'waktu_mulai' => now(),
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
        return view('dashboard.pages.survey');
    }

    public function surveySubmit(Request $request)
    {
        return redirect()->route('dashboard.survey')->with('success', 'Survey berhasil dikerjakan!');
    }

    // ======================
    // PRIVATE HELPER METHODS
    // ======================

    private function getTestStatus(?int $pesertaId): array
    {
        if (!$pesertaId) {
            return ['preTestAttempts' => 0, 'postTestDone' => false, 'monevDone' => false];
        }

        return [
            'preTestAttempts' => Percobaan::where('tes_id', 1)->where('peserta_id', $pesertaId)->count(),
            'postTestDone' => Percobaan::where('tes_id', 2)->where('peserta_id', $pesertaId)->exists(),
            'monevDone' => Percobaan::where('tes_id', 3)->where('peserta_id', $pesertaId)->exists(),
        ];
    }

    private function calculateRemainingTime(Percobaan $percobaan): int
    {
        if (!$percobaan->waktu_mulai) {
            return 0;
        }
        $duration = $percobaan->tes->durasi_menit * 60;
        $elapsed = now()->diffInSeconds($percobaan->waktu_mulai);
        return max($duration - $elapsed, 0);
    }

    private function finalizeTest(Percobaan $percobaan): void
    {
        if ($percobaan->waktu_selesai) {
            return; // Sudah difinalisasi sebelumnya
        }

        $percobaan->waktu_selesai = now();

        $jawaban = $percobaan->jawabanUser()->with('opsiJawaban')->get();
        $total = $jawaban->count();
        $benar = $jawaban->filter(fn($j) => $j->opsiJawaban && $j->opsiJawaban->apakah_benar)->count();
        $percobaan->skor = $total > 0 ? round(($benar / $total) * 100, 2) : 0;
        $percobaan->lulus = $percobaan->skor >= ($percobaan->tes->passing_score ?? 70);
        $percobaan->save();
    }
}
