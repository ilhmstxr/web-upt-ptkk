<?php

use App\Http\Controllers\LampiranController;
use App\Http\Controllers\PendaftaranController;
use App\Mail\TestMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PendaftaranController;

<<<<<<< HEAD
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Rute untuk halaman utama dan pendaftaran
Route::get('/', function () {
    return redirect()->route('pendaftaran.create');
});

// Route::get('/pendaftaran', [PendaftaranController::class, 'create'])->name('pendaftaran.create');
// Route::post('/pendaftaran', [PendaftaranController::class, 'store'])->name('pendaftaran.store');


// Rute bawaan Laravel untuk dashboard dan settings
Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');
=======
// ============================
// Halaman Utama (Landing Page)
// ============================
Route::get('/', function () {
    return view('landing');
})->name('landing');

// ============================
// Pendaftaran
// ============================
// Form pendaftaran
Route::get('/pendaftaran', [PendaftaranController::class, 'index'])->name('pendaftaran');
>>>>>>> e6dc1422201d5091cf1859ee3cd60966025e80cd

// Proses submit pendaftaran
Route::post('/pendaftaran', [PendaftaranController::class, 'submit'])->name('pendaftaran.submit');

// ============================
// Halaman Detail Pelatihan
// ============================
Route::get('/pelatihan/{kompetensi}', function ($kompetensi) {
    $kompetensiList = [
        'tata-boga',
        'tata-busana',
        'tata-kecantikan',
        'teknik-pendingin-dan-tata-udara',
    ];

<<<<<<< HEAD

Route::get('/send', function () {
    Mail::to(['23082010166@student.upnjatim.ac.id'])->send(new TestMail());
});


Route::get('/send', function () {
    Mail::to(['23082010166@student.upnjatim.ac.id'])->send(new TestMail());
});

Route::resource('pendaftaran', PendaftaranController::class);

route::get('1', function () {
    return view('peserta.pendaftaran.bio-peserta');
});
route::get('2', function () {
    return view('peserta.pendaftaran.bio-sekolah');
});
route::get('3', function () {
    return view('peserta.pendaftaran.lampiran');
});
route::get('4', function () {
    return view('peserta.pendaftaran.selesai');
});
// Rute untuk autentikasi (login, register, dll.)
require __DIR__ . '/auth.php';
=======
    if (!in_array($kompetensi, $kompetensiList)) {
        abort(404);
    }

    return view('detail-pelatihan', compact('kompetensi'));
})->name('detail-pelatihan');
>>>>>>> e6dc1422201d5091cf1859ee3cd60966025e80cd
