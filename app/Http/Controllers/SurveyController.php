<?php

namespace App\Http\Controllers;

use App\Http\Requests\StartSurveyRequest;
use App\Http\Requests\StoreParticipantRequest;
use App\Http\Requests\StoreSurveyRequest;
use App\Models\Survey;
use App\Models\Jawaban;
use App\Models\JawabanUser;
use App\Models\Kuis;
use App\Models\Percobaan;
use App\Models\Peserta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SurveyController extends Controller
{
    /**
     * Menampilkan halaman awal (form pendaftaran).
     * Ini adalah gerbang masuk utama.
     */
    public function index()
    {
        // Mengambil kuis yang akan dikerjakan.
        $kuis = Kuis::where('tipe', 'survei')->firstOrFail();
        return view('peserta.monev.survey.start', compact('kuis'));
    }

    /**
     * Menampilkan halaman-halaman survei secara dinamis.
     * Fungsi ini menangani logika perpindahan halaman dan pengumpulan data.
     */
    public function create(Request $request)
    {
        // 1. Validasi data pendaftar di halaman pertama
        $request->validate([
            'email' => 'required|email',
            'nama' => 'required|string',
        ]);

        $peserta = Peserta::where('email', $request->email)
            ->where('nama', $request->nama)
            ->first();

        if (!$peserta) {
            return redirect()->route('kuis.index')->withErrors(['message' => 'Kombinasi nama dan email tidak ditemukan.'])->withInput();
        }

        // 2. Mengumpulkan dan menggabungkan jawaban dari halaman sebelumnya
        $allAnswers = json_decode($request->input('all_answers', '[]'), true);
        $newAnswers = $request->input('answers', []);
        $allAnswers = array_merge($allAnswers, $newAnswers);

        // 3. Menentukan halaman berikutnya
        $kuis = Kuis::where('tipe', 'survei')->firstOrFail();
        $currentPage = $request->input('page', 0);
        $nextPage = $currentPage + 1;

        // 4. Mengambil pertanyaan untuk halaman berikutnya
        $itemsPerPage = 15;
        $questions = $kuis->pertanyaan()
            ->orderBy('nomor_urut', 'asc')
            ->paginate($itemsPerPage, ['*'], 'page', $nextPage);

        // 5. Logika Navigasi
        if ($questions->isNotEmpty()) {
            // Jika masih ada pertanyaan, tampilkan halaman berikutnya
            $section = $kuis;
            $section->order = $nextPage;

            return view('peserta.kuis.step', [
                'section' => $section,
                'peserta' => $peserta,
                'questions' => $questions,
                'all_answers_json' => json_encode($allAnswers), // Kirim data yang sudah terkumpul
            ]);
        } else {
            // Jika tidak ada lagi pertanyaan, ini adalah halaman terakhir.
            // Siapkan data final untuk disimpan.
            $finalData = [
                'nama' => $peserta->nama,
                'email' => $peserta->email,
                'kuis_id' => $kuis->id,
                'answers' => $allAnswers,
                'comments' => $request->input('comments'),
            ];

            // Panggil fungsi store untuk menyimpan semuanya
            return $this->store(new Request($finalData));
        }
    }


    /**
     * Menyimpan semua data yang terkumpul ke database.
     * Fungsi ini hanya dipanggil satu kali di akhir.
     */
    public function store(Request $request)
    {
        $peserta = Peserta::where('email', $request->email)->firstOrFail();
        $kuisId = $request->input('kuis_id');
        $allAnswers = $request->input('answers', []);

        // Memulai transaksi database untuk memastikan semua data tersimpan
        $percobaan = DB::transaction(function () use ($peserta, $kuisId, $allAnswers, $request) {
            // Buat record percobaan
            $percobaan = Percobaan::create([
                'peserta_id' => $peserta->id,
                'kuis_id' => $kuisId,
                'waktu_mulai' => now(), // Bisa disesuaikan jika waktu mulai dicatat lebih awal
                'waktu_selesai' => now(),
                'pesan_kesan' => $request->input('comments'),
            ]);

            // Simpan setiap jawaban
            foreach ($allAnswers as $pertanyaanId => $answerValue) {
                JawabanUser::create([
                    'percobaan_id' => $percobaan->id,
                    'pertanyaan_id' => $pertanyaanId,
                    'nilai_jawaban' => $answerValue,
                ]);
            }

            return $percobaan;
        });

        // Arahkan ke halaman selesai
        return redirect()->route('kuis.complete');
    }


    /**
     * Menampilkan halaman "Terima Kasih".
     */
    public function complete()
    {
        return view('peserta.kuis.complete');
    }

    /**
     * Fungsi untuk validasi AJAX dari halaman start.
     */
    public function checkCredentials(Request $request)
    {
        $exists = Peserta::where('email', $request->email)
            ->where('nama', $request->nama)
            ->exists();

        return response()->json(['exists' => $exists]);
    }
}
