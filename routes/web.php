<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;
use Livewire\Volt\Volt;
use Maatwebsite\Excel\Facades\Excel;

use App\Http\Controllers\PesertaController;
use App\Http\Controllers\PendaftaranController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SuratController;
use App\Http\Controllers\SurveyController;
use App\Http\Controllers\PostTestController;
use App\Http\Controllers\Auth\LoginController;

use App\Models\Peserta;
use App\Mail\TestMail;

use App\Exports\PesertaExport;
use App\Exports\PesertaSheet;
use App\Exports\LampiranSheet;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\PertanyaanController;
use App\Http\Controllers\UploadController;
use Illuminate\Support\Facades\DB;


use App\Models\Pertanyaan;
use App\Models\OpsiJawaban;
use App\Models\JawabanUser;
use Illuminate\Support\Facades\Response;
use Spatie\Browsershot\Browsershot;

/*
|--------------------------------------------------------------------------
| Web Routes Final
|--------------------------------------------------------------------------
| Gabungan antara file versi pertama & kedua.
| Sudah mencakup: dashboard publik (guest-friendly), pre/post test, survey, export,
| route fix, dan tambahan resource survey.
|--------------------------------------------------------------------------
*/

Route::get('/', fn() => view('landing'))->name('landing');

Route::get('/home', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard.home');
    }
    return redirect()->route('landing');
})->name('home');


/*
|--------------------------------------------------------------------------
| Pendaftaran
|--------------------------------------------------------------------------
*/
Route::resource('pendaftaran', PendaftaranController::class);
// Route::get('pendaftaran-selesai', [PendaftaranController::class, 'selesai'])->name('pendaftaran.selesai');
Route::get('pendaftaran/selesai/{id}', [PendaftaranController::class, 'selesai'])->name('pendaftaran.selesai');
Route::get('pendaftaran-testing', [PendaftaranController::class, 'testing'])->name('pendaftaran.testing');
Route::get('pendaftaran/download-file', [PendaftaranController::class, 'download_file'])->name('pendaftaran.download');
Route::get('pendaftaran-baru', fn() => view('registration-form-new'))->name('pendaftaran.baru');

// Peserta
Route::get('peserta/{peserta}/download-pdf', [PendaftaranController::class, 'download'])->name('peserta.download-pdf');
Route::get('peserta/download-bulk', [PendaftaranController::class, 'downloadBulk'])->name('peserta.download-bulk');

// ==========================================================================
// EXPORT
// ==========================================================================
// Cetak Massal
Route::get('cetak-massal', [PendaftaranController::class, 'generateMassal'])->name('pendaftaran.generateMassal');
Route::get('pendaftaran-baru', fn() => view('registration-form-new'))->name('pendaftaran.baru');
Route::get('/exports/pendaftaran/{pelatihan}/bulk',   [PendaftaranController::class, 'exportBulk'])
    ->name('exports.pendaftaran.bulk');

Route::get('/exports/pendaftaran/{pelatihan}/sample', [PendaftaranController::class, 'exportSample'])
    ->name('exports.pendaftaran.sample');

Route::get('/exports/pendaftaran/single/{pendaftaran}', [PendaftaranController::class, 'exportSingle'])
    ->name('exports.pendaftaran.single');
// Route::middleware(['auth'])->group(function () {
//     Route::get('/reports/jawaban-akumulatif/pdf', [ExportController::class, 'jawabanAkumulatifPdf'])
//         ->name('reports.jawaban-akumulatif.pdf');
// });
// Route::middleware(['auth']) // opsional, sesuai kebutuhan
//     ->get('/exports/report-jawaban-survei', [ExportController::class, 'reportJawabanSurvei'])
//     ->name('export.report-jawaban-survei');

Route::middleware(['auth'])->get('/export/report/pelatihan/{pelatihanId}', [ExportController::class, 'generateReportPdf'])
    ->name('export.report.pelatihan');
// Tambahkan middleware jika halaman ini hanya boleh diakses oleh user yang login



// Step View
Route::prefix('pendaftaran/step')->group(function () {
    Route::view('1', 'peserta.pendaftaran.bio-peserta');
    Route::view('2', 'peserta.pendaftaran.bio-sekolah');
    Route::view('3', 'peserta.pendaftaran.lampiran');
    Route::view('4', 'peserta.pendaftaran.selesai');
});

Route::view('template/instruktur', 'template_surat.instruktur');
Route::view('pendaftaran/monev', 'peserta.monev.pendaftaran');

// ============================
// Dashboard (guest-friendly untuk pre/post test)
// ============================
Route::prefix('dashboard')->name('dashboard.')->group(function () {
    // Dashboard utama
    Route::get('/', [DashboardController::class, 'home'])->name('home');
    Route::get('/profile', [DashboardController::class, 'profile'])->name('profile');
    Route::get('/materi', [DashboardController::class, 'materi'])->name('materi');
    Route::get('/materi/{materi}', [DashboardController::class, 'materiShow'])->name('materi.show');
    Route::get('/progress', [DashboardController::class, 'progress'])->name('progress');

    // ===== PERBAIKAN PATH: gunakan path relatif (tanpa 'dashboard/') =====
    // Set/Unset Peserta
    Route::post('set-peserta', [DashboardController::class, 'setPeserta'])->name('setPeserta');
    Route::post('unset-peserta', [DashboardController::class, 'unsetPeserta'])->name('unsetPeserta');

    Route::get('ajax/peserta/instansi-by-nama', [DashboardController::class, 'lookupInstansiByNama'])
        ->name('ajax.peserta.instansiByNama');

    // Logout (pilih salah satu, saran: POST)
    Route::post('logout', [DashboardController::class, 'logout'])->name('logout');
    // HAPUS ini bila pakai POST saja:
    // Route::match(['get','post'], 'logout', [DashboardController::class, 'logout'])->name('logout');

    Route::prefix('pretest')->name('pretest.')->group(function () {
        Route::get('/', [DashboardController::class, 'pretest'])->name('index');
        Route::get('result/{percobaan}', [DashboardController::class, 'pretestResult'])->name('result'); // spesifik dulu
        Route::post('{percobaan}/submit', [DashboardController::class, 'pretestSubmit'])->name('submit');
        Route::get('{tes}/start', [DashboardController::class, 'pretestStart'])->name('start');
        Route::post('{tes}/begin', [DashboardController::class, 'pretestBegin'])->name('begin');
        Route::get('{tes}', [DashboardController::class, 'pretestShow'])->name('show'); // dinamis terakhir
    });

    Route::prefix('posttest')->name('posttest.')->group(function () {
        Route::get('/', [DashboardController::class, 'posttest'])->name('index');
        Route::get('result/{percobaan}', [DashboardController::class, 'posttestResult'])->name('result');
        Route::post('{percobaan}/submit', [DashboardController::class, 'posttestSubmit'])->name('submit');
        Route::get('{tes}/start', [DashboardController::class, 'posttestStart'])->name('start');
        Route::post('{tes}/begin', [DashboardController::class, 'posttestBegin'])->name('begin');
        Route::get('{tes}', [DashboardController::class, 'posttestShow'])->name('show');
    });


    // Feedback (survey)
    Route::get('survey', [DashboardController::class, 'survey'])->name('survey');
    Route::post('survey/submit', [DashboardController::class, 'surveySubmit'])->name('survey.submit');
});
// routes/web.php
Route::post('/admin/uploads', [UploadController::class, 'store'])
    ->middleware(['web', 'auth'])
    ->name('admin.uploads.store');

route::resource('pertanyaan', PertanyaanController::class);



/*
|--------------------------------------------------------------------------
| Detail Pelatihan (public)
|--------------------------------------------------------------------------
*/
Route::get('/pelatihan/{kompetensi}', function ($kompetensi) {
    $kompetensiList = [
        'tata-boga',
        'tata-busana',
        'tata-kecantikan',
        'teknik-pendingin-dan-tata-udara',
    ];
    abort_unless(in_array($kompetensi, $kompetensiList), 404);
    return view('detail-pelatihan', compact('kompetensi'));
})->name('detail-pelatihan');


/*
|--------------------------------------------------------------------------
| Survey / Monev (resources & flows)
|--------------------------------------------------------------------------
| NOTE: keep resource if you use all CRUD; otherwise remove duplicates.
*/
Route::get('/survey', [SurveyController::class, 'index'])->name('survey.index');
Route::get('/survey/create', [SurveyController::class, 'create'])->name('survey.create');
Route::post('/survey', [SurveyController::class, 'store'])->name('survey.store');

// Participant-facing survey routes (flows)
Route::get('/complete', [SurveyController::class, 'complete'])->name('survey.complete');
Route::post('/start', [SurveyController::class, 'start'])->name('survey.start');
Route::post('/survey/check-credentials', [SurveyController::class, 'checkCredentials'])->name('survey.checkCredentials');

Route::get('/survey/{peserta}/{order}', [SurveyController::class, 'show'])->name('survey.step');
Route::post('/survey/{peserta}/{order}', [SurveyController::class, 'update'])->name('survey.update');

// If you also want resource routes for admin CRUD, keep this line. Be aware of duplication.
Route::resource('/survey', SurveyController::class)->except(['index', 'create', 'store']); // avoid duplicate index/create/store

/*
|--------------------------------------------------------------------------
| Excel Export
|--------------------------------------------------------------------------
*/
Route::get('/test-peserta', fn() => dd((new PesertaSheet(null))->collection()->take(5)));
Route::get('/test-lampiran', fn() => dd((new LampiranSheet(null))->collection()->take(5)));
Route::get('/export-peserta', fn() => Excel::download(new PesertaExport(), 'peserta.xlsx'))->name('export.peserta');

/*
|--------------------------------------------------------------------------
| Settings (Volt) - requires auth
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');
    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});


/*
|--------------------------------------------------------------------------
| Mail Testing
|--------------------------------------------------------------------------
*/
Route::get('/send', fn() => Mail::to('23082010166@student.upnjatim.ac.id')->send(new TestMail()));

/*
|--------------------------------------------------------------------------
| Data Peserta API
|--------------------------------------------------------------------------
*/
Route::get('api/peserta', fn() => Peserta::with('lampiran', 'bidang', 'pelatihan', 'instansi')->get());


/*
|--------------------------------------------------------------------------
| Route tambahan / fix (duplikat kecil diperbaiki)
|--------------------------------------------------------------------------
*/
Route::get('/download-file', [PendaftaranController::class, 'download_file'])->name('pendaftaran.download_file');
Route::get('/cetak-massal', [PendaftaranController::class, 'generateMassal'])->name('pendaftaran.generateMassal');
// Route::get('pendaftaran_selesai', [PendaftaranController::class, 'selesai'])->name('pendaftaran.selesai');
Route::get('testing', [PendaftaranController::class, 'testing'])->name('pendaftaran.testing');
Route::get('/peserta/{peserta}/download-pdf', [PendaftaranController::class, 'download'])->name('peserta.download-pdf');
Route::get('/peserta/download-bulk', [PendaftaranController::class, 'downloadBulk'])->name('peserta.download-bulk');

Route::get('/cek_icon', fn() => view('cek_icon'));

/*
|--------------------------------------------------------------------------
| Auth (login, register, dll.)
|--------------------------------------------------------------------------
*/

/*
|--------------------------------------------------------------------------
| TESTING
|--------------------------------------------------------------------------
*/
route::get('/test', function () {
    // return view('filament.resources.jawaban-surveis.pages.report-jawaban-survei');
    // $pelatihan = App\Models\Pelatihan::findOrFail(1);
    // $pelatihanIds = \App\Models\Pelatihan::with([
    //     'tes' => fn($q) => $q->where('tipe', 'survei')
    //         ->select('id', 'pelatihan_id')
    //         ->with([
    //             'pertanyaan' => fn($qp) => $qp->where('tipe_jawaban', 'skala_likert')
    //         ])
    // ])->get();


    // $questions = Pertanyaan::whereIn('id', $pertanyaanIds)->where('tipe_jawaban', 'skala_likert')
    //     ->orderBy('tes_id')->orderBy('nomor')
    //     ->get(['id', 'nomor', 'teks_pertanyaan']);
    // return $questions;
    /**
     * End-to-end pipeline:
     *  - Hitung skala Likert per jawaban {percobaan_id, pertanyaan_id, opsi_jawaban_id, skala}
     *  - Kelompokkan jawaban ke kategori kustom berbasis urutan pertanyaan & penanda 'pesan dan kesan'
     *  - Akumulasi nilai skala (1..4) global dan per kategori
     */
    // =============================
    // 0) Parameter & kategori kustom
    // =============================
    $arrayCustom = [
        'Pendapat Tentang Penyelenggaran Pelatihan',
        'Persepsi Terhadap Program Pelatihan',
        'Penilaian Terhadap Instruktur',
    ];

    // =============================
    // 1) Ambil semua pertanyaan Likert
    // =============================
    $pertanyaanLikert = Pertanyaan::where('tipe_jawaban', 'skala_likert')
        ->orderBy('id')
        ->get(['id', 'tes_id', 'teks_pertanyaan', 'tipe_jawaban', 'nomor']);

    $pertanyaanIds = $pertanyaanLikert->pluck('id');

    // =============================
    // 2) Pivot: pertanyaan -> template_pertanyaan
    // =============================
    $pivot = DB::table('pivot_jawaban')
        ->whereIn('pertanyaan_id', $pertanyaanIds)
        ->pluck('template_pertanyaan_id', 'pertanyaan_id');

    // =============================
    // 3) Kumpulan opsi untuk pertanyaan + template terkait (sekali query)
    // =============================
    $opsi = OpsiJawaban::whereIn(
        'pertanyaan_id',
        collect($pertanyaanIds)->merge($pivot->values())->unique()->all()
    )
        ->orderBy('id') // ganti ke kolom 'urutan' jika tersedia
        ->get(['id', 'pertanyaan_id', 'teks_opsi']);

    // 3a) Peta {pertanyaan_id => [opsi_id => skala]}
    $opsiIdToSkala = $opsi->groupBy('pertanyaan_id')->map(function ($rows) {
        $map = [];
        foreach ($rows->pluck('id')->values() as $i => $id) {
            $map[$id] = $i + 1; // 1..N
        }
        return $map; // contoh: [344=>4,343=>3,342=>2,341=>1]
    });

    // 3b) Peta {pertanyaan_id => [teks_opsi => opsi_id]}
    $opsiTextToId = $opsi->groupBy('pertanyaan_id')
        ->map(fn($rows) => $rows->pluck('id', 'teks_opsi')->mapWithKeys(
            fn($id, $teks) => [trim($teks) => $id]
        ));

    // =============================
    // 4) Jawaban user untuk pertanyaan likert
    // =============================
    $jawaban = JawabanUser::with('pertanyaan')
        ->whereIn('pertanyaan_id', $pertanyaanIds)
        ->get(['percobaan_id', 'pertanyaan_id', 'opsi_jawaban_id', 'jawaban_teks']);

    // =============================
    // 5) Hitung skala per jawaban (fallback ke template jika perlu)
    // =============================
    $hasilFlat = $jawaban->map(function ($j) use ($pivot, $opsiIdToSkala, $opsiTextToId) {
        $pid = (int) $j->pertanyaan_id;

        // Sumber skala: pakai opsi milik pertanyaan; jika kosong, pakai template
        $source = !empty($opsiIdToSkala->get($pid)) ? $pid : ($pivot->get($pid) ?? $pid);

        // Pastikan punya opsi_jawaban_id; jika null, cocokkan dari jawaban_teks
        $opsiId = $j->opsi_jawaban_id;
        if (!$opsiId && $j->jawaban_teks) {
            $opsiId = $opsiTextToId->get($source, collect())->get(trim($j->jawaban_teks));
        }

        // Hitung skala (indeks 1..N pada sumber)
        $skalaMap = $opsiIdToSkala->get($source, []);
        $skala = $opsiId ? ($skalaMap[$opsiId] ?? null) : null;

        return [
            'percobaan_id'    => (int) $j->percobaan_id,
            'pertanyaan_id'   => $pid,
            'opsi_jawaban_id' => $opsiId ? (int) $opsiId : null,
            'skala'           => $skala ? (int) $skala : null,
        ];
    })->values();

    // =============================
    // 6) Mapping pertanyaan -> kategori kustom
    //    Logika: urut per tes & nomor; ketika menemukan pertanyaan `teks_bebas`
    //    yang diawali 'pesan dan kesan', tutup grup dan labeli sesuai $arrayCustom
    // =============================
    $tesIds = $pertanyaanLikert->pluck('tes_id')->filter()->unique()->values();

    $semuaPertanyaanDalamTes = Pertanyaan::whereIn('tes_id', $tesIds)
        ->orderBy('tes_id')
        ->orderBy('nomor')
        ->get(['id', 'tes_id', 'tipe_jawaban', 'teks_pertanyaan', 'nomor']);

    $pertanyaanToKategori = [];

    foreach ($semuaPertanyaanDalamTes->groupBy('tes_id') as $tesId => $questions) {
        $groupKey = 1;        // dimulai 1
        $tempGroup = collect();

        foreach ($questions as $q) {
            $tempGroup->push($q);

            $isBoundary = $q->tipe_jawaban === 'teks_bebas'
                && str_starts_with(strtolower(trim($q->teks_pertanyaan)), 'pesan dan kesan');

            if ($isBoundary) {
                $categoryIndex = $groupKey - 1;
                $category = $arrayCustom[$categoryIndex] ?? ("Kategori " . $groupKey);

                // Map hanya pertanyaan Likert di dalam grup ke kategori
                foreach ($tempGroup as $item) {
                    if ($item->tipe_jawaban === 'skala_likert') {
                        $pertanyaanToKategori[$item->id] = $category;
                    }
                }
                $tempGroup = collect();
                $groupKey++;
            }
        }

        // Jika masih ada sisa grup tanpa boundary di akhir
        if ($tempGroup->isNotEmpty()) {
            $categoryIndex = $groupKey - 1;
            $category = $arrayCustom[$categoryIndex] ?? ("Kategori " . $groupKey);
            foreach ($tempGroup as $item) {
                if ($item->tipe_jawaban === 'skala_likert') {
                    $pertanyaanToKategori[$item->id] = $category;
                }
            }
        }
    }

    // =============================
    // 7) Output 2: dikelompokkan per kategori kustom
    // =============================
    $perKategori = $hasilFlat->groupBy(function ($row) use ($pertanyaanToKategori) {
        return $pertanyaanToKategori[$row['pertanyaan_id']] ?? 'Tanpa Kategori';
    })->map(function ($rows) {
        return $rows->values();
    });

    // =============================
    // 8) Output 3: akumulatif skala 1..4 (global & per kategori)
    // =============================
    $initCounter = fn() => [1 => 0, 2 => 0, 3 => 0, 4 => 0];

    // Global
    $akumulatifGlobal = $initCounter();
    foreach ($hasilFlat as $row) {
        $s = $row['skala'] ?? null;
        if ($s && isset($akumulatifGlobal[$s])) {
            $akumulatifGlobal[$s]++;
        }
    }

    // Per kategori
    $akumulatifPerKategori = [];
    foreach ($perKategori as $kategori => $rows) {
        $counter = $initCounter();
        foreach ($rows as $row) {
            $s = $row['skala'] ?? null;
            if ($s && isset($counter[$s])) {
                $counter[$s]++;
            }
        }
        $akumulatifPerKategori[$kategori] = $counter;
    }

    // =============================
    // Return semua keluaran
    // =============================
    return response()->json([
        // 'pertanyaan' => $pertanyaan,
        'output_1_flat'       => $hasilFlat,                  // [{percobaan_id, pertanyaan_id, opsi_jawaban_id, skala}]
        'output_2_perKategori' => $perKategori,                // {kategori: [...rows...]}
        'output_3_akumulatif' => [
            'global'    => $akumulatifGlobal,                 // {1: n, 2: n, 3: n, 4: n}
            'perKategori' => $akumulatifPerKategori,           // {kategori: {1:n,2:n,3:n,4:n}}
        ],
    ]);
})->name('login');

route::get('test-pdf', function () {
    return view('test-pdf');
});

// Route::get('testing-export-pdf', function ($pelatihanId) {
//     $view = view('filament.resources.jawaban-surveis.pages.report-page', ['pelatihanId' => $pelatihanId])->render();
//     // $view = view('test-pdf')->render();
//     $pdf = Browsershot::html($view)->pdf();
//     // $pdfContent = Browsershot::url('https://example.com')->pdf();
//     // $pdfContent = Browsershot::url('http://127.0.0.1:8000/exports/report-jawaban-survei?pelatihanId=1')->pdf();

//     return Response::make($pdf, 200, [
//         'Content-Type' => 'application/pdf',
//         'Content-Disposition' => 'inline; filename="laporan.pdf"',
//     ]);


//     // return view("welcome");
// });
require __DIR__ . '/auth.php';
