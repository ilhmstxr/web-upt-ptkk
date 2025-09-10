<?php

use App\Http\Controllers\PesertaController;
use App\Http\Controllers\RegistrationFlowController;
use Illuminate\Support\Facades\Route;

// Route::post('/flow/register', [RegistrationFlowController::class, 'register']);
Route::get('/peserta/search', [PesertaController::class, 'search'])->name('peserta.search');

