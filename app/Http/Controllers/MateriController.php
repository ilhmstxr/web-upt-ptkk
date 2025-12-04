<?php

namespace App\Http\Controllers;

use App\Models\Materi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MateriController extends Controller
{
    /**
     * Menampilkan daftar semua materi.
     * Rute: GET /dashboard/materi (dashboard.materi.index)
     */
    public function index()
    {
        // Mendapatkan semua materi, diurutkan berdasarkan urutan atau judul
        $materiList = Materi::orderBy('order')->get();

        // Anda dapat menambahkan logika untuk menandai materi yang sudah diselesaikan oleh pengguna (jika ada model Progress/UserMateri)

        return view('dashboard.pages.materi.index', compact('materiList'));
    }

    /**
     * Menampilkan detail materi tertentu.
     * Rute: GET /dashboard/materi/{materi} (dashboard.materi.show)
     */
    public function show(Materi $materi)
    {
        // Jika materi tidak ditemukan, Laravel akan otomatis melemparkan 404 karena Type Hinting.

        // Anda dapat menambahkan logika untuk mencatat bahwa pengguna telah melihat materi ini.
        // Contoh: $materi->markAsViewed(Auth::id());

        return view('dashboard.pages.materi.show', compact('materi'));
    }

    /**
     * Menandai materi tertentu sebagai telah diselesaikan.
     * Rute: POST /dashboard/materi/{materi}/complete (dashboard.materi.complete)
     */
    public function complete(Request $request, Materi $materi)
    {
        // Pastikan pengguna sudah login
        if (!Auth::check()) {
            return back()->with('error', 'Anda harus login untuk menyelesaikan materi.');
        }

        // --- Logika untuk menandai materi selesai ---
        
        // Contoh implementasi: Mencari atau membuat record progress
        // Asumsi: Ada relasi atau model yang menyimpan status progres pengguna terhadap materi (misalnya, UserMateri/Progress)
        // Di sini saya menggunakan contoh sederhana yang mungkin perlu disesuaikan dengan struktur database Anda.
        
        $user = Auth::user();
        
        // Cari atau buat record progres
        // Ganti dengan model dan logika progres yang sesuai di aplikasi Anda
        // Contoh menggunakan DB:
        /*
        \DB::table('user_materi_progress')->updateOrInsert(
            [
                'user_id' => $user->id,
                'materi_id' => $materi->id,
            ],
            [
                'is_completed' => true,
                'completed_at' => now()
            ]
        );
        */

        // Contoh dengan Model Eloquent (asumsi ada model UserMateriProgress)
        /*
        $user->materiProgress()->updateOrCreate(
            ['materi_id' => $materi->id],
            ['is_completed' => true]
        );
        */

        // Jika Anda hanya ingin menyimpan ke sesi atau database sementara:
        $request->session()->put("materi_completed.{$materi->id}", true);


        return redirect()->route('dashboard.materi.index')
                         ->with('success', 'Materi "' . $materi->judul . '" berhasil ditandai sebagai selesai!');
    }
}