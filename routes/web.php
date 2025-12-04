<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Response;
use Livewire\Volt\Volt;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\Browsershot\Browsershot;

// --- IMPORTS CONTROLLERS ---
use App\Http\Controllers\PesertaController;
use App\Http\Controllers\PendaftaranController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SuratController;
use App\Http\Controllers\SurveyController;
use App\Http\Controllers\PostTestController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\PertanyaanController;
use App\Http\Controllers\UploadController;
use App\Http\Controllers\BeritaController;
use App\Http\Controllers\MateriController; // FIX: Import MateriController
use App\Http\Controllers\ExportController; // Asumsi: Digunakan di rute export

// --- IMPORTS MODELS & EXPORTS ---
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
| Web Routes Final
|--------------------------------------------------------------------------
*/

/* ======= BERITA PUBLIC ======= */
Route::get('/berita', [BeritaController::class, 'index'])->name('berita.index');
Route::get('/berita/{slug}', [BeritaController::class, 'show'])->name('berita.show');


/* ======= BERANDA & HOME ======= */
Route::get('/', fn () => view('pages.landing'))->name('landing');

// Redireksi ke dashboard jika sudah login
Route::get('/home', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard.home');
    }
    return redirect()->route('landing');
})->name('home');

// ======= PROFIL (dropdown di navbar) =======
Route::view('/cerita-kami', 'pages.profil.cerita-kami')->name('story');
Route::view('/program-pelatihan', 'pages.profil.program-pelatihan')->name('programs');
Route::view('/kompetensi-pelatihan', 'pages.profil.kompetensi-pelatihan')->name('kompetensi');

// Legacy redirect
Route::redirect('/bidang-pelatihan', '/kompetensi-pelatihan', 301);

// Panduan
Route::view('/panduan', 'pages.panduan')->name('panduan');

/*
|--------------------------------------------------------------------------
| Pendaftaran & Exports
|--------------------------------------------------------------------------
*/
Route::resource('pendaftaran', PendaftaranController::class);
Route::get('pendaftaran/selesai/{id}', [PendaftaranController::class, 'selesai'])->name('pendaftaran.selesai');
Route::get('pendaftaran-testing', [PendaftaranController::class, 'testing'])->name('pendaftaran.testing');

// Download file dan cetak
Route::get('pendaftaran/download-file', [PendaftaranController::class, 'download_file'])->name('pendaftaran.download_file');
Route::get('pendaftaran-baru', fn() => view('registration-form-new'))->name('pendaftaran.baru');
Route::get('cetak-massal', [PendaftaranController::class, 'generateMassal'])->name('pendaftaran.generateMassal');

// Peserta PDF
Route::get('peserta/{peserta}/download-pdf', [PendaftaranController::class, 'download'])->name('peserta.download-pdf');
Route::get('peserta/download-bulk', [PendaftaranController::class, 'downloadBulk'])->name('peserta.download-bulk');

// Exports (Laporan & Pendaftaran)
Route::get('/exports/pendaftaran/{pelatihan}/bulk', [PendaftaranController::class, 'exportBulk'])
    ->name('exports.pendaftaran.bulk');
Route::get('/exports/pendaftaran/{pelatihan}/sample', [PendaftaranController::class, 'exportSample'])
    ->name('exports.pendaftaran.sample');
Route::get('/exports/pendaftaran/single/{pendaftaran}', [PendaftaranController::class, 'exportSingle'])
    ->name('exports.pendaftaran.single');

// Report Exports (membutuhkan ExportController)
Route::middleware(['auth'])
    ->get('/export/report/pelatihan/{pelatihanId}', [ExportController::class, 'generateReportPdf'])
    ->name('export.report.pelatihan');
Route::middleware(['auth'])
    ->get('/reports/jawaban-survei/pdf/{pelatihanId}', [ExportController::class, 'pdfView'])
    ->name('reports.jawaban-survei.pdf');


// Step View Pendaftaran
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
| Dashboard (Materi, Pretest, Posttest, Survey)
|--------------------------------------------------------------------------
*/
Route::prefix('dashboard')->name('dashboard.')->group(function () {
    
    // --- Rute Materi (Menggunakan MateriController) ---
    // FIX: Menggunakan Route::resource untuk menghindari konflik dengan rute dashboard di bawah
    Route::resource('materi', MateriController::class)->only(['index', 'show'])->names('materi');
    Route::post('materi/{materi}/complete', [MateriController::class, 'complete'])->name('materi.complete');
    
    // --- Rute Dashboard Lainnya (Menggunakan DashboardController) ---
    // Note: Rute untuk '/materi' dan '/materi/{materi}' di DashboardController dihapus
    
    Route::get('/', [DashboardController::class, 'home'])->name('home');
    Route::get('/profile', [DashboardController::class, 'profile'])->name('profile');
    Route::get('/progress', [DashboardController::class, 'progress'])->name('progress');

    Route::post('set-peserta', [DashboardController::class, 'setPeserta'])->name('setPeserta');
    Route::post('unset-peserta', [DashboardController::class, 'unsetPeserta'])->name('unsetPeserta');

    Route::get('ajax/peserta/instansi-by-nama', [DashboardController::class, 'lookupInstansiByNama'])
        ->name('ajax.peserta.instansiByNama');

    Route::post('logout', [DashboardController::class, 'logout'])->name('logout');

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
    // FIX: Mengubah 'survey.index' menjadi 'survey' agar cocok dengan panggilan route('dashboard.survey') di home.blade.php
    Route::get('survey', [DashboardController::class, 'survey'])->name('survey');
    Route::post('survey/submit', [DashboardController::class, 'surveySubmit'])->name('survey.submit');
});

// Upload admin
Route::post('/admin/uploads', [UploadController::class, 'store'])
    ->middleware(['web', 'auth'])
    ->name('admin.uploads.store');

Route::resource('pertanyaan', PertanyaanController::class);

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
| Survey / Monev Public (Di luar Dashboard)
|--------------------------------------------------------------------------
*/
// Rute Resource Survey (Kecuali index, create, store, karena sudah ditangani secara manual)
Route::resource('/survey', SurveyController::class)->except(['index', 'create', 'store']); 

Route::get('/survey', [SurveyController::class, 'index'])->name('survey.index');
Route::get('/survey/create', [SurveyController::class, 'create'])->name('survey.create');
Route::post('/survey', [SurveyController::class, 'store'])->name('survey.store');

// Flow Survey
Route::get('/complete', [SurveyController::class, 'complete'])->name('survey.complete');
Route::post('/start', [SurveyController::class, 'start'])->name('survey.start');
Route::post('/survey/check-credentials', [SurveyController::class, 'checkCredentials'])->name('survey.checkCredentials');

// Perbarui step/urutan
Route::get('/survey/{peserta}/{order}', [SurveyController::class, 'show'])->name('survey.step');
Route::post('/survey/{peserta}/{order}', [SurveyController::class, 'update'])->name('survey.update');


/*
|--------------------------------------------------------------------------
| Excel Export Testing
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
| Auth (login, register, dll.)
|--------------------------------------------------------------------------
*/
require __DIR__ . '/auth.php';


/*
|--------------------------------------------------------------------------
| TESTING
|--------------------------------------------------------------------------
*/
Route::get('test-pdf', function () {
    return view('test-pdf');
});

if (App::isLocal()) {

    // Muat semua fungsi dari file playground kita
    require_once __DIR__ . '/sandbox.php';

    // Ini adalah satu-satunya route yang Anda butuhkan untuk semua testing
    Route::get('/test', function () {

        // CUKUP GANTI NAMA FUNGSI DI BAWAH INI
        // UNTUK MENGETES LOGIKA YANG BERBEDA.

        // $result = SurveyHasilKegiatan();
        $result = countBidang();
        // $result = testCreateDummyUser();
        // $result = testSomethingElse();

        // Tampilkan hasilnya
        return response()->json($result);
    });
}