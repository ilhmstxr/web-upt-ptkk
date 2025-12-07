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
use App\Http\Controllers\PesertaController; // Pastikan digunakan jika Anda punya rute untuk ini
use App\Http\Controllers\PendaftaranController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SuratController; // Pastikan digunakan jika Anda punya rute untuk ini
use App\Http\Controllers\SurveyController;
use App\Http\Controllers\PostTestController; // Pastikan digunakan jika Anda punya rute untuk ini
use App\Http\Controllers\Auth\LoginController; // Pastikan digunakan jika auth.php tidak menangani login
use App\Http\Controllers\PertanyaanController;
use App\Http\Controllers\UploadController;
use App\Http\Controllers\BeritaController;
use App\Http\Controllers\MateriController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\AssessmentAuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Public\CeritaKamiController;
use App\Http\Controllers\Public\PelatihanController as PublicPelatihanController; // Controller Index Pelatihan (NEW)
use App\Http\Controllers\PelatihanDetailController; // Controller Detail Pelatihan (EXISTING)
use App\Http\Controllers\KompetensiController;
use App\Http\Controllers\KontenProgramPelatihanController;

// --- MODELS & OTHERS ---
use App\Models\Peserta;
use App\Models\Pertanyaan; // Diperlukan jika Anda menggunakan ini
use App\Models\OpsiJawaban; // Diperlukan jika Anda menggunakan ini
use App\Models\JawabanUser; // Diperlukan jika Anda menggunakan ini
use App\Mail\TestMail;
use App\Exports\PesertaExport;
use App\Exports\PesertaSheet;
use App\Exports\LampiranSheet;
use App\Models\Bidang;
/*
|--------------------------------------------------------------------------
| routes/web.php (Final gabungan)
|--------------------------------------------------------------------------
*/

/* =========================
   Public: Landing & Static
   ========================= */
Route::get('/', [LandingController::class, 'index'])->name('landing');

// PROFIL
//Route::view('/cerita-kami',          'pages.profil.cerita-kami')->name('story');
Route::get('/cerita-kami', [LandingController::class, 'ceritaKami'])->name('cerita-kami');

Route::get('/program-pelatihan', [KontenProgramPelatihanController::class, 'index'])
    ->name('programs');

Route::get('/kompetensi', [KompetensiController::class, 'index'])
    ->name('kompetensi');

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

// Cerita Kami via controller (public)
Route::get('/cerita-kami', [CeritaKamiController::class, 'index'])->name('cerita-kami');
Route::get('/story', fn() => redirect()->route('cerita-kami'))->name('story'); // compatibility alias



Route::get('/berita', [BeritaController::class, 'index'])->name('berita.index');
Route::get('/berita/{slug}', [BeritaController::class, 'show'])->name('berita.show');

Route::view('/panduan', 'pages.panduan')->name('panduan');
Route::view('/kontak-kami', 'pages.kontak')->name('kontak');

//--------------------------------------------------------------------------
// PELATIHAN (INDEX & DETAIL) - NEW ADDITION
//--------------------------------------------------------------------------

// Rute untuk Daftar Pelatihan (Index) - Menghubungkan ke PublicPelatihanController:index
Route::get('/pelatihan', [PublicPelatihanController::class, 'index'])->name('pelatihan.index');

// Rute untuk Detail Pelatihan (Controller yang Anda sediakan) - Menghubungkan ke PelatihanDetailController:show
// Catatan: Pastikan ini diletakkan setelah rute index untuk menghindari konflik '/pelatihan/{slug}' menimpa '/pelatihan'
Route::get('/pelatihan/{slug}', [PelatihanDetailController::class, 'show'])->name('pelatihan.show');

// HAPUS RUTE LAMA YANG KONFLIK:
/*
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
*/

//--------------------------------------------------------------------------
// Pendaftaran & Exports (Public)
//--------------------------------------------------------------------------
Route::get('/daftar', [PendaftaranController::class, 'showDaftar'])->name('pendaftaran.daftar');
Route::resource('pendaftaran', PendaftaranController::class);
Route::get('pendaftaran/selesai/{id}', [PendaftaranController::class, 'selesai'])->name('pendaftaran.selesai');

// Rute ini ada duplikasi di bagian bawah, dihapus duplikasinya:
Route::get('pendaftaran/testing', [PendaftaranController::class, 'testing'])->name('pendaftaran.testing');
Route::get('pendaftaran/download-file', [PendaftaranController::class, 'download_file'])->name('pendaftaran.download_file');
Route::get('cetak-massal', [PendaftaranController::class, 'generateMassal'])->name('pendaftaran.generateMassal');

Route::get('pendaftaran-baru', fn() => view('registration-form-new'))->name('pendaftaran.baru');

Route::get('/exports/pendaftaran/{pelatihan}/bulk', [PendaftaranController::class, 'exportBulk'])
    ->name('exports.pendaftaran.bulk');

Route::get('/exports/pendaftaran/{pelatihan}/sample', [PendaftaranController::class, 'exportSample'])
    ->name('exports.pendaftaran.sample');

Route::get('/exports/pendaftaran/single/{pendaftaran}', [PendaftaranController::class, 'exportSingle'])
    ->name('exports.pendaftaran.single');

// Peserta downloads (Rute ini ada duplikasi di bagian bawah, dihapus duplikasinya):
Route::get('peserta/{peserta}/download-pdf', [PendaftaranController::class, 'download'])->name('peserta.download-pdf');
Route::get('peserta/download-bulk', [PendaftaranController::class, 'downloadBulk'])->name('peserta.download-bulk');

// Wizard step views (convenience)
Route::prefix('pendaftaran/step')->group(function () {
    Route::view('1', 'peserta.pendaftaran.bio-peserta');
    Route::view('2', 'peserta.pendaftaran.bio-sekolah');
    Route::view('3', 'peserta.pendaftaran.lampiran');
    Route::view('4', 'peserta.pendaftaran.selesai');
});

Route::view('template/instruktur', 'template_surat.instruktur');
Route::view('pendaftaran/monev', 'peserta.monev.pendaftaran');

//--------------------------------------------------------------------------
// Survey / Monev (Public)
//--------------------------------------------------------------------------
Route::prefix('survey')->name('survey.')->group(function () {
    Route::get('/', [SurveyController::class, 'index'])->name('index');
    Route::get('/create', [SurveyController::class, 'create'])->name('create');
    Route::post('/', [SurveyController::class, 'store'])->name('store');

    // Perbaikan: Rute resource harus di atas rute spesifik jika tidak menggunakan except
    Route::resource('/', SurveyController::class)->except(['index', 'create', 'store']);

    Route::get('/complete', [SurveyController::class, 'complete'])->name('complete');
    Route::post('/start', [SurveyController::class, 'start'])->name('start');
    Route::post('/check-credentials', [SurveyController::class, 'checkCredentials'])->name('checkCredentials');

    // Rute dengan parameter {peserta} dan {order} harus diletakkan paling bawah dalam group survey
    Route::get('/{peserta}/{order}', [SurveyController::class, 'show'])->name('step');
    Route::post('/{peserta}/{order}', [SurveyController::class, 'update'])->name('update');
});


//--------------------------------------------------------------------------
// Dashboard (guest-friendly for pre/post test & content)
//--------------------------------------------------------------------------
Route::prefix('dashboard')->name('dashboard.')->group(function () {
    Route::get('/', [DashboardController::class, 'home'])->name('home');

    // Materi
    Route::get('/materi', [DashboardController::class, 'materi'])->name('materi');
    Route::get('/materi/{materi}', [DashboardController::class, 'materiShow'])->name('materi.show');

    // Profil & Progress
    Route::get('/profile', [DashboardController::class, 'profile'])->name('profile');
    Route::get('/progress', [DashboardController::class, 'progress'])->name('progress');

    // Authentication/Session Management
    Route::post('set-peserta', [DashboardController::class, 'setPeserta'])->name('setPeserta');
    Route::post('unset-peserta', [DashboardController::class, 'unsetPeserta'])->name('unsetPeserta');
    Route::post('logout', [DashboardController::class, 'logout'])->name('logout');

    // AJAX
    Route::get('ajax/peserta/instansi-by-nama', [DashboardController::class, 'lookupInstansiByNama'])
        ->name('ajax.peserta.instansiByNama');

    // Pretest groups
    Route::prefix('pretest')->name('pretest.')->group(function () {
        Route::get('/', [DashboardController::class, 'pretest'])->name('index');
        Route::get('result/{percobaan}', [DashboardController::class, 'pretestResult'])->name('result');
        Route::post('{percobaan}/submit', [DashboardController::class, 'pretestSubmit'])->name('submit');
        Route::get('{tes}/start', [DashboardController::class, 'pretestStart'])->name('start');
        Route::post('{tes}/begin', [DashboardController::class, 'pretestBegin'])->name('begin');
        Route::get('{tes}', [DashboardController::class, 'pretestShow'])->name('show');
    });

    // Posttest groups
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

//--------------------------------------------------------------------------
// Assessment (special token login, separate flows)
//--------------------------------------------------------------------------
Route::prefix('assessment')->name('assessment.')->group(function () {
    Route::get('/login', [AssessmentAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AssessmentAuthController::class, 'login'])->name('login.submit');
    Route::get('/dashboard', [AssessmentAuthController::class, 'dashboard'])->name('dashboard');
    Route::post('/logout', [AssessmentAuthController::class, 'logout'])->name('logout');
});

//--------------------------------------------------------------------------
// Admin / Authenticated Utilities (requires auth)
//--------------------------------------------------------------------------
Route::middleware(['auth'])->group(function () {
    // Admin dashboard (token management view)
    Route::get('/admin/dashboard', [AdminController::class, 'showTokenManagement'])->name('admin.dashboard');

    // Pendaftaran admin actions (token generate/download)
    Route::post('/admin/tokens/generate', [PendaftaranController::class, 'generateTokenMassal'])->name('admin.generate.tokens');
    Route::get('/admin/tokens/download', [PendaftaranController::class, 'downloadTokenAssessment'])->name('admin.download.tokens');

    // Uploads (admin)
    Route::post('/admin/uploads', [UploadController::class, 'store'])->name('admin.uploads.store');

    // Pertanyaan resource (biasanya mencakup index, create, store, show, edit, update, destroy)
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

    // Materi management
    Route::resource('materi', MateriController::class); // Aktifkan semua kecuali jika Anda memiliki rute publik yang berbeda
});

//--------------------------------------------------------------------------
// Exports / Misc / Testing
//--------------------------------------------------------------------------
Route::get('/test-peserta', fn() => dd((new PesertaSheet(null))->collection()->take(5)));
Route::get('/test-lampiran', fn() => dd((new LampiranSheet(null))->collection()->take(5)));
Route::get('/export-peserta', fn() => Excel::download(new PesertaExport(), 'peserta.xlsx'))->name('export.peserta');

Route::get('/send', fn() => Mail::to('23082010166@student.upnjatim.ac.id')->send(new TestMail()));

Route::get('api/peserta', fn() => Peserta::with('lampiran', 'bidang', 'pelatihan', 'instansi')->get());

Route::get('/cek_icon', fn() => view('cek_icon'));
Route::get('test-pdf', function () { return view('test-pdf'); });


/*
|--------------------------------------------------------------------------
| Auth Routes (login/register/etc.)
|--------------------------------------------------------------------------
*/
require __DIR__ . '/auth.php';

//--------------------------------------------------------------------------
// Local-only: sandbox and testing helpers
//--------------------------------------------------------------------------
if (App::isLocal()) {
    if (file_exists(__DIR__ . '/sandbox.php')) {
        require_once __DIR__ . '/sandbox.php';
    }

    Route::get('/test', function () {
        $result = function_exists('countBidang') ? countBidang() : ['msg' => 'helper countBidang not found'];
        return response()->json($result);
    });
}
