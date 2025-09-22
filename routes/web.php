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

/*
|--------------------------------------------------------------------------
| Web Routes Final (Rapih, tanpa duplikat)
|--------------------------------------------------------------------------
*/

// ============================
// Landing
// ============================
Route::get('/', fn() => view('landing'))->name('landing');

// Compatibility route 'home'
Route::get('/home', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard.home');
    }
    return redirect()->route('landing');
})->name('home');


// ============================
// Pendaftaran
// ============================
Route::resource('pendaftaran', PendaftaranController::class);
Route::get('pendaftaran-selesai', [PendaftaranController::class, 'selesai'])->name('pendaftaran.selesai');
Route::get('pendaftaran/selesai/{id}', [PendaftaranController::class, 'selesai'])->name('pendaftaran.selesai.byid');
Route::get('pendaftaran-testing', [PendaftaranController::class, 'testing'])->name('pendaftaran.testing');
Route::get('pendaftaran/download-file', [PendaftaranController::class, 'download_file'])->name('pendaftaran.download');
Route::get('pendaftaran-baru', fn() => view('registration-form-new'))->name('pendaftaran.baru');

// Peserta
Route::get('peserta/{peserta}/download-pdf', [PendaftaranController::class, 'download'])->name('peserta.download-pdf');
Route::get('peserta/download-bulk', [PendaftaranController::class, 'downloadBulk'])->name('peserta.download-bulk');

// Cetak Massal
Route::get('cetak-massal', [PendaftaranController::class, 'generateMassal'])->name('pendaftaran.generateMassal');
Route::get('pendaftaran-baru', fn() => view('registration-form-new'))->name('pendaftaran.baru');
Route::get('/exports/pendaftaran/{pelatihan}/bulk',   [PendaftaranController::class, 'exportBulk'])
    ->name('exports.pendaftaran.bulk');

Route::get('/exports/pendaftaran/{pelatihan}/sample', [PendaftaranController::class, 'exportSample'])
    ->name('exports.pendaftaran.sample');

Route::get('/exports/pendaftaran/single/{pendaftaran}', [PendaftaranController::class, 'exportSingle'])
    ->name('exports.pendaftaran.single');

// Step View
Route::prefix('pendaftaran/step')->group(function () {
    Route::view('1', 'peserta.pendaftaran.bio-peserta');
    Route::view('2', 'peserta.pendaftaran.bio-sekolah');
    Route::view('3', 'peserta.pendaftaran.lampiran');
    Route::view('4', 'peserta.pendaftaran.selesai');
});

// Template surat & monev
Route::view('template/instruktur', 'template_surat.instruktur');
Route::view('pendaftaran/monev', 'peserta.monev.pendaftaran');


// ============================
// Dashboard (guest-friendly untuk pre/post test)
// ============================
Route::prefix('dashboard')->name('dashboard.')->group(function () {
    // Dashboard utama
    Route::get('/', [DashboardController::class, 'home'])->name('home');
    Route::get('profile', [DashboardController::class, 'profile'])->name('profile');
    Route::get('materi', [DashboardController::class, 'materi'])->name('materi');
    Route::get('materi/{materi}', [DashboardController::class, 'materiShow'])->name('materi.show');
    Route::get('progress', [DashboardController::class, 'progress'])->name('progress');

    // Set/Unset Peserta (gunakan POST untuk keamanan & konsistensi)
    Route::post('set-peserta', [DashboardController::class, 'setPeserta'])->name('setPeserta');
    Route::post('unset-peserta', [DashboardController::class, 'unsetPeserta'])->name('unsetPeserta');

    // Logout (dashboard-specific logout that clears peserta_id & session)
    Route::post('logout', [DashboardController::class, 'logout'])->name('logout');

    // Pre-Test
    Route::prefix('pretest')->name('pretest.')->group(function () {
        Route::get('/', [DashboardController::class, 'pretest'])->name('index');

        // start sebelum menangkap '{tes}'
        Route::get('{tes}/start', [DashboardController::class, 'pretestStart'])->name('start');
        Route::post('{tes}/begin', [DashboardController::class, 'pretestBegin'])->name('begin');

        // submit jawaban (menggunakan percobaan id pada URI)
        Route::post('{percobaan}/submit', [DashboardController::class, 'pretestSubmit'])->name('submit');

        // hasil perlu diletakkan sebelum route '{tes}' supaya 'result' tidak tertangkap sebagai tes id
        Route::get('result/{percobaan}', [DashboardController::class, 'pretestResult'])->name('result');

        // show pertanyaan per tes (letakkan setelah route spesifik di atas)
        Route::get('{tes}', [DashboardController::class, 'pretestShow'])->name('show');
        Route::get('result/{percobaan}', [DashboardController::class, 'pretestResult'])->name('result');
    });

    // Post-Test
    Route::prefix('posttest')->name('posttest.')->group(function () {
        Route::get('/', [DashboardController::class, 'posttest'])->name('index');

        Route::get('{tes}/start', [DashboardController::class, 'posttestStart'])->name('start');
        Route::post('{tes}/begin', [DashboardController::class, 'posttestBegin'])->name('begin');

        // hasil posttest harus diletakkan sebelum '{tes}'
        Route::get('result/{percobaan}', [DashboardController::class, 'posttestResult'])->name('result');

        Route::post('{percobaan}/submit', [DashboardController::class, 'posttestSubmit'])->name('submit');
        Route::get('{tes}', [DashboardController::class, 'posttestShow'])->name('show');
    });

    // Feedback (survey untuk peserta)
    Route::get('survey', [DashboardController::class, 'survey'])->name('survey');
    Route::post('survey/submit', [DashboardController::class, 'surveySubmit'])->name('survey.submit');
});


// ============================
// Detail Pelatihan (public)
// ============================
Route::get('pelatihan/{kompetensi}', function ($kompetensi) {
    $kompetensiList = [
        'tata-boga',
        'tata-busana',
        'tata-kecantikan',
        'teknik-pendingin-dan-tata-udara',
    ];
    abort_unless(in_array($kompetensi, $kompetensiList), 404);
    return view('detail-pelatihan', compact('kompetensi'));
})->name('detail-pelatihan');


// ============================
// Survey / Monev
// ============================
// Admin Survey (CRUD admin)
Route::get('survey', [SurveyController::class, 'index'])->name('survey.index');
Route::get('survey/create', [SurveyController::class, 'create'])->name('survey.create');
Route::post('survey', [SurveyController::class, 'store'])->name('survey.store');

// Peserta Survey (public flow)
Route::get('complete', [SurveyController::class, 'complete'])->name('survey.complete');
Route::post('start', [SurveyController::class, 'start'])->name('survey.start');
Route::post('survey/check-credentials', [SurveyController::class, 'checkCredentials'])->name('survey.checkCredentials');

// Halaman Survey per peserta
Route::get('survey/{peserta}/{order}', [SurveyController::class, 'show'])->name('survey.show');
Route::post('survey/{peserta}/{order}', [SurveyController::class, 'update'])->name('survey.update');

// Resource Survey (tambahan: sisakan route yang diperlukan)
Route::resource('survey', SurveyController::class)->except(['index','create','store','show','update']);


// ============================
// Excel Export
// ============================
Route::get('test-peserta', fn() => dd((new PesertaSheet(null))->collection()->take(5)));
Route::get('test-lampiran', fn() => dd((new LampiranSheet(null))->collection()->take(5)));
Route::get('export-peserta', fn() => Excel::download(new PesertaExport(), 'peserta.xlsx'))->name('export.peserta');


// ============================
// Settings (Volt) - requires auth
// ============================
Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');
    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});


// ============================
// Mail Testing
// ============================
Route::get('send', fn() => Mail::to('23082010166@student.upnjatim.ac.id')->send(new TestMail()));


// ============================
// Data Peserta API
// ============================
Route::get('api/peserta', fn() => Peserta::with('lampiran', 'bidang', 'pelatihan', 'instansi')->get());


// ============================
// Extra / Testing
// ============================
Route::get('cek_icon', fn() => view('cek_icon'));


// ============================
// Auth (login, register, dll.)
// ============================
require __DIR__ . '/auth.php';
