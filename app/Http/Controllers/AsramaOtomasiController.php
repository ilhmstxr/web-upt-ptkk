<?php

namespace App\Http\Controllers;

use App\Services\AsramaAllocator;
use App\Models\Pelatihan;
use App\Models\PenempatanAsrama;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Mail;
use App\Mail\PenempatanMail;

class AsramaOtomasiController extends Controller
{
    /**
     * Finalize + allocate penempatan asrama per pelatihan (manual route).
     */
    public function finalizePenempatan(int $pelatihanId, AsramaAllocator $allocator): RedirectResponse
    {
        $pelatihan = Pelatihan::findOrFail($pelatihanId);

        // 1) generate kamar dari config/session untuk pelatihan ini
        $allocator->generateRoomsFromConfig($pelatihan->id, session('kamars') ?? config('kamars'));

        // 2) allocate peserta
        $result = $allocator->allocatePesertaPerPelatihan($pelatihan->id);

        // 3) kirim email ke yang baru ditempatkan
        $penempatanBaru = PenempatanAsrama::with('pendaftaran.peserta', 'kamars.asramas', 'pelatihan')
            ->whereIn('pendaftaran_id', collect($result['details'])->pluck('pendaftaran_id'))
            ->get();

        foreach ($penempatanBaru as $p) {
            $email = $p->pendaftaran->peserta->email ?? null;
            if ($email) {
                Mail::to($email)->send(new PenempatanMail($p));
            }
        }

        return back()->with(
            'success',
            "Finalize penempatan: OK {$result['ok']}, skipped {$result['skipped_already_assigned']}, gagal {$result['failed_full']}"
        );
    }
}
