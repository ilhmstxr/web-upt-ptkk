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

use App\Exports\PesertaExport;
use App\Exports\PesertaSheet;
use App\Exports\LampiranSheet;
use App\Models\Peserta;
use App\Http\Controllers\PostTestController;

Route::get('/dashboard/posttest/start', [PostTestController::class, 'start'])->name('dashboard.posttest.start');
Route::post('/dashboard/posttest/submit', [PostTestController::class, 'submit'])->name('dashboard.posttest.submit');
Route::get('/dashboard/posttest/result', [PostTestController::class, 'result'])->name('dashboard.posttest.result');

// ============================
// Test Excel Export
// ============================
Route::get('/test-peserta', function () {
    dd((new PesertaSheet())->collection()->take(5));
});

Route::get('/test-lampiran', function () {
    dd((new LampiranSheet())->collection()->take(5));
});

Route::get('/export-peserta', function () {
    return Excel::download(new PesertaExport(), 'peserta.xlsx');
});

// ============================
// Landing Page (sementara dinonaktifkan)
// ============================
// Route::get('/', function () {
//     $pelatihans = Pelatihan::orderBy('tanggal_mulai', 'desc')->take(10)->get();
//     return view('landing', compact('pelatihans'));
// })->name('landing');

// ============================
// Pendaftaran
// ============================
Route::get('/pendaftaran', [PendaftaranController::class, 'index'])->name('pendaftaran');
Route::post('/pendaftaran', [PendaftaranController::class, 'submit'])->name('pendaftaran.submit');

Route::get('/pendaftaran-baru', function () {
    return view('registration-form-new');
})->name('pendaftaran.baru');

Route::get('/download-file', [PendaftaranController::class, 'download_file'])->name('pendaftaran.download_file');
Route::get('/cetak-massal', [PendaftaranController::class, 'generateMassal'])->name('pendaftaran.generateMassal');
Route::get('pendaftaran_selesai', [PendaftaranController::class, 'selesai'])->name('pendaftaran.selesai');
Route::get('testing', [PendaftaranController::class, 'testing'])->name('pendaftaran.testing');
Route::get('/peserta/{peserta}/download-pdf', [PendaftaranController::class, 'download'])->name('peserta.download-pdf');
Route::get('/peserta/download-bulk', [PendaftaranController::class, 'downloadBulk'])->name('peserta.download-bulk');

// ============================
// Detail Pelatihan
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
// Dashboard
// ============================
Route::prefix('dashboard')->name('dashboard.')->group(function () {
    Route::get('/', [DashboardController::class, 'home'])->name('home');
    Route::get('/profile', [DashboardController::class, 'profile'])->name('profile');
    Route::get('/materi', [DashboardController::class, 'materi'])->name('materi');
    Route::get('/materi/{materi}', [DashboardController::class, 'materiShow'])->name('materi.show');
    Route::get('/pretest', [DashboardController::class, 'pretest'])->name('pretest');
    Route::get('/pretest/start', [DashboardController::class, 'pretestStart'])->name('pretest.start');
    Route::get('/posttest', [DashboardController::class, 'posttest'])->name('posttest');
    Route::get('/posttest/start', [DashboardController::class, 'posttestStart'])->name('posttest.start');
    Route::get('/feedback', [DashboardController::class, 'feedback'])->name('feedback');
    Route::post('/feedback/submit', [DashboardController::class, 'feedbackSubmit'])->name('feedback.submit');
    Route::get('/progress', [DashboardController::class, 'progress'])->name('progress');
});

// ============================
// Settings (Volt)
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
Route::get('/send', function () {
    Mail::to(['23082010166@student.upnjatim.ac.id'])->send(new TestMail());
});

// ============================
// Pengujian Sementara
// ============================
Route::get('1', function () { return view('peserta.pendaftaran.bio-peserta'); });
Route::get('2', function () { return view('peserta.pendaftaran.bio-sekolah'); });
Route::get('3', function () { return view('peserta.pendaftaran.lampiran'); });
Route::get('4', function () { return view('peserta.pendaftaran.selesai'); });
Route::get('5', function () { return view('template_surat.instruktur'); });
Route::get('6', function () { return view('peserta.monev.pendaftaran'); });

// ============================
// Data Peserta API
// ============================
Route::get('test-peserta', function () {
    $peserta = Peserta::with('lampiran', 'bidang', 'pelatihan', 'instansi')->get();
    return $peserta;
});

// ============================
// Survey / Monev
// ============================
Route::resource('/survey', SurveyController::class);

Route::get('/survey/{peserta}/{order}', [SurveyController::class, 'show'])->name('survey.show');
Route::post('/survey/{peserta}/{order}', [SurveyController::class, 'update'])->name('survey.update');
Route::get('/complete', [SurveyController::class, 'complete'])->name('survey.complete');
Route::post('/survey_checkCredentials', [SurveyController::class, 'checkCredentials'])->name('survey.checkCredentials');

// ============================
// Auth Routes
// ============================
require __DIR__ . '/auth.php';
