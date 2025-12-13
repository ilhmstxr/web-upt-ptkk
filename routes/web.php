<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\App;
use Livewire\Volt\Volt;
use Maatwebsite\Excel\Facades\Excel;

// --- CONTROLLERS ---
use App\Http\Controllers\LandingController;
use App\Http\Controllers\Public\CeritaKamiController;
use App\Http\Controllers\Public\PelatihanController as PublicPelatihanController;
use App\Http\Controllers\PelatihanDetailController;
use App\Http\Controllers\PendaftaranController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SurveyController;
use App\Http\Controllers\PertanyaanController;
use App\Http\Controllers\UploadController;
use App\Http\Controllers\MateriController;
use App\Http\Controllers\BeritaController;
use App\Http\Controllers\KompetensiController;
use App\Http\Controllers\KontenProgramPelatihanController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AssessmentLoginController;     // token login flow
use App\Http\Controllers\AssessmentAuthController;      // optional assessment dashboard flow
use App\Http\Controllers\ExportController;
use App\Http\Controllers\TesRekapDownloadController;
use App\Http\Controllers\AsramaOtomasiController;
use App\Http\Controllers\StatistikPelatihanController;
use App\Http\Controllers\TokenController;

// --- MODELS & OTHERS ---
use App\Models\Peserta;
use App\Mail\TestMail;
use App\Exports\PesertaExport;
use App\Exports\PesertaSheet;
use App\Exports\LampiranSheet;

/*
|--------------------------------------------------------------------------
| routes/web.php (FINAL GABUNGAN)
|--------------------------------------------------------------------------
*/

/* ======================================================================
|  SAFETY FALLBACK (Filament route mismatch workaround)
====================================================================== */
if (! Route::has('filament.admin.resources.materi-pelatihans.index')) {
    Route::get('/_filament_stub/materi-pelatihans', function () {
        if (Route::has('filament.resources.materi-pelatihans.index')) {
            return redirect()->route('filament.resources.materi-pelatihans.index');
        }
        return redirect()->route('dashboard.home');
    })->name('filament.admin.resources.materi-pelatihans.index');
}

/* ======================================================================
|  A. PUBLIC: LANDING & PROFIL & STATIS
====================================================================== */
Route::get('/', [LandingController::class, 'index'])->name('landing');

// Profil / Cerita Kami (public)
Route::get('/cerita-kami', [CeritaKamiController::class, 'index'])->name('cerita-kami');
Route::get('/story', fn () => redirect()->route('cerita-kami'))->name('story'); // alias lama

// Program Pelatihan & Kompetensi Pelatihan (profil public)
Route::get('/program-pelatihan', [KontenProgramPelatihanController::class, 'index'])->name('programs');
Route::get('/profil/kompetensi-pelatihan', [KompetensiController::class, 'index'])->name('kompetensi');
Route::redirect('/kompetensi-pelatihan', '/profil/kompetensi-pelatihan', 301);

// Berita (controller)
Route::get('/berita', [BeritaController::class, 'index'])->name('berita.index');
Route::get('/berita/{slug}', [BeritaController::class, 'show'])->name('berita.show');
Route::view('/panduan', 'pages.panduan')->name('panduan');
Route::view('/kontak-kami', 'pages.kontak')->name('kontak');

// Halaman Masuk umum kalau masih dipakai
Route::view('/masuk', 'pages.masuk')->name('masuk');

/* ======================================================================
|  B. HOME redirect (legacy)
====================================================================== */
Route::get('/home', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard.home');
    }
    return redirect()->route('landing');
})->name('home');

/* ======================================================================
|  C. PELATIHAN PUBLIC (INDEX & DETAIL)
====================================================================== */
Route::get('/pelatihan', [PublicPelatihanController::class, 'index'])
    ->name('pelatihan.index');

Route::get('/pelatihan/{slug}', [PelatihanDetailController::class, 'show'])
    ->name('pelatihan.show');

// alias kompatibilitas lama
Route::get('/detail-pelatihan/{slug}', fn ($slug) =>
    redirect()->route('pelatihan.show', $slug)
)->name('detail-pelatihan');

/* ======================================================================
|  D. PENDAFTARAN PUBLIC + EXPORT
====================================================================== */
Route::get('/daftar', [PendaftaranController::class, 'index'])
    ->name('pendaftaran.daftar');

Route::resource('pendaftaran', PendaftaranController::class);

Route::get('pendaftaran/selesai/{id}', [PendaftaranController::class, 'selesai'])
    ->name('pendaftaran.selesai');

Route::get('pendaftaran/testing', [PendaftaranController::class, 'testing'])
    ->name('pendaftaran.testing');

Route::get('pendaftaran/download-file', [PendaftaranController::class, 'download_file'])
    ->name('pendaftaran.download_file');

Route::get('cetak-massal', [PendaftaranController::class, 'generateMassal'])
    ->name('pendaftaran.generateMassal');

Route::get('pendaftaran-baru', fn () => view('registration-form-new'))
    ->name('pendaftaran.baru');

Route::get('peserta/{peserta}/download-pdf', [PendaftaranController::class, 'download'])
    ->name('peserta.download-pdf');

Route::get('peserta/download-bulk', [PendaftaranController::class, 'downloadBulk'])
    ->name('peserta.download-bulk');

// export pendaftaran
Route::get('/exports/pendaftaran/{pelatihan}/bulk', [PendaftaranController::class, 'exportBulk'])
    ->name('exports.pendaftaran.bulk');

Route::get('/exports/pendaftaran/{pelatihan}/sample', [PendaftaranController::class, 'exportSample'])
    ->name('exports.pendaftaran.sample');

Route::get('/exports/pendaftaran/single/{pendaftaran}', [PendaftaranController::class, 'exportSingle'])
    ->name('exports.pendaftaran.single');

// Step wizard statis
Route::prefix('pendaftaran/step')->group(function () {
    Route::view('1', 'peserta.pendaftaran.bio-peserta');
    Route::view('2', 'peserta.pendaftaran.bio-sekolah');
    Route::view('3', 'peserta.pendaftaran.lampiran');
    Route::view('4', 'peserta.pendaftaran.selesai');
});

Route::view('template/instruktur', 'template_surat.instruktur');
Route::view('pendaftaran/monev', 'peserta.monev.pendaftaran');

/* ======================================================================
|  E. SURVEY PUBLIC + SURVEY ADMIN
====================================================================== */
Route::prefix('survey')->name('survey.')->group(function () {
    Route::get('/', [SurveyController::class, 'index'])->name('index');
    Route::get('/create', [SurveyController::class, 'create'])->name('create');
    Route::post('/', [SurveyController::class, 'store'])->name('store');

    // resource tambahan selain index/create/store
    Route::resource('/', SurveyController::class)->except(['index', 'create', 'store']);

    Route::get('/complete', [SurveyController::class, 'complete'])->name('complete');
    Route::post('/start', [SurveyController::class, 'start'])->name('start');
    Route::post('/check-credentials', [SurveyController::class, 'checkCredentials'])->name('checkCredentials');

    Route::get('/{peserta}/{order}', [SurveyController::class, 'show'])->name('step');
    Route::post('/{peserta}/{order}', [SurveyController::class, 'update'])->name('update');
});

// admin variant supaya ga bentrok dengan public
Route::resource('/survey-admin', SurveyController::class)
    ->except(['index', 'create', 'store']);

/* ======================================================================
|  F. ASSESSMENT (TOKEN LOGIN)
====================================================================== */
Route::middleware(['web'])->group(function () {
    Route::get('/assessment/login', [AssessmentLoginController::class, 'show'])
        ->name('assessment.login');

    Route::post('/assessment/login', [AssessmentLoginController::class, 'submit'])
        ->name('assessment.login.submit');
});

Route::post('/assessment/logout', [AssessmentLoginController::class, 'logout'])
    ->middleware(['web', 'assessment'])
    ->name('assessment.logout');

// optional dashboard assessment kalau dipakai
Route::get('/assessment/dashboard', [AssessmentAuthController::class, 'dashboard'])
    ->middleware(['web', 'assessment'])
    ->name('assessment.dashboard');

/* ======================================================================
|  G. DASHBOARD PESERTA
|  (assessment + training.session)
====================================================================== */
Route::middleware(['web', 'assessment', 'training.session'])
    ->prefix('dashboard')
    ->name('dashboard.')
    ->group(function () {

    Route::get('/', [DashboardController::class, 'home'])->name('home');
    Route::get('/profile', [DashboardController::class, 'profile'])->name('profile');
    Route::get('/progress', [DashboardController::class, 'progress'])->name('progress');

    // Materi
    Route::get('/materi', [DashboardController::class, 'materi'])->name('materi.index');
    Route::get('/materi/{materi}', [DashboardController::class, 'materiShow'])->name('materi.show');
    Route::post('/materi/{materi}/complete', [DashboardController::class, 'materiComplete'])->name('materi.complete');

    // set/unset peserta (legacy UI)
    Route::post('/set-peserta', [DashboardController::class, 'setPeserta'])->name('setPeserta');
    Route::post('/unset-peserta', [DashboardController::class, 'unsetPeserta'])->name('unsetPeserta');

    // AJAX helper
    Route::get('/ajax/peserta/instansi-by-nama', [DashboardController::class, 'lookupInstansiByNama'])
        ->name('ajax.peserta.instansiByNama');

    // logout dashboard -> satu pintu dengan assessment logout
    Route::post('/logout', [AssessmentLoginController::class, 'logout'])->name('logout');

    // Pretest
    Route::prefix('pretest')->name('pretest.')->group(function () {
        Route::get('/', [DashboardController::class, 'pretest'])->name('index');
        Route::get('result/{percobaan}', [DashboardController::class, 'pretestResult'])->name('result');
        Route::post('{percobaan}/submit', [DashboardController::class, 'pretestSubmit'])->name('submit');
        Route::get('{tes}/start', [DashboardController::class, 'pretestStart'])->name('start');
        Route::get('{tes}', [DashboardController::class, 'pretestShow'])->name('show');
    });

    // Posttest
    Route::prefix('posttest')->name('posttest.')->group(function () {
        Route::get('/', [DashboardController::class, 'posttest'])->name('index');
        Route::get('result/{percobaan}', [DashboardController::class, 'posttestResult'])->name('result');
        Route::post('{percobaan}/submit', [DashboardController::class, 'posttestSubmit'])->name('submit');
        Route::get('{tes}/start', [DashboardController::class, 'posttestStart'])->name('start');
        Route::get('{tes}', [DashboardController::class, 'posttestShow'])->name('show');
    });

    // Survey internal dashboard
    Route::get('survey', [DashboardController::class, 'survey'])->name('survey');
    Route::post('survey/submit', [DashboardController::class, 'surveySubmit'])->name('survey.submit');

    // Monev (jika dipakai)
    Route::prefix('monev')->name('monev.')->group(function () {
        Route::get('/', [DashboardController::class, 'monev'])->name('index');
        Route::get('/{tes}/start', [DashboardController::class, 'monevStart'])->name('start');
        Route::get('/{tes}/begin', [DashboardController::class, 'monevBegin'])->name('begin');
        Route::get('/{tes}', [DashboardController::class, 'monevShow'])->name('show');
        Route::post('/{percobaan}/submit', [DashboardController::class, 'monevSubmit'])->name('submit');
        Route::get('/result/{percobaan}', [DashboardController::class, 'monevResult'])->name('result');
    });
});

/* ======================================================================
|  H. ADMIN / AUTH UTILITIES (auth)
====================================================================== */
Route::middleware(['auth'])->group(function () {

    // Admin dashboard token management
    Route::get('/admin/dashboard', [AdminController::class, 'showTokenManagement'])
        ->name('admin.dashboard');

    // Token generate/download
    Route::post('/admin/tokens/generate', [PendaftaranController::class, 'generateTokenMassal'])
        ->name('admin.generate.tokens');

    Route::get('/admin/tokens/download', [PendaftaranController::class, 'downloadTokenAssessment'])
        ->name('admin.download.tokens');

    // Upload admin
    Route::post('/admin/uploads', [UploadController::class, 'store'])
        ->name('admin.uploads.store');

    // Pertanyaan CRUD
    Route::resource('pertanyaan', PertanyaanController::class);

    // Materi CRUD (admin)
    Route::resource('materi', MateriController::class);

    // Export laporan pelatihan
    Route::get('/export/report/pelatihan/{pelatihanId}', [ExportController::class, 'generateReportPdf'])
        ->name('export.report.pelatihan');

    // Export jawaban survei
    Route::get('/reports/jawaban-survei/pdf/{pelatihanId}', [ExportController::class, 'pdfView'])
        ->name('reports.jawaban-survei.pdf');

    // Rekap nilai tes
    Route::get('/admin/tes/{tes}/rekap-download', [TesRekapDownloadController::class, 'download'])
        ->name('tes.rekap.download');
    
    // Export Data Pelatihan (Template Surat)
    Route::get('/export/template/rekap-pelatihan/{pelatihanId}', [ExportController::class, 'rekapPelatihan'])
        ->name('export.template.rekap-pelatihan');
    
    Route::get('/export/template/peserta-excel/{pelatihanId}', [ExportController::class, 'pesertaExcel'])
        ->name('export.template.peserta-excel');

    Route::get('/export/template/daftar-instruktur/{pelatihanId}', [ExportController::class, 'daftarInstruktur'])
        ->name('export.template.daftar-instruktur');

    Route::get('/export/template/biodata-peserta/{pelatihanId}', [ExportController::class, 'biodataPeserta'])
        ->name('export.template.biodata-peserta');

    // Asrama otomasi
    Route::post('/otomasi-asrama/{pelatihan}', [AsramaOtomasiController::class, 'jalankanOtomasi'])
        ->name('otomasi.asrama');

    // Volt settings
    Route::redirect('settings', 'settings/profile');
    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});

/* ======================================================================
|  I. STATISTIK PELATIHAN
====================================================================== */
Route::get('/statistik-pelatihan', [StatistikPelatihanController::class, 'index']);
Route::get('/api/statistik-pelatihan', [StatistikPelatihanController::class, 'data']);

/* ======================================================================
|  J. EXPORT / TEST HELPERS / DEBUG
====================================================================== */
Route::get('/test-peserta', fn () => dd((new PesertaSheet(null))->collection()->take(5)));
Route::get('/test-lampiran', fn () => dd((new LampiranSheet(null))->collection()->take(5)));
Route::get('/export-peserta', fn () => Excel::download(new PesertaExport(), 'peserta.xlsx'))
    ->name('export.peserta');
Route::get('/admin/download-tokens', [TokenController::class, 'download'])
    ->name('admin.download.tokens');

Route::get('/send', fn () => Mail::to('23082010166@student.upnjatim.ac.id')->send(new TestMail()));

// debug API peserta
Route::get('/api/peserta', fn () => Peserta::with('lampiran', 'kompetensi', 'pelatihan', 'instansi')->get());

Route::get('/cek_icon', fn () => view('cek_icon'));
Route::get('test-pdf', fn () => view('test-pdf'));

/* ======================================================================
|  K. AUTH ROUTES
====================================================================== */
require __DIR__ . '/auth.php';

/* ======================================================================
|  L. LOCAL SANDBOX / TESTING
====================================================================== */
if (App::isLocal()) {
    if (file_exists(__DIR__ . '/sandbox.php')) {
        require_once __DIR__ . '/sandbox.php';
    }

    Route::get('/test', function () {
        $result = countKompetensi();
        return response()->json($result);
    });
}
