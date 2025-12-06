<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;
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
use App\Http\Controllers\Public\CeritaKamiController;
use App\Http\Controllers\KontenProgramPelatihanController;

use App\Http\Controllers\PertanyaanController;
use App\Http\Controllers\UploadController;
use App\Http\Controllers\BeritaController;
use App\Http\Controllers\MateriController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\AssessmentAuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\KompetensiPelatihanController;

// --- MODELS & OTHERS ---
use App\Models\Peserta;
use App\Models\Pertanyaan;
use App\Models\OpsiJawaban;
use App\Models\JawabanUser;
use App\Mail\TestMail;
use App\Exports\PesertaExport;
use App\Exports\PesertaSheet;
use App\Exports\LampiranSheet;
use App\Models\Bidang;
/*
|--------------------------------------------------------------------------
| Web Routes (Gabungan Final)
|--------------------------------------------------------------------------
*/

/* =========================
   Public: Landing & Static
   ========================= */
Route::get('/', fn() => view('pages.landing'))->name('landing');

// PROFIL
//Route::view('/cerita-kami',          'pages.profil.cerita-kami')->name('story');
Route::get('/cerita-kami', [CeritaKamiController::class, 'index'])->name('cerita-kami');

Route::get('/program-pelatihan', [KontenProgramPelatihanController::class, 'index'])
    ->name('program-pelatihan.index');

Route::view('/kompetensi-pelatihan', 'pages.profil.kompetensi-pelatihan')->name('kompetensi');

Route::redirect('/bidang-pelatihan', '/kompetensi-pelatihan', 301); 

Route::view('/berita',  'pages.berita')->name('news');

Route::view('/panduan', 'pages.panduan')->name('panduan');

// Legacy redirect
Route::get('/home', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard.home');
    }
    return redirect()->route('landing');
})->name('home');


// Halaman Masuk
Route::view('/masuk', 'pages.masuk')->name('masuk');

Route::get('/daftar', [PendaftaranController::class, 'showDaftar'])
    ->name('pendaftaran.daftar');

// Redirect /home (kalau sudah login ke dashboard)
Route::get('/home', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard.home');
    }
    return redirect()->route('landing');
})->name('home');

/*
|--------------------------------------------------------------------------
| Pendaftaran (Public)
|--------------------------------------------------------------------------
*/
Route::resource('pendaftaran', PendaftaranController::class);
Route::get('pendaftaran/selesai/{id}', [PendaftaranController::class, 'selesai'])->name('pendaftaran.selesai');
Route::get('pendaftaran/testing', [PendaftaranController::class, 'testing'])->name('pendaftaran.testing');
Route::get('pendaftaran/download-file', [PendaftaranController::class, 'download_file'])->name('pendaftaran.download_file');
Route::get('pendaftaran-baru', fn() => view('registration-form-new'))->name('pendaftaran.baru');
Route::get('cetak-massal', [PendaftaranController::class, 'generateMassal'])->name('pendaftaran.generateMassal');

Route::get('/exports/pendaftaran/{pelatihan}/bulk', [PendaftaranController::class, 'exportBulk'])
    ->name('exports.pendaftaran.bulk');

Route::get('/exports/pendaftaran/{pelatihan}/sample', [PendaftaranController::class, 'exportSample'])
    ->name('exports.pendaftaran.sample');

Route::get('/exports/pendaftaran/single/{pendaftaran}', [PendaftaranController::class, 'exportSingle'])
    ->name('exports.pendaftaran.single');

// Peserta related downloads
Route::get('peserta/{peserta}/download-pdf', [PendaftaranController::class, 'download'])->name('peserta.download-pdf');
Route::get('peserta/download-bulk', [PendaftaranController::class, 'downloadBulk'])->name('peserta.download-bulk');

// Step views (simple view routes for wizard steps)
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
| Detail Pelatihan (Public simplified)
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
| Survey / Monev (Public)
|--------------------------------------------------------------------------
*/
Route::resource('/survey', SurveyController::class)->except(['index', 'create', 'store']);
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
| Dashboard (guest-friendly for pre/post test & content)
|--------------------------------------------------------------------------
*/
Route::prefix('dashboard')->name('dashboard.')->group(function () {
    Route::get('/', [DashboardController::class, 'home'])->name('home');

    // Materi list & show (some routes use DashboardController, resource for admin editing)
    Route::get('/materi', [DashboardController::class, 'materi'])->name('materi.index');
    Route::get('/materi/{materi}', [DashboardController::class, 'materiShow'])->name('materi.show');

    // Optional resource for Materi management (protected under auth in admin group)
    // Route::resource('materi', MateriController::class)->names('materi')->except(['index','show']);

    Route::get('/profile', [DashboardController::class, 'profile'])->name('profile');
    Route::get('/progress', [DashboardController::class, 'progress'])->name('progress');

    Route::post('set-peserta', [DashboardController::class, 'setPeserta'])->name('setPeserta');
    Route::post('unset-peserta', [DashboardController::class, 'unsetPeserta'])->name('unsetPeserta');

    Route::get('ajax/peserta/instansi-by-nama', [DashboardController::class, 'lookupInstansiByNama'])
        ->name('ajax.peserta.instansiByNama');

    Route::post('logout', [DashboardController::class, 'logout'])->name('logout');

    // Pretest & Posttest
    Route::prefix('pretest')->name('pretest.')->group(function () {
        Route::get('/', [DashboardController::class, 'pretest'])->name('index');
        Route::get('result/{percobaan}', [DashboardController::class, 'pretestResult'])->name('result');
        Route::post('{percobaan}/submit', [DashboardController::class, 'pretestSubmit'])->name('submit');
        Route::get('{tes}/start', [DashboardController::class, 'pretestStart'])->name('start');
        Route::post('{tes}/begin', [DashboardController::class, 'pretestBegin'])->name('begin');
        Route::get('{tes}', [DashboardController::class, 'pretestShow'])->name('show');
    });

    Route::prefix('posttest')->name('posttest.')->group(function () {
        Route::get('/', [DashboardController::class, 'posttest'])->name('index');
        Route::get('result/{percobaan}', [DashboardController::class, 'posttestResult'])->name('result');
        Route::post('{percobaan}/submit', [DashboardController::class, 'posttestSubmit'])->name('submit');
        Route::get('{tes}/start', [DashboardController::class, 'posttestStart'])->name('start');
        Route::post('{tes}/begin', [DashboardController::class, 'posttestBegin'])->name('begin');
        Route::get('{tes}', [DashboardController::class, 'posttestShow'])->name('show');
    });

    Route::get('survey', [DashboardController::class, 'survey'])->name('survey');
    Route::post('survey/submit', [DashboardController::class, 'surveySubmit'])->name('survey.submit');
});

/*
|--------------------------------------------------------------------------
| Assessment (special token login, separate flows)
|--------------------------------------------------------------------------
*/
Route::prefix('assessment')->name('assessment.')->group(function () {
    Route::get('/login', [AssessmentAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AssessmentAuthController::class, 'login'])->name('login.submit');
    Route::get('/dashboard', [AssessmentAuthController::class, 'dashboard'])->name('dashboard');
    Route::post('/logout', [AssessmentAuthController::class, 'logout'])->name('logout');
});

/*
|--------------------------------------------------------------------------
| Admin / Authenticated Utilities (requires auth)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    // Admin dashboard (token management view)
    Route::get('/admin/dashboard', [AdminController::class, 'showTokenManagement'])->name('admin.dashboard');

    // Pendaftaran admin actions (token generate/download)
    Route::post('/admin/tokens/generate', [PendaftaranController::class, 'generateTokenMassal'])->name('admin.generate.tokens');
    Route::get('/admin/tokens/download', [PendaftaranController::class, 'downloadTokenAssessment'])->name('admin.download.tokens');

    // Uploads
    Route::post('/admin/uploads', [UploadController::class, 'store'])->name('admin.uploads.store');

    // Pertanyaan resource (admin)
    Route::resource('pertanyaan', PertanyaanController::class);

    // Reports & Export (admin)
    Route::get('/export/report/pelatihan/{pelatihanId}', [ExportController::class, 'generateReportPdf'])
        ->name('export.report.pelatihan');

    Route::get('/reports/jawaban-survei/pdf/{pelatihanId}', [ExportController::class, 'pdfView'])
        ->name('reports.jawaban-survei.pdf');

    // Volt settings (admin)
    Route::redirect('settings', 'settings/profile');
    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');

    // Optional Materi resource for admin editing
    Route::resource('materi', MateriController::class)->except(['index','show']);
});


/*
|--------------------------------------------------------------------------
| Exports / Misc / Testing
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
| Additional Fix / Backward-Compatible Shortcuts
|--------------------------------------------------------------------------
*/
Route::get('/download-file', [PendaftaranController::class, 'download_file'])->name('pendaftaran.download_file');
Route::get('/cetak-massal', [PendaftaranController::class, 'generateMassal'])->name('pendaftaran.generateMassal');
Route::get('testing', [PendaftaranController::class, 'testing'])->name('pendaftaran.testing');
Route::get('/peserta/{peserta}/download-pdf', [PendaftaranController::class, 'download'])->name('peserta.download-pdf');
Route::get('/peserta/download-bulk', [PendaftaranController::class, 'downloadBulk'])->name('peserta.download-bulk');

/*
|--------------------------------------------------------------------------
| Auth Routes (login/register/etc.)
|--------------------------------------------------------------------------
*/
require __DIR__ . '/auth.php';

/*
|--------------------------------------------------------------------------
| Local-only: sandbox and testing helpers
|--------------------------------------------------------------------------
*/
if (App::isLocal()) {
    if (file_exists(__DIR__ . '/sandbox.php')) {
        require_once __DIR__ . '/sandbox.php';
    }

    Route::get('/test', function () {
        // contoh panggil fungsi dari sandbox.php
        $result = function_exists('countBidang') ? countBidang() : ['msg' => 'helper countBidang not found'];
        return response()->json($result);
    });
}
