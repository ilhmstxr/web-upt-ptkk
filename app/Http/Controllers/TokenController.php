<?php

namespace App\Http\Controllers;

use App\Models\PendaftaranPelatihan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class TokenController extends Controller
{
    /**
     * Download daftar token assessment dalam bentuk CSV
     * Optional query:
     * - ?status=diterima  (default: diterima)
     * - ?all=1            (ambil semua status)
     */
    public function download(Request $request): StreamedResponse
    {
        $filename = 'token-assessment-' . now()->format('Ymd_His') . '.csv';

        // Query: default hanya "diterima", kecuali all=1
        $query = PendaftaranPelatihan::query()
            ->with(['peserta:id,nama', 'pelatihan:id,nama_pelatihan'])
            ->whereNotNull('assessment_token')
            ->where('assessment_token', '<>', '');

        if (! $request->boolean('all')) {
            $status = $request->get('status', 'diterima');
            $query->where('status_pendaftaran', $status);
        }

        // Untuk data besar: jangan ->get() dulu, iterasi pakai cursor()
        $rows = $query->orderBy('id', 'asc')->cursor();

        return response()->streamDownload(function () use ($rows) {
            // Bersihkan output buffer supaya file tidak corrupt
            if (ob_get_level() > 0) {
                ob_end_clean();
            }

            $handle = fopen('php://output', 'w');

            // BOM UTF-8 supaya Excel tidak salah encoding
            fprintf($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));

            // Header CSV
            fputcsv($handle, [
                'ID Pendaftaran',
                'Nama Peserta',
                'Pelatihan',
                'Nomor Registrasi',
                'Token Assessment',
                'Tanggal Pendaftaran',
            ]);

            foreach ($rows as $p) {
                // tanggal_pendaftaran bisa string atau null
                $tgl = $p->tanggal_pendaftaran
                    ? Carbon::parse($p->tanggal_pendaftaran)->format('Y-m-d H:i')
                    : '';

                fputcsv($handle, [
                    $p->id,
                    $p->peserta->nama ?? '',
                    $p->pelatihan->nama_pelatihan ?? '',
                    $p->nomor_registrasi ?? '',
                    $p->assessment_token ?? '',
                    $tgl,
                ]);
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }
}
