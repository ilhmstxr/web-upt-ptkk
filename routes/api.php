<?php

use App\Http\Controllers\PesertaController;
use App\Http\Controllers\RegistrationFlowController;
use Illuminate\Support\Facades\Route;

// Route::post('/flow/register', [RegistrationFlowController::class, 'register']);
Route::get('/peserta/search', [PesertaController::class, 'search'])->name('peserta.search');

class RegistrationController extends Controller
{
    public function submit(Request $request)
    {
        // Process the form data here (e.g., save to the database)

        // After processing, redirect to the success route
        return redirect()->route('registration.success');
    }
}