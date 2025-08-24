<?php

namespace App\Http\Controllers;

use App\Http\Requests\StartSurveyRequest;
use App\Http\Requests\StoreParticipantRequest;
use App\Http\Requests\StoreSurveyRequest;
use App\Models\Survey;
use App\Models\Jawaban;
use App\Models\Peserta;
use Illuminate\Http\Request;

class SurveyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return redirect()->route('survey.create');
    }

    /**
     * Menampilkan formulir untuk membuat resource baru (memulai survei).
     * (Sebelumnya: showStartForm)
     */
    public function create()
    {
        return view('peserta.monev.survey.start');
    }

    /**
     * Menyimpan resource yang baru dibuat ke dalam storage.
     * (Sebelumnya: storepeserta)
     */
    /**
     * Menyimpan resource yang baru dibuat ke dalam storage.
     */
    public function store(Request $request)
    {
        // return $request;
        $peserta = Peserta::where('email', $request->email)
            // ->where('nama', $request->nama)
            ->first();

        // return $peserta;
        // return $request;
        if (!$peserta) {
            return redirect()->back()->withErrors(['message' => 'Data peserta tidak ditemukan. Silahkan periksa kembali email dan nama Anda.'])->withInput();
        }

        $firstSection = Survey::orderBy('order', 'asc')->first();
        // return $firstSection;
        if (!$firstSection) {
            return redirect()->route('survey.complete')->with('message', 'Belum ada survei yang tersedia.');
        }

        return redirect()->route('survey.show', [
            'peserta' => $peserta->id,
            'order' => $firstSection->order
        ]);
    }

    /**
     * Menampilkan resource yang spesifik (satu langkah/seksi survei).
     * (Sebelumnya: showSurveyStep)
     */
    public function show(Peserta $peserta, $order)
    {
        // return $peserta;
        // return $order;
        $section = Survey::where('order', $order)->firstOrFail();
        // return $section;
        $questions = $section->questions()->orderBy('order', 'asc')->get();

        return view('peserta.monev.survey.step', compact('peserta', 'section', 'questions'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }


    /**
     * Memperbarui resource yang spesifik di dalam storage.
     * (Sebelumnya: storeSurveyStep)
     */
    public function update(StoreSurveyRequest $request, Peserta $peserta, $order)
    {
        return $request;
        $section = Survey::where('order', $order)->firstOrFail();

        $validated = $request->validated();
        $answers = $validated['jawaban'];
        $comments = $validated['comments'] ?? null;

        foreach ($answers as $questionId => $value) {
            Jawaban::updateOrCreate(
                [
                    'peserta_id' => $peserta->id,
                    'pertanyaan_id' => $questionId,
                ],
                [
                    'value' => $value,
                ]
            );
        }

        // Simpan komentar jika ada
        if ($comments) {
            $peserta->comments()->updateOrCreate(
                ['survey_id' => $section->id],
                ['content' => $comments]
            );
        }

        // Tentukan langkah selanjutnya
        $nextSection = Survey::where('order', '>', $section->order)
            ->orderBy('order', 'asc')
            ->first();

        if ($nextSection) {
            return redirect()->route('survey.show', [
                'peserta' => $peserta->id,
                'order' => $nextSection->order
            ]);
        }

        // Jika tidak ada langkah selanjutnya, arahkan ke halaman selesai
        return redirect()->route('survey.complete');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }


    /**
     * Menampilkan halaman "Terima Kasih".
     * (Sebelumnya: showCompletePage)
     */
    public function complete()
    {
        return view('peserta.monev.survey.complete');
    }

    public function checkEmail(Request $request)
    {
        // Validasi sederhana untuk memastikan email dikirim
        $request->validate(['email' => 'required|email']);

        // Cari peserta berdasarkan email dan nama yang dikirim
        $exists = Peserta::where('email', $request->email)
            // ->where('nama', $request->nama) // Cek nama juga untuk akurasi
            ->exists();

        return $exists;
        // Kembalikan response dalam format JSON
        return response()->json(['exists' => $exists]);
    }
}
