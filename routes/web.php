<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;
use Livewire\Volt\Volt;

use App\Http\Controllers\PesertaController;
use App\Http\Controllers\PendaftaranController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SuratController;

use App\Exports\PesertaExport;
use App\Models\Peserta;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PesertaSheet;
use App\Exports\LampiranSheet;
use App\Http\Controllers\SurveyController;

Route::get('/test-peserta', function () {
    // ambil 5 data pertama dari PesertaSheet
    dd((new PesertaSheet())->collection()->take(5));
});

Route::get('/test-lampiran', function () {
    // ambil 5 data pertama dari LampiranSheet
    dd((new LampiranSheet())->collection()->take(5));
});

Route::get('/export-peserta', function () {
    return Excel::download(new PesertaExport(), 'peserta.xlsx');
});



/*
|--------------------------------------------------------------------------
| Landing Page
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Halaman utama (landing page)
// Route::get('/', function () {
//     $pelatihans = Pelatihan::orderBy('tanggal_mulai', 'desc')->take(10)->get();
//     return view('landing', compact('pelatihans'));
// })->name('landing');

// ============================
// API untuk Flow Pendaftaran (Step-by-Step)
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
Route::get('/pendaftaran', [PendaftaranController::class, 'index'])->name('pendaftaran');
Route::post('/pendaftaran', [PendaftaranController::class, 'submit'])->name('pendaftaran.submit');

// Kalau mau akses langsung registration-form-new.blade.php
Route::get('/pendaftaran-baru', function () {
    return view('registration-form-new'); // resources/views/registration-form-new.blade.php
})->name('pendaftaran.baru');

/*
|--------------------------------------------------------------------------
| Detail Pelatihan
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
| Dashboard
|--------------------------------------------------------------------------
*/
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

/*
|--------------------------------------------------------------------------
| Settings (Volt)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');
    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});

// Rute untuk mengirim email (gunakan salah satu, jangan duplikasi)
Route::get('/send', function () {
    Mail::to(['23082010166@student.upnjatim.ac.id'])->send(new TestMail());
});



// Rute-rute ini tampaknya untuk pengujian, disarankan untuk dihapus setelah selesai
Route::get('1', function () {
    return view('peserta.pendaftaran.bio-peserta');
});
Route::get('2', function () {
    return view('peserta.pendaftaran.bio-sekolah');
});
Route::get('3', function () {
    return view('peserta.pendaftaran.lampiran');
});
Route::get('4', function () {
    // return view('peserta.pendaftaran.test');
    return view('peserta.pendaftaran.selesai');
    // return "arsa";
});

Route::get('5', function () {
    return view('template_surat.instruktur');
});

Route::get('6', function () {
    return view('peserta.monev.pendaftaran');
});



Route::get('test-peserta', function () {
    new \App\Models\Peserta();
    $peserta = Peserta::with('lampiran', 'bidang', 'pelatihan', 'instansi')
        ->get();
    return $peserta;
    return "peserta";
    // return view('peserta.pendaftaran.lampiran');
});

// Route::get('pendaftaran_selesai', function () {
//     return view('peserta.pendaftaran.selesai');
// });


// route fix
// route pendaftaran
Route::get('/download-file', [PendaftaranController::class, 'download_file'])->name('pendaftaran.download_file');
Route::get('/cetak-massal', [PendaftaranController::class, 'generateMassal'])->name('pendaftaran.generateMassal');

Route::get('pendaftaran_selesai', [PendaftaranController::class, 'selesai'])->name('pendaftaran.selesai');
Route::get('testing', [PendaftaranController::class, 'testing'])->name('pendaftaran.testing');
Route::get('/peserta/{peserta}/download-pdf', [PendaftaranController::class, 'download'])
    ->name('peserta.download-pdf');
Route::get('/peserta/download-bulk', [PendaftaranController::class, 'downloadBulk'])
    ->name('peserta.download-bulk');

// route monev
Route::resource('/survey', SurveyController::class);

// Rute untuk menampilkan setiap langkah/bagian survei
// Menggunakan route model binding untuk {participant} dan slug untuk {section_slug}
Route::get('/survey/{peserta}/{order}', [SurveyController::class, 'show'])->name('survey.show');

// Rute untuk menyimpan jawaban dari setiap langkah survei
Route::post('/survey/{peserta}/{order}', [SurveyController::class, 'update'])->name('survey.update');

// Rute untuk halaman "Selesai"
Route::get('/complete', [SurveyController::class, 'complete'])->name('survey.complete');

Route::post('/survey_checkCredentials', [SurveyController::class, 'checkCredentials'])->name('survey.checkCredentials');


// Rute untuk autentikasi (login, register, dll.)
require __DIR__ . '/auth.php';
