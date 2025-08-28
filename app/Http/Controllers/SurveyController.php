<?php

namespace App\Http\Controllers;

use App\Http\Requests\StartSurveyRequest;
use App\Http\Requests\StoreParticipantRequest;
use App\Http\Requests\StoreSurveyRequest;
use App\Models\Survey;
use App\Models\Jawaban;
use App\Models\JawabanUser;
use App\Models\Kuis;
use App\Models\OpsiJawaban;
use App\Models\Percobaan;
use App\Models\Pertanyaan;
use App\Models\Peserta;
use App\Models\PivotJawaban;
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
        $kuis = Kuis::where('tipe', 'survei')->firstorfail();
        // return $kuis;
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

    public function start(Request $request)
    {
        // return $request;
        $validated = $request->validate([
            'email'   => 'required|email|exists:pesertas,email',
            'kuis_id' => 'required|integer|exists:kuis,id'
        ]);

        $peserta = Peserta::where('email', $validated['email'])->first();

        // return $request;
        // PERBAIKAN DI SINI:
        // Kirimkan parameter 'peserta' dan 'order' sesuai yang diminta route
        return redirect()->route('survey.show', [
            'peserta' => $peserta->id,           // Menggunakan ID peserta
            // 'order'   => 1,   // Asumsi 'order' adalah ID kuis
            // 'kuis_id' => $validated['kuis_id']
            'order' => $validated['kuis_id']
        ]);
    }

    // app/Http/Controllers/SurveyController.php

    // Laravel akan otomatis mencari Peserta berdasarkan ID yang ada di URL
    public function show(Peserta $peserta, $order, Request $request)
    {
        $kuisId = $order;
        // return $request;s
        // Langkah 1 & 2: Ambil data Kuis yang akan kita sebut sebagai 'section'
        $section = Kuis::findOrFail($kuisId);
        // return $section;

        // Langkah 3: Ambil semua pertanyaan yang terkait, diurutkan berdasarkan nomor
        $questions = Pertanyaan::where('kuis_id', $section->id)
            ->with([
                'opsiJawabans', // Untuk pertanyaan yang punya opsi sendiri
                'opsiLink.templatePertanyaan.opsiJawabans' // Untuk pertanyaan yang mencontek dari template
            ])
            ->orderBy('nomor', 'asc')
            ->get();

        // return $questions;

        // Langkah 4: Tampilkan view dengan data yang sudah disiapkan
        return view('peserta.monev.survey.step', [
            'peserta'   => $peserta,
            'section'   => $section,
            'questions' => $questions,
        ]);
    }

    //   public function show(Peserta $peserta, Kuis $kuis, $page = 1)
    // {
    //     // 1. Ambil SEMUA pertanyaan untuk kuis ini sekali saja dari database
    //     $allQuestions = Pertanyaan::where('kuis_id', $kuis->id)->orderBy('nomor')->get();

    //     // 2. Kelompokkan pertanyaan menjadi beberapa halaman
    //     $pages = new Collection();
    //     $currentPageQuestions = new Collection();

    //     foreach ($allQuestions as $question) {
    //         $currentPageQuestions->push($question);
    //         if ($question->tipe_jawaban === 'teks_bebas') {
    //             $pages->push($currentPageQuestions);
    //             $currentPageQuestions = new Collection();
    //         }
    //     }

    //     // 3. Jangan lupa masukkan sisa pertanyaan terakhir jika ada
    //     if ($currentPageQuestions->isNotEmpty()) {
    //         $pages->push($currentPageQuestions);
    //     }

    //     // 4. Ambil data untuk halaman saat ini (array index dimulai dari 0)
    //     $questionForCurrentPage = $pages->get($page - 1);

    //     // Jika user mencoba mengakses halaman yang tidak ada, arahkan ke halaman selesai
    //     if (!$questionForCurrentPage) {
    //         return redirect()->route('survey.complete'); // Buat route ini nanti
    //     }

    //     return view('peserta.monev.survey.step', [
    //         'peserta'     => $peserta,
    //         'kuis'        => $kuis,
    //         'questions'   => $questionForCurrentPage,
    //         'currentPage' => $page,
    //         'totalPages'  => $pages->count(),
    //     ]);
    // }


    /**
     * Menyimpan semua data yang terkumpul ke database.
     * Fungsi ini hanya dipanggil satu kali di akhir.
     */
    // public function store(Request $request)
    // {
    //     // return $request;
    //     $peserta = Peserta::where('email', $request->email)->firstOrFail();
    //     $kuisId = $request->input('kuis_id');
    //     $allAnswers = $request->input('answers', []);

    //     // Memulai transaksi database untuk memastikan semua data tersimpan
    //     $percobaan = DB::transaction(function () use ($peserta, $kuisId, $allAnswers, $request) {
    //         // Buat record percobaan
    //         $percobaan = Percobaan::create([
    //             'peserta_id' => $peserta->id,
    //             'kuis_id' => $kuisId,
    //             'waktu_mulai' => now(), // Bisa disesuaikan jika waktu mulai dicatat lebih awal
    //             'waktu_selesai' => now(),
    //             'pesan_kesan' => $request->input('comments'),
    //         ]);

    //         // Simpan setiap jawaban
    //         foreach ($allAnswers as $pertanyaanId => $answerValue) {
    //             JawabanUser::create([
    //                 'percobaan_id' => $percobaan->id,
    //                 'pertanyaan_id' => $pertanyaanId,
    //                 'nilai_jawaban' => $answerValue,
    //             ]);
    //         }

    //         return $percobaan;
    //     });

    //     // Arahkan ke halaman selesai
    //     return redirect()->route('kuis.complete');
    // }

    public function store(Request $request)
    {
        // return $request;
        // 1. Validasi semua data yang masuk
        $validatedData = $request->validate([
            'peserta_id' => 'required|integer|exists:pesertas,id',
            'kuis_id'    => 'required|integer|exists:kuis,id',
            'answers'    => 'required|array',
            'answers.*'  => 'required', // Setiap jawaban harus diisi
            'comments'   => 'nullable|string', // Validasi untuk kesan & pesan
        ]);

        // return $validatedData;

        // try {
        // Ambil semua pertanyaan terkait sekali saja untuk efisiensi
        $questions = Pertanyaan::where('kuis_id', $validatedData['kuis_id'])
            ->get()
            ->keyBy('id'); // Jadikan ID sebagai key untuk pencarian cepat

        // return $questions;
        // 2. Gunakan transaksi untuk memastikan semua data aman tersimpan
        // Buat record percobaan (attempt)
        $percobaan = Percobaan::create([
            'peserta_id'    => $validatedData['peserta_id'],
            'kuis_id'       => $validatedData['kuis_id'],
            'waktu_mulai'   => now(),
            'waktu_selesai' => now(),
            'pesan_kesan'   => $validatedData['comments'] ?? null,
        ]);
        // Ambil semua pertanyaan untuk kuis ini
        $questions = Pertanyaan::where('kuis_id', $validatedData['kuis_id'])->get()->keyBy('id');
        $questionIds = $questions->pluck('id');

        // Ambil semua ID template yang mungkin digunakan oleh pertanyaan-pertanyaan ini
        $templateIds = PivotJawaban::whereIn('pertanyaan_id', $questionIds)
            ->pluck('template_pertanyaan_id');

        // Gabungkan semua ID pertanyaan (asli dan template) untuk mengambil semua opsi jawaban yang relevan sekaligus
        $allRelevantQuestionIds = $questionIds->merge($templateIds)->unique();
        $allOptions = OpsiJawaban::whereIn('pertanyaan_id', $allRelevantQuestionIds)
            ->get()
            ->keyBy('id'); // Jadikan ID opsi sebagai kunci untuk pencarian cepat

        // Buat record percobaan (attempt)
        $percobaan = Percobaan::create([
            'peserta_id'    => $validatedData['peserta_id'],
            'kuis_id'       => $validatedData['kuis_id'],
            'waktu_mulai'   => now(),
            'waktu_selesai' => now(),
            'pesan_kesan'   => $validatedData['comments'] ?? null,
        ]);

        $jawabanUntukDisimpan = [];
        foreach ($validatedData['answers'] as $pertanyaanId => $jawabanValue) {
            $question = $questions->get($pertanyaanId);
            if (!$question) continue;

            // REVISI: Inisialisasi array dengan semua kolom nullable
            // Ini memastikan setiap baris memiliki struktur yang konsisten.
            $dataJawaban = [
                'percobaan_id'      => $percobaan->id,
                'pertanyaan_id'     => $pertanyaanId,
                'opsi_jawaban_id'   => null,
                'nilai_jawaban'     => null,
                'jawaban_teks'      => null,
                'created_at'        => now(),
                'updated_at'        => now(),
            ];

            if ($question->tipe_jawaban === 'teks_bebas') {
                // Jika tipe adalah teks bebas, isi kolom jawaban_teks
                $dataJawaban['jawaban_teks'] = $jawabanValue;
            } else {
                // Jika bukan teks bebas, maka $jawabanValue adalah ID Opsi Jawaban
                $selectedOption = $allOptions->get($jawabanValue);

                if ($selectedOption) {
                    // Isi kolom opsi_jawaban_id dan nilai_jawaban
                    $dataJawaban['opsi_jawaban_id'] = $selectedOption->id;
                    $dataJawaban['nilai_jawaban'] = $selectedOption->nilai; // nilai akan tetap null jika tidak ada
                }
            }

            $jawabanUntukDisimpan[] = $dataJawaban;
        }

        // return $jawabanUntukDisimpan;

        if (!empty($jawabanUntukDisimpan)) {
            JawabanUser::insert($jawabanUntukDisimpan);
        }
        //     DB::transaction(function () use ($validatedData, $questions) {
        //   });



        // 3. Arahkan ke halaman selesai jika berhasil
        return redirect()->route('survey.complete')->with('success', 'Terima kasih, survei Anda berhasil disimpan.');
        // } catch (\Exception $e) {
        // Jika terjadi error, kembalikan ke halaman sebelumnya dengan pesan error
        return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        // }
    }



    /**
     * Menampilkan halaman "Terima Kasih".
     */
    public function complete()
    {
        return view('peserta.monev.survey.complete');
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
