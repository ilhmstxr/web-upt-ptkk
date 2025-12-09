<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureAssessmentLogin
{
    public function handle(Request $request, Closure $next)
    {
        if (!session()->has('peserta_id') && !session()->has('pesertaSurvei_id')) {
            return redirect()->route('assessment.login')
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        return $next($request);
    }
}
