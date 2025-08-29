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
use App\Models\Tes;
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
        // return true;
        // Mengambil kuis yang akan dikerjakan.
        // return 'konto';
        $kuis = Tes::where('tipe', 'survei')->firstorfail();
        // return $kuis;
        return view('peserta.monev.survey.start', compact('kuis'));
    }

    /**
     * Menampilkan halaman-halaman survei secara dinamis.
     * Fungsi ini menangani logika perpindahan halaman dan pengumpulan data.
     */
    public function create(Request $request)
    {
        // 1. Validasi data pendaftar di halaman pertama (tetap diperlukan)
        $request->validate([
            'email' => 'required|email',
            'nama' => 'required|string',
        ]);

        // PERUBAHAN: Blok untuk mengecek data peserta ke database dihapus.
        // Data nama dan email dari request akan langsung digunakan.

        // 2. Mengumpulkan dan menggabungkan jawaban dari halaman sebelumnya
        $allAnswers = json_decode($request->input('all_answers', '[]'), true);
        $newAnswers = $request->input('answers', []);
        $allAnswers = array_merge($allAnswers, $newAnswers);

        // 3. Menentukan halaman berikutnya
        $kuis = Tes::where('tipe', 'survei')->firstOrFail();
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

            // PERUBAHAN: Buat objek sementara untuk data peserta agar
            // variabel di view tidak perlu diubah.
            $pesertaData = new \stdClass();
            $pesertaData->nama = $request->input('nama');
            $pesertaData->email = $request->input('email');

            $section = $kuis;
            $section->order = $nextPage;

            return view('peserta.kuis.step', [
                'section' => $section,
                'peserta' => $pesertaData, // Kirim objek sementara ke view
                'questions' => $questions,
                'all_answers_json' => json_encode($allAnswers),
            ]);
        } else {
            // Jika tidak ada lagi pertanyaan, ini adalah halaman terakhir.
            // Siapkan data final untuk disimpan.
            $finalData = [
                // PERUBAHAN: Ambil nama dan email langsung dari request,
                // bukan dari objek model Peserta.
                'nama' => $request->input('nama'),
                'email' => $request->input('email'),
                'kuis_id' => $kuis->id,
                'answers' => $allAnswers,
                'comments' => $request->input('comments'),
            ];

            // Panggil fungsi store untuk menyimpan semuanya
            return $this->store(new Request($finalData));
        }
    }


    public function search(Request $request)
    {
        // Ambil query pencarian dari request, contoh: /api/peserta/search?nama=bud
        $query = $request->input('nama');

        // Lakukan pencarian di database menggunakan 'LIKE'
        $peserta = Peserta::where('nama', 'LIKE', "%{$query}%")
            ->limit(10) // Batasi hasilnya agar tidak terlalu banyak
            ->get(['id', 'nama']); // Ambil kolom id dan nama saja

        // Kembalikan hasilnya dalam format JSON
        return response()->json($peserta);
    }

    public function start(Request $request)
    {
        // return $request;
        $validated = $request->validate([
            'email'   => 'required|email',
            'nama' => 'required|string',
            'kuis_id' => 'required|integer|exists:kuis,id'
        ]);

        // Ambil nama lengkap dari input yang sudah divalidasi
        $namaLengkap = $validated['nama'];

        // 1. Pecah string nama menjadi array berisi kata-kata kunci
        $keywords = explode(' ', $namaLengkap);

        // 2. Lakukan pencarian dengan query builder dinamis
        $peserta = Peserta::query()
            ->where(function ($query) use ($keywords) {
                // 3. Loop setiap kata kunci dan cari menggunakan OR WHERE
                foreach ($keywords as $keyword) {
                    // Gunakan orWhereRaw untuk pencarian case-insensitive (LOWER) 
                    // dan pencarian parsial (LIKE)
                    $query->orWhereRaw('LOWER(nama) LIKE ?', ['%' . strtolower($keyword) . '%']);
                }
            })
            ->first(); // Ambil data pertama yang cocok

        // return $findPeserta;
        return redirect()->route('survey.show', [
            'email' => $validated['email'], // Menggunakan email peserta->id,           // Menggunakan ID peserta
            'nama' => $validated['nama'],
            'order' => $validated['kuis_id'],
            'peserta' => $peserta->id,
        ]);
    }

    // app/Http/Controllers/SurveyController.php

    // Laravel akan otomatis mencari Peserta berdasarkan ID yang ada di URL
    public function show(Peserta $peserta, $order, Request $request)
    {
        $kuisId = $order;

        $section = Tes::findOrFail($kuisId);
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
        $validatedData = $request->validate([
            'nama'      => 'required|string',
            'email'     => 'required|email',
            'kuis_id'   => 'required|integer|exists:kuis,id',
            'answers'   => 'required|array',
            'answers.*' => 'required',
            'comments'  => 'nullable|string',
        ]);

        // Buat percobaan tanpa foreign key peserta
        $percobaan = Percobaan::create([
            'nama'          => $validatedData['nama'],
            'email'         => $validatedData['email'],
            'kuis_id'       => $validatedData['kuis_id'],
            'waktu_mulai'   => now(),
            'waktu_selesai' => now(),
            'pesan_kesan'   => $validatedData['comments'] ?? null,
        ]);

        $questions = Pertanyaan::where('kuis_id', $validatedData['kuis_id'])->get()->keyBy('id');
        $questionIds = $questions->pluck('id');

        $templateIds = PivotJawaban::whereIn('pertanyaan_id', $questionIds)
            ->pluck('template_pertanyaan_id');

        $allRelevantQuestionIds = $questionIds->merge($templateIds)->unique();
        $allOptions = OpsiJawaban::whereIn('pertanyaan_id', $allRelevantQuestionIds)->get()->keyBy('id');

        $jawabanUntukDisimpan = [];
        foreach ($validatedData['answers'] as $pertanyaanId => $jawabanValue) {
            $question = $questions->get($pertanyaanId);
            if (!$question) continue;

            $dataJawaban = [
                'percobaan_id'    => $percobaan->id,
                'pertanyaan_id'   => $pertanyaanId,
                'opsi_jawaban_id' => null,
                'nilai_jawaban'   => null,
                'jawaban_teks'    => null,
                'created_at'      => now(),
                'updated_at'      => now(),
            ];

            if ($question->tipe_jawaban === 'teks_bebas') {
                $dataJawaban['jawaban_teks'] = $jawabanValue;
            } else {
                $selectedOption = $allOptions->get($jawabanValue);
                if ($selectedOption) {
                    $dataJawaban['opsi_jawaban_id'] = $selectedOption->id;
                    $dataJawaban['nilai_jawaban']   = $selectedOption->nilai;
                }
            }

            $jawabanUntukDisimpan[] = $dataJawaban;
        }

        if (!empty($jawabanUntukDisimpan)) {
            JawabanUser::insert($jawabanUntukDisimpan);
        }

        return redirect()->route('survey.complete')
            ->with('success', 'Terima kasih, survei Anda berhasil disimpan.');
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
    // public function checkCredentials(Request $request)
    // {
    //     $exists = Peserta::where('email', $request->email)
    //         ->where('nama', $request->nama)
    //         ->exists();

    //     return response()->json(['exists' => $exists]);
    // }
}
