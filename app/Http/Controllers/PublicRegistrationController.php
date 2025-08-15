<?php

namespace App\Http\Controllers;

use App\Models\Registration;
use Illuminate\Http\Request;

class PublicRegistrationController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|string|max:20',
            'gender' => 'required',
            'training_id' => 'required|exists:trainings,id',
        ]);

        $registration = Registration::create($validated);

        // Trigger notifikasi WA & Email
        // event(new RegistrationCreated($registration));

        return redirect()->route('thankyou')->with('success', 'Pendaftaran berhasil!');
    }
}
