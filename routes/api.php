<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RegistrationController extends Controller
{
    public function submit(Request $request)
    {
        // Process the form data here (e.g., save to the database)

        // After processing, redirect to the success route
        return redirect()->route('registration.success');
    }
}