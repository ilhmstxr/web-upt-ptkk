<?php

namespace App\Http\Controllers;

use App\Models\PendaftaranPelatihan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

use App\Exports\TokenAssessmentExport;
use Maatwebsite\Excel\Facades\Excel;

class TokenController extends Controller
{
    /**
     * Download daftar token assessment dalam bentuk Excel (.xlsx)
     */
    public function download(Request $request)
    {
        $filename = 'token-assessment-' . now()->format('Ymd_His') . '.xlsx';
        $status = $request->get('status', 'diterima');
        $all = $request->boolean('all');

        return Excel::download(new TokenAssessmentExport($status, $all), $filename);
    }
}
