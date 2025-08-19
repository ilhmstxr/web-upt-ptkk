<?php

use App\Http\Controllers\PendaftaranController;
use App\Mail\TestMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

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

// Halaman utama dan pendaftaran
Route::get('/', function () {
    return view('landing');
})->name('landing');

// Rute untuk pendaftaran (menggunakan resource controller)
Route::resource('pendaftaran', PendaftaranController::class);

// Rute untuk halaman detail pelatihan
Route::get('/pelatihan/{kompetensi}', function ($kompetensi) {
    $kompetensiList = [
        'tata-boga',
        'tata-busana',
        'tata-kecantikan',
        'teknik-pendingin-dan-tata-udara',
    ];

    if (!in_array($kompetensi, $kompetensiList)) {
        abort(404);
    }

    return view('detail-pelatihan', compact('kompetensi'));
})->name('detail-pelatihan');

// Rute untuk mengirim email (gunakan salah satu, jangan duplikasi)
Route::get('/send', function () {
    Mail::to(['23082010166@student.upnjatim.ac.id'])->send(new TestMail());
});

// Rute bawaan Laravel untuk dashboard dan settings
Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Rute untuk autentikasi (login, register, dll.)
require __DIR__ . '/auth.php';

// Rute-rute ini tampaknya digunakan untuk pengujian dan dapat dihapus setelah selesai
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
    return view('peserta.pendaftaran.selesai');
});