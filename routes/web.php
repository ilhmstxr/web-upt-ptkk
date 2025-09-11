<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;
use Livewire\Volt\Volt;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

use App\Http\Controllers\PesertaController;
use App\Http\Controllers\PendaftaranController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SuratController;
use App\Http\Controllers\SurveyController;
use App\Http\Controllers\PostTestController;

use App\Models\Peserta;
use App\Mail\TestMail;

use App\Exports\PesertaExport;
use App\Exports\PesertaSheet;
use App\Exports\LampiranSheet;

/*
|--------------------------------------------------------------------------
| Web Routes Final
|--------------------------------------------------------------------------
| Gabungan antara file versi pertama & kedua.
| Sudah mencakup: dashboard publik (guest-friendly), pre/post test, survey, export,
| route fix, dan tambahan resource survey.
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

// Route::prefix('api/flow')->middleware('api')->group(function () {
//     Route::post('/register', [RegistrationFlowController::class, 'register'])->name('flow.register');
//     Route::post('/biodata-sekolah', [RegistrationFlowController::class, 'saveSchool'])->name('flow.school');
//     Route::post('/biodata-diri', [RegistrationFlowController::class, 'savePersonal'])->name('flow.personal');
//     Route::post('/finish', [RegistrationFlowController::class, 'finish'])->name('flow.finish');
// });

// ============================
// Pendaftaran (Form Pendaftaran Baru)
// ============================
// Route::get('/pendaftaran', [PendaftaranController::class, 'index'])->name('pendaftaran');
// Route::post('/pendaftaran', [PendaftaranController::class, 'submit'])->name('pendaftaran.submit');


// Kalau mau akses langsung registration-form-new.blade.php
Route::resource('pendaftaran', PendaftaranController::class);
Route::get('pendaftaran-selesai', [PendaftaranController::class, 'selesai'])->name('pendaftaran.selesai');
Route::get('pendaftaran-testing', [PendaftaranController::class, 'testing'])->name('pendaftaran.testing');

Route::get('pendaftaran/download-file', [PendaftaranController::class, 'download_file'])->name('pendaftaran.download');
Route::get('peserta/{peserta}/download-pdf', [PendaftaranController::class, 'download'])->name('peserta.download-pdf');
Route::get('peserta/download-bulk', [PendaftaranController::class, 'downloadBulk'])->name('peserta.download-bulk');
Route::get('cetak-massal', [PendaftaranController::class, 'generateMassal'])->name('pendaftaran.generateMassal');
Route::get('pendaftaran-baru', fn() => view('registration-form-new'))->name('pendaftaran.baru');

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
    Route::get('/profile', [DashboardController::class, 'profile'])->name('profile');
    Route::get('/materi', [DashboardController::class, 'materi'])->name('materi');
    Route::get('/materi/{materi}', [DashboardController::class, 'materiShow'])->name('materi.show');
    Route::get('/progress', [DashboardController::class, 'progress'])->name('progress');

    // Pre-Test (guest-friendly)
    Route::prefix('pretest')->name('pretest.')->group(function () {
        Route::get('/', [DashboardController::class, 'pretest'])->name('index');
        Route::get('{tes}/start', [DashboardController::class, 'pretestStart'])->name('start');
        Route::post('{tes}/begin', [DashboardController::class, 'pretestBegin'])->name('begin');
        Route::post('{percobaan}/submit', [DashboardController::class, 'pretestSubmit'])->name('submit');
        Route::get('{tes}', [DashboardController::class, 'pretestShow'])->name('show');
	Route::get('result/{percobaan}', [DashboardController::class, 'pretestResult'])->name('result');

    });

    // Post-Test (guest-friendly)
    Route::prefix('posttest')->name('posttest.')->group(function () {
        Route::get('/', [DashboardController::class, 'posttest'])->name('index');
        Route::get('{tes}/start', [DashboardController::class, 'posttestStart'])->name('start');
        Route::post('{tes}/begin', [DashboardController::class, 'posttestBegin'])->name('begin');
        Route::get('{tes}', [DashboardController::class, 'posttestShow'])->name('show');
        Route::post('{percobaan}/submit', [DashboardController::class, 'posttestSubmit'])->name('submit');
        Route::get('result/{percobaan}', [DashboardController::class, 'posttestResult'])->name('result');
    });

    // Feedback
    Route::get('survey', [DashboardController::class, 'survey'])->name('survey');
    Route::post('survey/submit', [DashboardController::class, 'surveySubmit'])->name('survey.submit');
});

// ============================
// Detail Pelatihan (public)
// ============================
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

// ============================
// Survey / Monev
// ============================

// Route untuk manajemen Survey (Admin)
Route::get('/survey', [SurveyController::class, 'index'])->name('survey.index');
Route::get('/survey/create', [SurveyController::class, 'create'])->name('survey.create'); // Diubah
Route::post('/survey', [SurveyController::class, 'store'])->name('survey.store');         // Sebaiknya ada untuk menyimpan survey baru

// Route untuk peserta yang mengisi survey
Route::get('/complete', [SurveyController::class, 'complete'])->name('survey.complete');
Route::post('/start', [SurveyController::class, 'start'])->name('survey.start');
Route::post('/survey/check-credentials', [SurveyController::class, 'checkCredentials'])->name('survey.checkCredentials'); // Diubah

// Route utama untuk menampilkan & menyimpan jawaban per halaman
Route::get('/survey/{peserta}/{order}', [SurveyController::class, 'show'])->name('survey.show');
Route::post('/survey/{peserta}/{order}', [SurveyController::class, 'update'])->name('survey.update');
Route::resource('/survey', SurveyController::class);

// ============================
// Excel Export
// ============================
Route::get('/test-peserta', fn() => dd((new PesertaSheet(null))->collection()->take(5)));
Route::get('/test-lampiran', fn() => dd((new LampiranSheet(null))->collection()->take(5)));
Route::get('/export-peserta', fn() => Excel::download(new PesertaExport(), 'peserta.xlsx'))->name('export.peserta');

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
Route::get('/send', fn() => Mail::to('23082010166@student.upnjatim.ac.id')->send(new TestMail()));

// ============================
// Data Peserta API
// ============================
Route::get('api/peserta', fn() => Peserta::with('lampiran', 'bidang', 'pelatihan', 'instansi')->get());

// ============================
// Route tambahan / fix
// ============================
Route::get('/download-file', [PendaftaranController::class, 'download_file'])->name('pendaftaran.download_file');
Route::get('/cetak-massal', [PendaftaranController::class, 'generateMassal'])->name('pendaftaran.generateMassal');
Route::get('pendaftaran_selesai', [PendaftaranController::class, 'selesai'])->name('pendaftaran.selesai');
Route::get('testing', [PendaftaranController::class, 'testing'])->name('pendaftaran.testing');
Route::get('/peserta/{peserta}/download-pdf', [PendaftaranController::class, 'download'])->name('peserta.download-pdf');
Route::get('/peserta/download-bulk', [PendaftaranController::class, 'downloadBulk'])->name('peserta.download-bulk');

Route::get('/cek_icon', fn() => view('cek_icon'));

// ============================
// Auth (login, register, dll.)
// ============================
require __DIR__ . '/auth.php';
