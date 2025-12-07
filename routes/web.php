<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Response;
use Livewire\Volt\Volt;
use Maatwebsite\Excel\Facades\Excel;

use App\Http\Controllers\PesertaController;
use App\Http\Controllers\PendaftaranController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SuratController;
use App\Http\Controllers\SurveyController;
use App\Http\Controllers\PostTestController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\PertanyaanController;
use App\Http\Controllers\UploadController;
use App\Http\Controllers\TesRekapDownloadController;
use App\Http\Controllers\ExportController;

use App\Models\Peserta;
use App\Models\Pertanyaan;
use App\Models\OpsiJawaban;
use App\Models\JawabanUser;

use App\Mail\TestMail;

use App\Exports\PesertaExport;
use App\Exports\PesertaSheet;
use App\Exports\LampiranSheet;

use Spatie\Browsershot\Browsershot;

/*
|--------------------------------------------------------------------------
| Web Routes Final
|--------------------------------------------------------------------------
*/

/* ======= BERANDA & HOME ======= */
Route::get('/', fn () => view('pages.landing'))->name('landing');

// PROFIL
Route::view('/cerita-kami',          'pages.profil.cerita-kami')->name('story');
Route::view('/program-pelatihan',    'pages.profil.program-pelatihan')->name('programs');
Route::view('/kompetensi-pelatihan', 'pages.profil.kompetensi-pelatihan')->name('kompetensi');

// (sepertinya redundant tapi dibiarkan sesuai file asli)
Route::redirect('/kompetensi-pelatihan', '/kompetensi-pelatihan', 301);

Route::view('/berita',  'pages.berita')->name('news');
Route::view('/panduan', 'pages.panduan')->name('panduan');

/* 🔹 Halaman Masuk */
Route::view('/masuk', 'pages.masuk')->name('masuk');

/* 🔹 Halaman Daftar (frontend baru) */
Route::get('/daftar', [PendaftaranController::class, 'showDaftar'])
    ->name('pendaftaran.daftar');

/* ===== end BERANDA & HOME ===== */

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
Route::get('pendaftaran/selesai/{id}', [PendaftaranController::class, 'selesai'])->name('pendaftaran.selesai');
Route::get('pendaftaran-testing', [PendaftaranController::class, 'testing'])->name('pendaftaran.testing');
Route::get('pendaftaran/download-file', [PendaftaranController::class, 'download_file'])->name('pendaftaran.download');

// Form baru pendaftaran
Route::get('pendaftaran-baru', fn () => view('registration-form-new'))->name('pendaftaran.baru');

// Peserta
Route::get('peserta/{peserta}/download-pdf', [PendaftaranController::class, 'download'])->name('peserta.download-pdf');
Route::get('peserta/download-bulk', [PendaftaranController::class, 'downloadBulk'])->name('peserta.download-bulk');

// Cetak Massal & Exports
Route::get('cetak-massal', [PendaftaranController::class, 'generateMassal'])->name('pendaftaran.generateMassal');

Route::get('/exports/pendaftaran/{pelatihan}/bulk',   [PendaftaranController::class, 'exportBulk'])
    ->name('exports.pendaftaran.bulk');

Route::get('/exports/pendaftaran/{pelatihan}/sample', [PendaftaranController::class, 'exportSample'])
    ->name('exports.pendaftaran.sample');

Route::get('/exports/pendaftaran/single/{pendaftaran}', [PendaftaranController::class, 'exportSingle'])
    ->name('exports.pendaftaran.single');

// Export laporan pelatihan
Route::middleware(['auth'])
    ->get('/export/report/pelatihan/{pelatihanId}', [ExportController::class, 'generateReportPdf'])
    ->name('export.report.pelatihan');

// Export laporan jawaban survei (PDF)
Route::middleware(['auth'])
    ->get('/reports/jawaban-survei/pdf/{pelatihanId}', [ExportController::class, 'pdfView'])
    ->name('reports.jawaban-survei.pdf');

// Step View pendaftaran
Route::prefix('pendaftaran/step')->group(function () {
    Route::view('1', 'peserta.pendaftaran.bio-peserta');
    Route::view('2', 'peserta.pendaftaran.bio-sekolah');
    Route::view('3', 'peserta.pendaftaran.lampiran');
    Route::view('4', 'peserta.pendaftaran.selesai');
});

// Template & monev pendaftaran
Route::view('template/instruktur', 'template_surat.instruktur');
Route::view('pendaftaran/monev', 'peserta.monev.pendaftaran');

/*
|--------------------------------------------------------------------------
| Dashboard (guest-friendly untuk pre/post test)
|--------------------------------------------------------------------------
*/
Route::prefix('dashboard')->name('dashboard.')->group(function () {
    Route::get('/', [DashboardController::class, 'home'])->name('home');
    Route::get('/profile', [DashboardController::class, 'profile'])->name('profile');
    Route::get('/materi', [DashboardController::class, 'materi'])->name('materi');
    Route::get('/materi/{materi}', [DashboardController::class, 'materiShow'])->name('materi.show');
    Route::get('/progress', [DashboardController::class, 'progress'])->name('progress');

    Route::post('set-peserta', [DashboardController::class, 'setPeserta'])->name('setPeserta');
    Route::post('unset-peserta', [DashboardController::class, 'unsetPeserta'])->name('unsetPeserta');

    Route::get('ajax/peserta/instansi-by-nama', [DashboardController::class, 'lookupInstansiByNama'])
        ->name('ajax.peserta.instansiByNama');

    Route::post('logout', [DashboardController::class, 'logout'])->name('logout');

    // Pretest routes
    Route::prefix('pretest')->name('pretest.')->group(function () {
        Route::get('/', [DashboardController::class, 'pretest'])->name('index');
        Route::get('result/{percobaan}', [DashboardController::class, 'pretestResult'])->name('result');
        Route::post('{percobaan}/submit', [DashboardController::class, 'pretestSubmit'])->name('submit');
        Route::get('{tes}/start', [DashboardController::class, 'pretestStart'])->name('start');
        Route::post('{tes}/begin', [DashboardController::class, 'pretestBegin'])->name('begin');
        Route::get('{tes}', [DashboardController::class, 'pretestShow'])->name('show');
    });

    // Posttest routes
    Route::prefix('posttest')->name('posttest.')->group(function () {
        Route::get('/', [DashboardController::class, 'posttest'])->name('index');
        Route::get('result/{percobaan}', [DashboardController::class, 'posttestResult'])->name('result');
        Route::post('{percobaan}/submit', [DashboardController::class, 'posttestSubmit'])->name('submit');
        Route::get('{tes}/start', [DashboardController::class, 'posttestStart'])->name('start');
        Route::post('{tes}/begin', [DashboardController::class, 'posttestBegin'])->name('begin');
        Route::get('{tes}', [DashboardController::class, 'posttestShow'])->name('show');
    });

    // Survey / monev dari dashboard
    Route::get('survey', [DashboardController::class, 'survey'])->name('survey');
    Route::post('survey/submit', [DashboardController::class, 'surveySubmit'])->name('survey.submit');
});

/*
|--------------------------------------------------------------------------
| Uploads admin
|--------------------------------------------------------------------------
*/
Route::post('/admin/uploads', [UploadController::class, 'store'])
    ->middleware(['web', 'auth'])
    ->name('admin.uploads.store');

/*
|--------------------------------------------------------------------------
| Pertanyaan (resource)
|--------------------------------------------------------------------------
*/
Route::resource('pertanyaan', PertanyaanController::class);

/*
|--------------------------------------------------------------------------
| Detail Pelatihan (public)
|--------------------------------------------------------------------------
*/
Route::get('/pelatihan/{slug}', [\App\Http\Controllers\PelatihanDetailController::class, 'show'])
    ->name('detail-pelatihan');

/*
|--------------------------------------------------------------------------
| Survey / Monev Public
|--------------------------------------------------------------------------
*/
Route::get('/survey', [SurveyController::class, 'index'])->name('survey.index');
Route::get('/survey/create', [SurveyController::class, 'create'])->name('survey.create');
Route::post('/survey', [SurveyController::class, 'store'])->name('survey.store');

Route::get('/complete', [SurveyController::class, 'complete'])->name('survey.complete');
Route::post('/start', [SurveyController::class, 'start'])->name('survey.start');
Route::post('/survey/check-credentials', [SurveyController::class, 'checkCredentials'])
    ->name('survey.checkCredentials');

Route::get('/survey/{peserta}/{order}', [SurveyController::class, 'show'])->name('survey.step');
Route::post('/survey/{peserta}/{order}', [SurveyController::class, 'update'])->name('survey.update');

Route::resource('/survey', SurveyController::class)->except(['index', 'create', 'store']);

/*
|--------------------------------------------------------------------------
| Excel Export
|--------------------------------------------------------------------------
*/
Route::get('/test-peserta', fn () => dd((new PesertaSheet(null))->collection()->take(5)));
Route::get('/test-lampiran', fn () => dd((new LampiranSheet(null))->collection()->take(5)));
Route::get('/export-peserta', fn () => Excel::download(new PesertaExport(), 'peserta.xlsx'))
    ->name('export.peserta');

// Rekap nilai tes (CSV) untuk dibuka di Excel
Route::get('/admin/tes/{tes}/rekap-download', [TesRekapDownloadController::class, 'download'])
    ->middleware(['web', 'auth'])
    ->name('tes.rekap.download');

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
Route::get('/send', fn () => Mail::to('23082010166@student.upnjatim.ac.id')->send(new TestMail()));

/*
|--------------------------------------------------------------------------
| Data Peserta API
|--------------------------------------------------------------------------
*/
Route::get('api/peserta', fn () => Peserta::with('lampiran', 'kompetensi', 'pelatihan', 'instansi')->get());

/*
|--------------------------------------------------------------------------
| Route tambahan / fix (duplikasi bawaan file aslimu dipertahankan)
|--------------------------------------------------------------------------
*/
Route::get('/download-file', [PendaftaranController::class, 'download_file'])->name('pendaftaran.download_file');
Route::get('/cetak-massal', [PendaftaranController::class, 'generateMassal'])->name('pendaftaran.generateMassal');
Route::get('testing', [PendaftaranController::class, 'testing'])->name('pendaftaran.testing');
Route::get('/peserta/{peserta}/download-pdf', [PendaftaranController::class, 'download'])->name('peserta.download-pdf');
Route::get('/peserta/download-bulk', [PendaftaranController::class, 'downloadBulk'])->name('peserta.download-bulk');

Route::get('/cek_icon', fn () => view('cek_icon'));

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

// Contoh route Browsershot (masih dikomentari di file asli, dibiarkan)
/*
Route::get('testing-export-pdf/{pelatihanId}', function ($pelatihanId) {
    $view = view('filament.resources.jawaban-surveis.pages.report-page', ['pelatihanId' => $pelatihanId])->render();
    $pdf = Browsershot::html($view)->pdf();

    return Response::make($pdf, 200, [
        'Content-Type'        => 'application/pdf',
        'Content-Disposition' => 'inline; filename="laporan.pdf"',
    ]);
});
*/
//kamar
Route::post('/otomasi-asrama/{pelatihan}', [AsramaOtomasiController::class, 'jalankanOtomasi'])
    ->name('otomasi.asrama');

/*
|--------------------------------------------------------------------------
| Sandbox (hanya di local)
|--------------------------------------------------------------------------
*/
if (App::isLocal()) {
    // Muat semua fungsi dari file playground kita
    require_once __DIR__ . '/sandbox.php';

    // Ini adalah satu-satunya route yang Anda butuhkan untuk semua testing
    Route::get('/test', function () {
        // CUKUP GANTI NAMA FUNGSI DI BAWAH INI
        // UNTUK MENGETES LOGIKA YANG BERBEDA.

        // $result = SurveyHasilKegiatan();
        $result = countKompetensi();
        // $result = testCreateDummyUser();
        // $result = testSomethingElse();

        // Tampilkan hasilnya
        return response()->json($result);
    });
}
