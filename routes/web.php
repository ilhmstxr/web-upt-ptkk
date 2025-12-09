<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\App;
use Livewire\Volt\Volt;
use Maatwebsite\Excel\Facades\Excel;

use App\Http\Controllers\PendaftaranController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SurveyController;
use App\Http\Controllers\PertanyaanController;
use App\Http\Controllers\UploadController;
use App\Http\Controllers\TesRekapDownloadController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\AssessmentLoginController;
use App\Http\Controllers\AsramaOtomasiController;
use App\Http\Controllers\PelatihanDetailController;
use App\Http\Controllers\StatistikPelatihanController;

use App\Models\Peserta;
use App\Mail\TestMail;
use App\Exports\PesertaExport;
use App\Exports\PesertaSheet;
use App\Exports\LampiranSheet;

/*
|--------------------------------------------------------------------------
| Web Routes Final + Assessment Token Login
|--------------------------------------------------------------------------
| Berisi public routes, assessment login, dashboard peserta, export, dll.
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
|  A. BERANDA & HOME
====================================================================== */
Route::get('/', fn () => view('pages.landing'))->name('landing');

// PROFIL
Route::view('/cerita-kami',          'pages.profil.cerita-kami')->name('story');
Route::view('/program-pelatihan',    'pages.profil.program-pelatihan')->name('programs');
Route::view('/kompetensi-pelatihan', 'pages.profil.kompetensi-pelatihan')->name('kompetensi');

Route::view('/berita',  'pages.berita')->name('news');
Route::view('/panduan', 'pages.panduan')->name('panduan');

// halaman masuk umum (kalau masih dipakai)
Route::view('/masuk', 'pages.masuk')->name('masuk');

/* ======================================================================
|  B. ASSESSMENT LOGIN (NO REG + TGL LAHIR)
====================================================================== */
Route::middleware(['web', 'guest'])->group(function () {
    Route::get('/assessment/login', [AssessmentLoginController::class, 'show'])
        ->name('assessment.login');

    Route::post('/assessment/login', [AssessmentLoginController::class, 'submit'])
        ->name('assessment.login.submit');
});

Route::post('/assessment/logout', [AssessmentLoginController::class, 'logout'])
    ->middleware(['web'])
    ->name('assessment.logout');

/* ======================================================================
|  C. HOME redirect
====================================================================== */
Route::get('/home', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard.home');
    }
    return redirect()->route('landing');
})->name('home');

/* ======================================================================
|  D. PENDAFTARAN
====================================================================== */
Route::resource('pendaftaran', PendaftaranController::class);

Route::get('pendaftaran/selesai/{id}', [PendaftaranController::class, 'selesai'])
    ->name('pendaftaran.selesai');

Route::get('pendaftaran-testing', [PendaftaranController::class, 'testing'])
    ->name('pendaftaran.testing');

Route::get('pendaftaran/download-file', [PendaftaranController::class, 'download_file'])
    ->name('pendaftaran.download');

Route::get('/daftar', [PendaftaranController::class, 'showDaftar'])
    ->name('pendaftaran.daftar');

Route::get('pendaftaran-baru', fn () => view('registration-form-new'))
    ->name('pendaftaran.baru');

Route::get('peserta/{peserta}/download-pdf', [PendaftaranController::class, 'download'])
    ->name('peserta.download-pdf');

Route::get('peserta/download-bulk', [PendaftaranController::class, 'downloadBulk'])
    ->name('peserta.download-bulk');

Route::get('cetak-massal', [PendaftaranController::class, 'generateMassal'])
    ->name('pendaftaran.generateMassal');

Route::get('/exports/pendaftaran/{pelatihan}/bulk', [PendaftaranController::class, 'exportBulk'])
    ->name('exports.pendaftaran.bulk');

Route::get('/exports/pendaftaran/{pelatihan}/sample', [PendaftaranController::class, 'exportSample'])
    ->name('exports.pendaftaran.sample');

Route::get('/exports/pendaftaran/single/{pendaftaran}', [PendaftaranController::class, 'exportSingle'])
    ->name('exports.pendaftaran.single');

// Export laporan pelatihan (PDF)
Route::middleware(['auth'])->get('/export/report/pelatihan/{pelatihanId}', [ExportController::class, 'generateReportPdf'])
    ->name('export.report.pelatihan');

// Export jawaban survei (PDF)
Route::middleware(['auth'])->get('/reports/jawaban-survei/pdf/{pelatihanId}', [ExportController::class, 'pdfView'])
    ->name('reports.jawaban-survei.pdf');

// Step pendaftaran statis
Route::prefix('pendaftaran/step')->group(function () {
    Route::view('1', 'peserta.pendaftaran.bio-peserta');
    Route::view('2', 'peserta.pendaftaran.bio-sekolah');
    Route::view('3', 'peserta.pendaftaran.lampiran');
    Route::view('4', 'peserta.pendaftaran.selesai');
});

// Template surat
Route::view('template/instruktur', 'template_surat.instruktur');
Route::view('pendaftaran/monev', 'peserta.monev.pendaftaran');

/* ======================================================================
|  E. DASHBOARD PESERTA (protected by middleware assessment)
====================================================================== */
Route::middleware(['assessment'])->prefix('dashboard')->name('dashboard.')->group(function () {

    // HOME
    Route::get('/', [DashboardController::class, 'home'])->name('home');
    Route::get('/profile', [DashboardController::class, 'profile'])->name('profile');

    // MATERI
    Route::get('/materi', [DashboardController::class, 'materi'])->name('materi.index');
    Route::get('/materi/{materi}', [DashboardController::class, 'materiShow'])->name('materi.show');
    Route::post('/materi/{materi}/complete', [DashboardController::class, 'materiComplete'])->name('materi.complete');

    // SET PESERTA (optional legacy UI)
    Route::post('/set-peserta', [DashboardController::class, 'setPeserta'])->name('setPeserta');

    // AJAX helper
    Route::get('/ajax/peserta/instansi-by-nama', [DashboardController::class, 'lookupInstansiByNama'])
        ->name('ajax.peserta.instansiByNama');

    // LOGOUT (session for assessment/dashboard)
    Route::post('/logout', [DashboardController::class, 'logout'])->name('logout');

    // PRETEST
    Route::prefix('pretest')->name('pretest.')->group(function () {
        Route::get('/', [DashboardController::class, 'pretest'])->name('index');
        Route::get('/result/{percobaan}', [DashboardController::class, 'pretestResult'])->name('result');
        Route::post('/{percobaan}/submit', [DashboardController::class, 'pretestSubmit'])->name('submit');
        Route::get('/{tes}/start', [DashboardController::class, 'pretestStart'])->name('start');
        Route::get('/{tes}', [DashboardController::class, 'pretestShow'])->name('show');
    });

    // POSTTEST
    Route::prefix('posttest')->name('posttest.')->group(function () {
        Route::get('/', [DashboardController::class, 'posttest'])->name('index');
        Route::get('/result/{percobaan}', [DashboardController::class, 'posttestResult'])->name('result');
        Route::post('/{percobaan}/submit', [DashboardController::class, 'posttestSubmit'])->name('submit');
        Route::get('/{tes}/start', [DashboardController::class, 'posttestStart'])->name('start');
        Route::get('/{tes}', [DashboardController::class, 'posttestShow'])->name('show');
    });

    // SURVEY
    Route::get('/survey', [DashboardController::class, 'survey'])->name('survey');
    Route::post('/survey/submit', [DashboardController::class, 'surveySubmit'])->name('survey.submit');

    // MONEV
    Route::prefix('monev')->name('monev.')->group(function () {
        Route::get('/', [DashboardController::class, 'monev'])->name('index');
        Route::get('/{tes}/start', [DashboardController::class, 'monevStart'])->name('start');
        Route::get('/{tes}/begin', [DashboardController::class, 'monevBegin'])->name('begin');
        Route::get('/{tes}', [DashboardController::class, 'monevShow'])->name('show');
    });
});

/* ======================================================================
|  F. UPLOAD admin
====================================================================== */
Route::post('/admin/uploads', [UploadController::class, 'store'])
    ->middleware(['web', 'auth'])
    ->name('admin.uploads.store');

/* ======================================================================
|  G. PERTANYAAN
====================================================================== */
Route::resource('pertanyaan', PertanyaanController::class);

/* ======================================================================
|  H. DETAIL PELATIHAN
====================================================================== */
Route::get('/pelatihan/{slug}', [PelatihanDetailController::class, 'show'])
    ->name('detail-pelatihan');

/* ======================================================================
|  I. SURVEY / MONEV PUBLIC
====================================================================== */
Route::get('/survey', [SurveyController::class, 'index'])->name('survey.index');
Route::get('/survey/create', [SurveyController::class, 'create'])->name('survey.create');
Route::post('/survey', [SurveyController::class, 'store'])->name('survey.store');

Route::get('/complete', [SurveyController::class, 'complete'])->name('survey.complete');
Route::post('/start', [SurveyController::class, 'start'])->name('survey.start');
Route::post('/survey/check-credentials', [SurveyController::class, 'checkCredentials'])
    ->name('survey.checkCredentials');

Route::get('/survey/{peserta}/{order}', [SurveyController::class, 'show'])->name('survey.step');
Route::post('/survey/{peserta}/{order}', [SurveyController::class, 'update'])->name('survey.update');

// admin variant to avoid name clashes with public survey routes
Route::resource('/survey-admin', SurveyController::class)->except(['index', 'create', 'store']);

/* ======================================================================
|  J. EXCEL EXPORT & TEST HELPERS
====================================================================== */
Route::get('/test-peserta', fn () => dd((new PesertaSheet(null))->collection()->take(5)));
Route::get('/test-lampiran', fn () => dd((new LampiranSheet(null))->collection()->take(5)));

Route::get('/export-peserta', fn () => Excel::download(new PesertaExport(), 'peserta.xlsx'))
    ->name('export.peserta');

// Rekap nilai tes
Route::get('/admin/tes/{tes}/rekap-download', [TesRekapDownloadController::class, 'download'])
    ->middleware(['web', 'auth'])
    ->name('tes.rekap.download');

/* ======================================================================
|  K. SETTINGS (Volt)
====================================================================== */
Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');
    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});

/* ======================================================================
|  L. MAIL TESTING
====================================================================== */
Route::get('/send', fn () => Mail::to('23082010166@student.upnjatim.ac.id')->send(new TestMail()));

/* ======================================================================
|  M. DATA PESERTA API (debug)
====================================================================== */
Route::get('/api/peserta', fn () => Peserta::with('lampiran', 'kompetensi', 'pelatihan', 'instansi')->get());

/* ======================================================================
|  N. STATISTIK PELATIHAN
====================================================================== */
Route::get('/statistik-pelatihan', [StatistikPelatihanController::class, 'index']);
Route::get('/api/statistik-pelatihan', [StatistikPelatihanController::class, 'data']);

/* ======================================================================
|  O. TESTING / SANDBOX LOCAL
====================================================================== */
require __DIR__ . '/auth.php';

Route::get('test-pdf', fn () => view('test-pdf'));

Route::post('/otomasi-asrama/{pelatihan}', [AsramaOtomasiController::class, 'jalankanOtomasi'])
    ->name('otomasi.asrama');

if (App::isLocal()) {
    require_once __DIR__ . '/sandbox.php';

    Route::get('/test', function () {
        $result = countKompetensi();
        return response()->json($result);
    });
}
