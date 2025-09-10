@extends('dashboard.layouts.main')

@section('title', 'Post-Test')
@section('page-title', 'Post-Test: Soal '.($currentQuestionIndex + 1))

@section('content')
<div class="bg-white p-6 rounded-xl shadow-md fade-in">

    @if(isset($pertanyaan))

        {{-- Timer --}}
        @php
            $duration = $elapsedSeconds ?? ($percobaan->waktu_mulai ? now()->diffInSeconds($percobaan->waktu_mulai) : 0);
            $jawabanCollection = $percobaan->jawabanUser ?? collect();
            $totalSoal = $pertanyaanList->count();
            $terjawab = $jawabanCollection->count();
            $progress = $totalSoal > 0 ? round(($terjawab / $totalSoal) * 100, 2) : 0;
        @endphp
        <p class="text-sm text-gray-600 mb-4">
            Waktu berjalan: <span id="timer">{{ gmdate('H:i:s', $duration) }}</span>
        </p>

        {{-- Progress Bar --}}
        <div class="mb-4">
            <div class="w-full bg-gray-200 rounded-full h-4">
                <div class="bg-blue-600 h-4 rounded-full" style="width: {{ $progress }}%;"></div>
            </div>
            <p class="text-sm mt-1 text-gray-600">
                Terjawab: {{ $terjawab }} / {{ $totalSoal }}
            </p>
        </div>

        {{-- Nomor Soal --}}
        <div class="mb-4 flex flex-wrap gap-2">
            @foreach($pertanyaanList as $p)
                @php
                    $answered = $jawabanCollection->contains('pertanyaan_id', $p->id);
                    $status = $answered ? 'bg-green-500' : 'bg-red-500';
                @endphp
                <a href="{{ route('dashboard.posttest.show', ['tes' => $tes->id, 'q' => $loop->index]) }}"
                   class="px-3 py-1 rounded text-white {{ $status }}">
                    {{ $p->nomor }}
                </a>
            @endforeach
        </div>

        {{-- Pertanyaan --}}
        <p class="text-gray-700 mb-4">
            {{ $pertanyaan->nomor ?? '?' }}. {{ $pertanyaan->teks_pertanyaan ?? '-' }}
        </p>

        {{-- Gambar pertanyaan --}}
        @if(!empty($pertanyaan->gambar))
            <img src="{{ asset('storage/'.$pertanyaan->gambar) }}" class="mb-4 rounded shadow">
        @endif

        {{-- Form Jawaban --}}
        <form action="{{ route('dashboard.posttest.submit', ['percobaan' => $percobaan->id]) }}?q={{ $currentQuestionIndex }}" method="POST">
            @csrf
            <div class="space-y-2 mb-4">
                @if($pertanyaan->opsiJawabans && $pertanyaan->opsiJawabans->count() > 0)
                    @foreach($pertanyaan->opsiJawabans as $opsi)
                        @php
                            $existing = $jawabanCollection->firstWhere('pertanyaan_id', $pertanyaan->id);
                            $checked = $existing && ($existing->opsi_jawabans_id ?? 0) == $opsi->id;
                        @endphp
                        <label class="block p-2 border rounded hover:bg-gray-100 cursor-pointer">
                            <input
                                type="radio"
                                name="jawaban[{{ $pertanyaan->id }}]"
                                value="{{ $opsi->id }}"
                                class="mr-2"
                                {{ $checked ? 'checked' : '' }}
                                required
                            >
                            @if(!empty($opsi->gambar))
                                <img src="{{ asset('storage/'.$opsi->gambar) }}" class="inline-block w-12 h-12 mr-2 rounded align-middle">
                            @endif
                            <span class="align-middle">{{ $opsi->teks_opsi ?? '-' }}</span>
                        </label>
                    @endforeach
                @else
                    <p class="text-red-500">Belum ada opsi jawaban untuk pertanyaan ini.</p>
                @endif
            </div>

            {{-- Hidden --}}
            <input type="hidden" name="percobaan_id" value="{{ $percobaan->id }}">

            {{-- Navigasi --}}
            <div class="flex justify-between">
                @if($currentQuestionIndex > 0)
                    <a href="{{ route('dashboard.posttest.show', ['tes' => $tes->id, 'q' => $currentQuestionIndex - 1]) }}"
                       class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600 transition">
                        Sebelumnya
                    </a>
                @else
                    <span></span>
                @endif

                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                    {{ $currentQuestionIndex + 1 == $totalSoal ? 'Selesai' : 'Selanjutnya' }}
                </button>
            </div>
        </form>

    @else
        {{-- Semua soal selesai --}}
        <p class="text-gray-500">Semua soal telah selesai.</p>
        <p class="text-lg font-semibold mt-2">
            Nilai Anda: {{ $percobaan->skor ?? 0 }} / {{ $totalSoal ?? 0 }}
            - Status:
            <span class="{{ ($percobaan->lulus ?? false) ? 'text-green-600' : 'text-red-600' }}">
                {{ ($percobaan->lulus ?? false) ? 'Lulus' : 'Tidak Lulus' }}
            </span>
        </p>

        <a href="{{ route('dashboard.posttest.result', ['percobaan' => $percobaan->id ?? 0]) }}"
           class="mt-4 inline-block px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition">
           Lihat Hasil Detail
        </a>
    @endif
</div>

{{-- Timer JS --}}
<script>
(function(){
    const serverStart = "{{ $percobaan->waktu_mulai ?? now() }}";
    const startTime = new Date(serverStart).getTime();

    function pad(n){ return n.toString().padStart(2,'0'); }

    function updateTimer() {
        const now = Date.now();
        let elapsed = Math.floor((now - startTime)/1000);
        if(elapsed < 0) elapsed = 0;

        const hours = pad(Math.floor(elapsed/3600));
        const minutes = pad(Math.floor((elapsed%3600)/60));
        const seconds = pad(elapsed%60);

        const el = document.getElementById('timer');
        if(el) el.textContent = `${hours}:${minutes}:${seconds}`;
    }

    updateTimer();
    setInterval(updateTimer, 1000);
})();
</script>
@endsection
