<?php

namespace App\Http\Controllers;

use App\Http\Requests\StartSurveiRequest;
use App\Http\Requests\StoreParticipantRequest;
use App\Http\Requests\StoreSurveiRequest;
use App\Models\Kompetensi;
use App\Models\Survei;
use App\Models\Jawaban;
use App\Models\JawabanUser;
use App\Models\Kuis;
use App\Models\OpsiJawaban;
use App\Models\Pelatihan;
use App\Models\Percobaan;
use App\Models\Pertanyaan;
use App\Models\Peserta;
use App\Models\PesertaSurvei;
use App\Models\PivotJawaban;
use App\Models\Tes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SurveiController extends Controller
{
    /**
     * Menampilkan halaman awal (form pendaftaran).
     * Ini adalah gerbang masuk utama.
     */
    public function index()
    {
        // return true;
        // Mengambil tes yang akan dikerjakan.
        // return 'konto';
        $tes = Tes::where('tipe', 'survei')->firstorfail();
        $pelatihan = Pelatihan::all();
        $kompetensi = Kompetensi::all();
        // return $kompetensi;
        // return $tes;
        return view('peserta.monev.survei.start', compact('tes', 'pelatihan', 'kompetensi'));
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
        $tes = Tes::where('tipe', 'survei')->firstOrFail();
        $currentPage = $request->input('page', 0);
        $nextPage = $currentPage + 1;

        // 4. Mengambil pertanyaan untuk halaman berikutnya
        $itemsPerPage = 15;
        $questions = $tes->pertanyaan()
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

            $section = $tes;
            $section->order = $nextPage;

            return view('peserta.tes.step', [
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
                'tes_id' => $tes->id,
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
        // $query = $request->input('nama');

        // // Lakukan pencarian di database menggunakan 'LIKE'
        // $peserta = Peserta::where('nama', 'LIKE', "%{$query}%")
        //     ->limit(10) // Batasi hasilnya agar tidak terlalu banyak
        //     ->get(['id', 'nama']); // Ambil kolom id dan nama saja

        // // Kembalikan hasilnya dalam format JSON
        // return response()->json($peserta);
    }

    // public function start(Request $request)
    public function start(Peserta $Peserta)
    {
        // return $request;
        // 1. Validasi input
        // 1. Validasi input
        // $validated = $request->validate([
        //     'email'        => 'required|email',
        //     'nama'         => 'required|string',
        //     'angkatan'         => 'required|string',
        //     'pelatihan_id' => 'required|integer|exists:pelatihan,id', // Sebaiknya integer & exists
        //     'kompetensi_id' => 'required|integer|exists:kompetensi,id',   // Ganti nama & validasi
        //     'tes_id'       => 'required|integer|exists:tes,id'
        // ]);

        // $peserta = PesertaSurvei::updateOrCreate(
        //     // Array 1: Kunci unik untuk mencari data
        //     [
        //         'email' => $validated['email']
        //     ],
        //     // Array 2: Data yang akan di-update atau dibuat
        //     [
        //         'nama'         => $validated['nama'],
        //         'angkatan'         => $validated['angkatan'],
        //         'pelatihan_id' => $validated['pelatihan_id'],
        //         'kompetensi_id'    => $validated['kompetensi_id'] // Sesuaikan dengan nama dari form
        //     ]
        // );
        // REVISI DI SINI
        // IMPROVE: untuk menyimpan datanya di


        // 4. Redirect ke route 'survei.show' dengan parameter yang sesuai
        //    Hanya kirim 'peserta' dan 'order' karena hanya itu yang ada di URI route.
        // return redirect()->route('survei.show', [
        //     'peserta' => $peserta->id,
        //     'order'   => $validated['tes_id'],
        // 'peserta' => $peserta->id,
        // 'order'   => $validated['tes_id'],
        // ]);
    }
    // app/Http/Controllers/SurveiController.php

    // Laravel akan otomatis mencari Peserta berdasarkan ID yang ada di URL
    // public function show(PesertaSurvei $peserta, $order, Request $request)
    public function show(Peserta $peserta, $order, Request $request)
    {
        $tesId = $order;

        $section = Tes::findOrFail($tesId);
        $questions = Pertanyaan::where('tes_id', $section->id)
            ->with(['opsiJawabans', 'templates.opsiJawabans'])
            ->orderBy('nomor', 'asc')
            ->get();


        $arrayCustom = ["Pendapat Tentang Penyelenggaran Pelatihan", "Persepsi Terhadap Program Pelatihan", "Penilaian Terhadap Instruktur"];

        // Proses untuk mengelompokkan pertanyaan
        $groupedQuestions = [];
        $groupKey = 1; // Kunci grup dimulai dari 1
        $tempGroup = [];

        foreach ($questions as $question) {
            $tempGroup[] = $question;

            if ($question->tipe_jawaban === 'teks_bebas' && str_starts_with(strtolower($question->teks_pertanyaan), 'pesan dan kesan')) {
                // Tentukan index untuk array custom (groupKey 1 -> index 0, dst.)
                $categoryIndex = $groupKey - 1;

                // Ambil kategori dari array custom sesuai urutan.
                // Gunakan isset() untuk mencegah error jika grup lebih banyak dari kategori.
                $category = isset($arrayCustom[$categoryIndex]) ? $arrayCustom[$categoryIndex] : null;

                $groupedQuestions[$groupKey] = [
                    'pertanyaan' => $tempGroup,
                    'kategori'   => $category, // Simpan satu elemen kategori
                ];

                $tempGroup = [];
                $groupKey++;
            }
        }

        // Lakukan hal yang sama untuk sisa pertanyaan terakhir
        if (!empty($tempGroup)) {
            $categoryIndex = $groupKey - 1;
            $category = isset($arrayCustom[$categoryIndex]) ? $arrayCustom[$categoryIndex] : null;

            $groupedQuestions[$groupKey] = [
                'pertanyaan' => $tempGroup,
                'kategori'   => $category,
            ];
        }

        // return $questions;
        // return $groupedQuestions;
        // Langkah 4: Tampilkan view dengan data yang sudah disiapkan
        return view('peserta.monev.survei.step', [
            'peserta'   => $peserta,
            'section'   => $section,
            // 'questions' => $questions,
            'groupedQuestions' => $groupedQuestions,
        ]);
    }

    //   public function show(Peserta $peserta, tes $tes, $page = 1)
    // {
    //     // 1. Ambil SEMUA pertanyaan untuk tes ini sekali saja dari database
    //     $allQuestions = Pertanyaan::where('tes_id', $tes->id)->orderBy('nomor')->get();

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
    //         return redirect()->route('survei.complete'); // Buat route ini nanti
    //     }

    //     return view('peserta.monev.survei.step', [
    //         'peserta'     => $peserta,
    //         'tes'        => $tes,
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
    //     $tesId = $request->input('tes_id');
    //     $allAnswers = $request->input('answers', []);

    //     // Memulai transaksi database untuk memastikan semua data tersimpan
    //     $percobaan = DB::transaction(function () use ($peserta, $tesId, $allAnswers, $request) {
    //         // Buat record percobaan
    //         $percobaan = Percobaan::create([
    //             'peserta_id' => $peserta->id,
    //             'tes_id' => $tesId,
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
    //     return redirect()->route('tes.complete');
    // }

    public function store(Request $request)
    {
        // 1. Validasi semua data yang masuk
        $validatedData = $request->validate([
            'peserta_id' => 'required|integer|exists:peserta,id',
            'tes_id'     => 'required|integer|exists:tes,id',
            'answers'    => 'required|array',
            'answers.*'  => 'required',
            'comments'   => 'nullable|string',
        ]);

        try {
            // Mulai transaksi database untuk memastikan konsistensi data
            DB::beginTransaction();

            // 2. Buat record percobaan (attempt)
            // PERUBAHAN: Menyimpan ke 'peserta_id' dan men-null-kan 'peserta_id'
            $percobaan = Percobaan::create([
                'peserta_id'         => $validatedData['peserta_id'],
                'pesertaSurvei_id'   => null, // Eksplisit diatur menjadi null sesuai permintaan
                'tes_id'             => $validatedData['tes_id'],
                'waktu_mulai'        => now(), // Sebaiknya ada waktu mulai yang sebenarnya dari frontend
                'waktu_selesai'      => now(),
                'pesan_kesan'        => $validatedData['comments'] ?? null,
            ]);

            // 3. Ambil semua data yang dibutuhkan sekali saja untuk efisiensi
            $questions = Pertanyaan::where('tes_id', $validatedData['tes_id'])->get()->keyBy('id');
            $questionIds = $questions->pluck('id');

            // Ambil ID template jika ada
            $templateIds = PivotJawaban::whereIn('pertanyaan_id', $questionIds)
                ->pluck('template_pertanyaan_id');

            $allRelevantQuestionIds = $questionIds->merge($templateIds)->unique();

            // Ambil semua opsi jawaban yang relevan
            $allOptions = OpsiJawaban::whereIn('pertanyaan_id', $allRelevantQuestionIds)
                ->get()
                ->keyBy('id');


            // 4. Siapkan array jawaban untuk disimpan secara massal (bulk insert)
            $jawabanUntukDisimpan = [];
            foreach ($validatedData['answers'] as $pertanyaanId => $jawabanValue) {
                $question = $questions->get($pertanyaanId);
                if (!$question) {
                    continue; // Lewati jika ID pertanyaan tidak valid untuk tes ini
                }

                $dataJawaban = [
                    'opsi_jawaban_id' => null,
                    'percobaan_id'    => $percobaan->id,
                    'pertanyaan_id'   => $pertanyaanId,
                    'nilai_jawaban'   => null,
                    'jawaban_teks'    => null,
                    'created_at'      => now(),
                    'updated_at'      => now(),
                ];

                if ($question->tipe_jawaban === 'teks_bebas') {
                    $dataJawaban['jawaban_teks'] = $jawabanValue;
                } else {
                    // Asumsi $jawabanValue adalah ID dari opsi jawaban
                    $selectedOption = $allOptions->get($jawabanValue);
                    if ($selectedOption) {
                        $dataJawaban['opsi_jawaban_id'] = $selectedOption->id;
                        // PERBAIKAN: Mengisi 'nilai_jawaban' berdasarkan 'apakah_benar'
                        // Asumsi: 1 untuk benar, 0 untuk salah.
                        $dataJawaban['nilai_jawaban']   = $selectedOption->apakah_benar ? 1 : 0;
                    }
                }

                $jawabanUntukDisimpan[] = $dataJawaban;
            }

            // 5. Simpan semua jawaban sekaligus jika ada
            if (!empty($jawabanUntukDisimpan)) {
                JawabanUser::insert($jawabanUntukDisimpan);
            }

            // 6. Hitung dan simpan skor akhir percobaan
            $percobaan->hitungDanSimpanSkor();

            // Jika semua berhasil, commit transaksi
            DB::commit();

            // 7. Arahkan ke halaman selesai
            return redirect()->route('survei.complete')
                ->with('success', 'Terima kasih, survei Anda berhasil disimpan.');
        } catch (\Exception $e) {
            // Jika terjadi error, batalkan semua perubahan
            DB::rollBack();

            // Catat error untuk debugging
            Log::error('Gagal menyimpan hasil survei: ' . $e->getMessage());

            // Kembalikan user ke halaman sebelumnya dengan pesan error
            return back()->withInput()
                ->with('error', 'Terjadi kesalahan saat menyimpan survei. Silakan coba lagi.');
        }
    }


    /**
     * Menampilkan halaman "Terima Kasih".
     */
    public function complete()
    {
        return view('peserta.monev.survei.complete');
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
