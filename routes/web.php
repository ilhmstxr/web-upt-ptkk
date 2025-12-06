<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Response;
use Livewire\Volt\Volt;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\Browsershot\Browsershot;

// --- CONTROLLERS ---
use App\Http\Controllers\PesertaController;
use App\Http\Controllers\PendaftaranController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SuratController;
use App\Http\Controllers\SurveyController;
use App\Http\Controllers\PostTestController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\PertanyaanController;
use App\Http\Controllers\UploadController;
use App\Http\Controllers\BeritaController;
use App\Http\Controllers\MateriController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\AssessmentAuthController; // [PENTING] Controller Login Token

// --- MODELS & OTHERS ---
use App\Models\Peserta;
use App\Models\Pertanyaan;
use App\Models\OpsiJawaban;
use App\Models\JawabanUser;
use App\Mail\TestMail;
use App\Exports\PesertaExport;
use App\Exports\PesertaSheet;
use App\Exports\LampiranSheet;

/*
|--------------------------------------------------------------------------
| Web Routes (Final Fix)
|--------------------------------------------------------------------------
*/

/* * ==========================================
 * ROUTE KHUSUS ASSESSMENT (LOGIN TOKEN)
 * ==========================================
 * Ini wajib ada agar form di overlay dashboard berfungsi
 */
Route::prefix('assessment')->name('assessment.')->group(function () {
    Route::get('/login', [AssessmentAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AssessmentAuthController::class, 'login'])->name('login.submit'); // <-- Ini yang dicari error tadi
    Route::get('/dashboard', [AssessmentAuthController::class, 'dashboard'])->name('dashboard');
    Route::post('/logout', [AssessmentAuthController::class, 'logout'])->name('logout');
});


/* ======= BERITA PUBLIC ======= */
Route::get('/berita', [BeritaController::class, 'index'])->name('berita.index');
Route::get('/berita/{slug}', [BeritaController::class, 'show'])->name('berita.show');


/* ======= BERANDA & HOME ======= */
Route::get('/', fn () => view('pages.landing'))->name('landing');

// PROFIL (dropdown navbar)
Route::view('/cerita-kami',          'pages.profil.cerita-kami')->name('story');
Route::view('/program-pelatihan',    'pages.profil.program-pelatihan')->name('programs');
// route kompetensi pelatihan
Route::get('/kompetensi-pelatihan', [LandingController::class, 'bidangPelatihan'])
    ->name('kompetensi');

// Legacy redirect
Route::get('/home', function () {
    // Redirect logic: Jika user admin (auth) login, ke dashboard admin (jika ada)
    // Jika peserta login (session), tetap ke dashboard home
    if (auth()->check()) {
        return redirect()->route('dashboard.home');
    }
    return redirect()->route('landing');
})->name('home');

Route::view('/masuk', 'pages.masuk')->name('masuk');
Route::view('/cerita-kami', 'pages.profil.cerita-kami')->name('story');
Route::view('/program-pelatihan', 'pages.profil.program-pelatihan')->name('programs');
Route::view('/kompetensi-pelatihan', 'pages.profil.kompetensi-pelatihan')->name('kompetensi');
Route::redirect('/bidang-pelatihan', '/kompetensi-pelatihan', 301);
Route::view('/panduan', 'pages.panduan')->name('panduan');
Route::view('/kontak-kami', 'pages.kontak')->name('kontak');


// Halaman Masuk
Route::view('/masuk', 'pages.masuk')->name('masuk');

// Halaman Daftar (frontend baru)
Route::get('/daftar', [PendaftaranController::class, 'showDaftar'])->name('pendaftaran.daftar');

// Redirect /home (kalau sudah login ke dashboard)
Route::get('/home', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard.home');
    }
    return redirect()->route('landing');
})->name('home');

/*
|--------------------------------------------------------------------------
| Pendaftaran & Exports
|--------------------------------------------------------------------------
*/
Route::resource('pendaftaran', PendaftaranController::class);
Route::get('pendaftaran/selesai/{id}', [PendaftaranController::class, 'selesai'])->name('pendaftaran.selesai');
Route::get('pendaftaran-testing', [PendaftaranController::class, 'testing'])->name('pendaftaran.testing');
Route::get('pendaftaran/download-file', [PendaftaranController::class, 'download_file'])->name('pendaftaran.download_file');
Route::get('pendaftaran-baru', fn() => view('registration-form-new'))->name('pendaftaran.baru');
Route::get('cetak-massal', [PendaftaranController::class, 'generateMassal'])->name('pendaftaran.generateMassal');

Route::get('peserta/{peserta}/download-pdf', [PendaftaranController::class, 'download'])->name('peserta.download-pdf');
Route::get('peserta/download-bulk', [PendaftaranController::class, 'downloadBulk'])->name('peserta.download-bulk');

Route::get('/exports/pendaftaran/{pelatihan}/bulk', [PendaftaranController::class, 'exportBulk'])
    ->name('exports.pendaftaran.bulk');
Route::get('/exports/pendaftaran/{pelatihan}/sample', [PendaftaranController::class, 'exportSample'])
    ->name('exports.pendaftaran.sample');
Route::get('/exports/pendaftaran/single/{pendaftaran}', [PendaftaranController::class, 'exportSingle'])
    ->name('exports.pendaftaran.single');

/* Step view pendaftaran */
Route::prefix('pendaftaran/step')->group(function () {
    Route::view('1', 'peserta.pendaftaran.bio-peserta');
    Route::view('2', 'peserta.pendaftaran.bio-sekolah');
    Route::view('3', 'peserta.pendaftaran.lampiran');
    Route::view('4', 'peserta.pendaftaran.selesai');
});

Route::view('template/instruktur', 'template_surat.instruktur');
Route::view('pendaftaran/monev', 'peserta.monev.pendaftaran');


/*
|--------------------------------------------------------------------------
| Dashboard Peserta (Tanpa Middleware Auth Laravel)
|--------------------------------------------------------------------------
| Middleware 'auth' DIHAPUS di sini agar Overlay Token bisa muncul.
| Keamanan ditangani oleh Logic Overlay di Controller/View.
*/
Route::prefix('dashboard')->name('dashboard.')->group(function () {

<<<<<<< HEAD
    // --- Rute Materi (Menggunakan MateriController) ---
    // FIX: Menggunakan Route::resource untuk menghindari konflik dengan rute dashboard di bawah
    Route::resource('materi', MateriController::class)->only(['index', 'show'])->names('materi');
    Route::post('materi/{materi}/complete', [MateriController::class, 'complete'])->name('materi.complete');

    // --- Rute Dashboard Lainnya (Menggunakan DashboardController) ---
    // Note: Rute untuk '/materi' dan '/materi/{materi}' di DashboardController dihapus

    Route::get('/', [DashboardController::class, 'home'])->name('home');
=======
    // [PENTING] Arahkan home ke AssessmentAuthController agar logic overlay jalan
    // Atau jika Anda pakai DashboardController, pastikan logic overlay ada di sana.
    // Di sini saya arahkan ke AssessmentAuthController sesuai flow terakhir.
    Route::get('/', [AssessmentAuthController::class, 'dashboard'])->name('home');

    // Materi
    Route::resource('materi', MateriController::class)->names('materi')->except(['index', 'show']);
    Route::get('/materi', [DashboardController::class, 'materi'])->name('materi.index');
    Route::get('/materi/{materi}', [DashboardController::class, 'materiShow'])->name('materi.show');
    Route::post('materi/{materi}/complete', [MateriController::class, 'complete'])->name('materi.complete');

    // Profil & Progress
>>>>>>> bb957f848c51108415c7a5beee75061bfb673daf
    Route::get('/profile', [DashboardController::class, 'profile'])->name('profile');
    Route::get('/progress', [DashboardController::class, 'progress'])->name('progress');

    // Logout
    Route::post('logout', [DashboardController::class, 'logout'])->name('logout');

    // AJAX & Utilities
    Route::post('set-peserta', [DashboardController::class, 'setPeserta'])->name('setPeserta');
    Route::post('unset-peserta', [DashboardController::class, 'unsetPeserta'])->name('unsetPeserta');
    Route::get('ajax/peserta/instansi-by-nama', [DashboardController::class, 'lookupInstansiByNama'])
        ->name('ajax.peserta.instansiByNama');

    // Pretest
    Route::prefix('pretest')->name('pretest.')->group(function () {
        Route::get('/', [DashboardController::class, 'pretest'])->name('index');
        Route::get('result/{percobaan}', [DashboardController::class, 'pretestResult'])->name('result');
        Route::post('{percobaan}/submit', [DashboardController::class, 'pretestSubmit'])->name('submit');
        Route::get('{tes}/start', [DashboardController::class, 'pretestStart'])->name('start');
        Route::post('{tes}/begin', [DashboardController::class, 'pretestBegin'])->name('begin');
        Route::get('{tes}', [DashboardController::class, 'pretestShow'])->name('show');
    });

    // Posttest
    Route::prefix('posttest')->name('posttest.')->group(function () {
        Route::get('/', [DashboardController::class, 'posttest'])->name('index');
        Route::get('result/{percobaan}', [DashboardController::class, 'posttestResult'])->name('result');
        Route::post('{percobaan}/submit', [DashboardController::class, 'posttestSubmit'])->name('submit');
        Route::get('{tes}/start', [DashboardController::class, 'posttestStart'])->name('start');
        Route::post('{tes}/begin', [DashboardController::class, 'posttestBegin'])->name('begin');
        Route::get('{tes}', [DashboardController::class, 'posttestShow'])->name('show');
    });

    // Survey
    Route::get('survey', [DashboardController::class, 'survey'])->name('survey');
    Route::post('survey/submit', [DashboardController::class, 'surveySubmit'])->name('survey.submit');
});


/*
|--------------------------------------------------------------------------
| Admin & Utilities (Wajib Login Admin)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    // Upload Admin
    Route::post('/admin/uploads', [UploadController::class, 'store'])->name('admin.uploads.store');

    // Pertanyaan
    Route::resource('pertanyaan', PertanyaanController::class);

    // Report Exports
    Route::get('/export/report/pelatihan/{pelatihanId}', [ExportController::class, 'generateReportPdf'])
        ->name('export.report.pelatihan');

    Route::get('/reports/jawaban-survei/pdf/{pelatihanId}', [ExportController::class, 'pdfView'])
        ->name('reports.jawaban-survei.pdf');

    // Settings (Volt)
    Route::redirect('settings', 'settings/profile');
    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});


/*
|--------------------------------------------------------------------------
| Detail Pelatihan (Public)
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
| Survey / Monev Public
|--------------------------------------------------------------------------
*/
<<<<<<< HEAD
// Rute Resource Survey (Kecuali index, create, store, karena sudah ditangani secara manual)
Route::resource('/survey', SurveyController::class)->except(['index', 'create', 'store']);

=======
Route::resource('/survey', SurveyController::class)->except(['index', 'create', 'store']);
>>>>>>> bb957f848c51108415c7a5beee75061bfb673daf
Route::get('/survey', [SurveyController::class, 'index'])->name('survey.index');
Route::get('/survey/create', [SurveyController::class, 'create'])->name('survey.create');
Route::post('/survey', [SurveyController::class, 'store'])->name('survey.store');

Route::get('/complete', [SurveyController::class, 'complete'])->name('survey.complete');
Route::post('/start', [SurveyController::class, 'start'])->name('survey.start');
Route::post('/survey/check-credentials', [SurveyController::class, 'checkCredentials'])->name('survey.checkCredentials');

Route::get('/survey/{peserta}/{order}', [SurveyController::class, 'show'])->name('survey.step');
Route::post('/survey/{peserta}/{order}', [SurveyController::class, 'update'])->name('survey.update');


/*
|--------------------------------------------------------------------------
| Excel / Testing routes
|--------------------------------------------------------------------------
*/
Route::get('/test-peserta', fn() => dd((new PesertaSheet(null))->collection()->take(5)));
Route::get('/test-lampiran', fn() => dd((new LampiranSheet(null))->collection()->take(5)));
Route::get('/export-peserta', fn() => Excel::download(new PesertaExport(), 'peserta.xlsx'))->name('export.peserta');
Route::get('/send', fn() => Mail::to('23082010166@student.upnjatim.ac.id')->send(new TestMail()));
Route::get('api/peserta', fn() => Peserta::with('lampiran', 'bidang', 'pelatihan', 'instansi')->get());
Route::get('/cek_icon', fn() => view('cek_icon'));
Route::get('test-pdf', function () { return view('test-pdf'); });


/*
|--------------------------------------------------------------------------
| Auth (login, register, dll.)
|--------------------------------------------------------------------------
*/
require __DIR__ . '/auth.php';


/*
|--------------------------------------------------------------------------
| Local only testing helper
|--------------------------------------------------------------------------
*/
if (App::isLocal()) {
    if (file_exists(__DIR__ . '/sandbox.php')) {
        require_once __DIR__ . '/sandbox.php';
    }

    Route::get('/test', function () {
        $result = countBidang(); // sesuaikan fungsi test
        return response()->json($result);
    });
}
