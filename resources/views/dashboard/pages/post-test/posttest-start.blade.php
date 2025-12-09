@extends('dashboard.layouts.main')

@section('title', 'Post-Test')
@section('page-title', 'Post-Test: Soal '.($currentQuestionIndex + 1))

@section('content')
<div class="bg-white p-6 rounded-xl shadow-md fade-in">

    @if(isset($pertanyaan))
        @php
            $jawabanCollection = $percobaan->jawabanUser ?? collect();
            $totalSoal = $pertanyaanList->count();
            $terjawab = $jawabanCollection->count();
            $progress = $totalSoal > 0 ? round(($terjawab / $totalSoal) * 100, 2) : 0;

            $existing = $jawabanCollection->firstWhere('pertanyaan_id', $pertanyaan->id);
            $existingOpsi = $existing?->opsi_jawaban_id;
        @endphp

        {{-- Header progress + timer --}}
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 mb-4">
            <div class="text-sm text-slate-600">
                Terjawab <b>{{ $terjawab }}</b> / {{ $totalSoal }}
            </div>

            <div class="text-sm text-slate-600">
                Sisa waktu:
                <span id="timer" class="font-bold text-blue-700"></span>
            </div>
        </div>

        {{-- Progress Bar --}}
        <div class="mb-5">
            <div class="w-full bg-slate-200 rounded-full h-3 overflow-hidden">
                <div class="bg-blue-600 h-3 rounded-full" style="width: {{ $progress }}%;"></div>
            </div>
            <p class="text-xs mt-1 text-slate-500">Progress: {{ $progress }}%</p>
        </div>

        <form id="form-tes"
              action="{{ route('dashboard.posttest.submit', ['percobaan' => $percobaan->id]) }}"
              method="POST">
            @csrf

            {{-- Navigasi nomor soal --}}
            <div class="mb-5 flex flex-wrap gap-2">
                @foreach($pertanyaanList as $p)
                    @php
                        $answered = $jawabanCollection->contains('pertanyaan_id', $p->id);
                        $isActive = $p->id === $pertanyaan->id;
                    @endphp
                    <button type="submit"
                            name="next_q"
                            value="{{ $loop->index }}"
                            class="px-3 py-1.5 rounded text-xs font-semibold border transition
                                   {{ $isActive ? 'bg-blue-600 text-white border-blue-600' : ($answered ? 'bg-emerald-50 text-emerald-700 border-emerald-200 hover:bg-emerald-100' : 'bg-slate-50 text-slate-600 border-slate-200 hover:bg-slate-100') }}">
                        {{ $p->nomor ?? ($loop->index+1) }}
                    </button>
                @endforeach
            </div>

            {{-- Pertanyaan --}}
            <div class="mb-4">
                <div class="text-sm text-slate-500 mb-1">
                    Soal {{ $currentQuestionIndex + 1 }} dari {{ $totalSoal }}
                </div>
                <div class="text-slate-800 font-semibold text-base md:text-lg">
                    {{ $pertanyaan->nomor ?? ($currentQuestionIndex+1) }}.
                    {{ $pertanyaan->teks_pertanyaan ?? '-' }}
                </div>
            </div>

            {{-- Gambar pertanyaan --}}
            @if(!empty($pertanyaan->gambar))
                <img src="{{ asset('images/pertanyaan/'.$pertanyaan->gambar) }}"
                     class="mb-4 rounded-lg shadow cursor-pointer hover:scale-[1.01] transition"
                     onclick="openImageModal('{{ asset('images/pertanyaan/'.$pertanyaan->gambar) }}')">
            @endif

            {{-- Opsi jawaban --}}
            <div class="space-y-2 mb-6">
                @forelse($pertanyaan->opsiJawabans ?? [] as $opsi)
                    @php
                        $checked = $existingOpsi == $opsi->id;
                    @endphp

                    <label class="flex items-start gap-3 p-3 border rounded-lg hover:bg-slate-50 cursor-pointer transition">
                        <input
                            type="radio"
                            name="jawaban[{{ $pertanyaan->id }}]"
                            value="{{ $opsi->id }}"
                            class="mt-1"
                            {{ $checked ? 'checked' : '' }}
                            required
                        />

                        <div class="flex-1">
                            @if(!empty($opsi->gambar))
                                <img src="{{ asset('images/opsi-jawaban/'.$opsi->gambar) }}"
                                     class="w-16 h-16 mb-2 rounded cursor-pointer hover:scale-[1.03] transition"
                                     onclick="openImageModal('{{ asset('images/opsi-jawaban/'.$opsi->gambar) }}')">
                            @endif
                            <div class="text-slate-700">
                                {{ $opsi->teks_opsi ?? '-' }}
                            </div>
                        </div>
                    </label>
                @empty
                    <p class="text-red-500 text-sm">Belum ada opsi jawaban untuk pertanyaan ini.</p>
                @endforelse
            </div>

            <input type="hidden" name="percobaan_id" value="{{ $percobaan->id }}">

            {{-- Navigasi prev/next --}}
            <div class="flex justify-between">
                @if($currentQuestionIndex > 0)
                    <button type="submit"
                            name="next_q"
                            value="{{ $currentQuestionIndex - 1 }}"
                            class="px-4 py-2 bg-slate-500 text-white rounded-lg hover:bg-slate-600 transition text-sm font-semibold">
                        ← Sebelumnya
                    </button>
                @else
                    <span></span>
                @endif

                <button type="submit"
                        name="next_q"
                        value="{{ $currentQuestionIndex + 1 }}"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-sm font-semibold">
                    {{ $currentQuestionIndex + 1 == $totalSoal ? 'Selesai' : 'Selanjutnya →' }}
                </button>
            </div>
        </form>

    @else
        {{-- Semua soal selesai --}}
        <div class="text-center py-6">
            <div class="text-lg font-semibold text-slate-800">Semua soal telah selesai.</div>
            <div class="mt-2 text-sm text-slate-600">
                Nilai Anda:
                <span class="font-bold text-emerald-700">{{ $percobaan->skor ?? 0 }}</span>
            </div>

            <a href="{{ route('dashboard.posttest.result', ['percobaan' => $percobaan->id]) }}"
               class="mt-4 inline-block px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition text-sm font-semibold">
                Lihat Hasil Detail →
            </a>
        </div>
    @endif
</div>

{{-- Modal Zoom --}}
<div id="imageModal" class="fixed inset-0 bg-black/80 hidden z-50 flex items-center justify-center" onclick="closeImageModal()">
    <div class="relative max-w-3xl w-full mx-4" onclick="event.stopPropagation()">
        <button onclick="closeImageModal()"
                class="absolute -top-4 -right-4 bg-white text-black rounded-full p-2 shadow hover:bg-slate-200">
            ✕
        </button>
        <img id="modalImage" src="" class="max-w-full max-h-screen rounded shadow-lg mx-auto">
    </div>
</div>

<script>
(function(){
    let duration = {{ (int)($tes->durasi_menit ?? 0) * 60 }};
    const serverStart = new Date("{{ $percobaan->waktu_mulai ?? now() }}").getTime();
    const nowServer   = new Date("{{ now() }}").getTime();
    let elapsed  = Math.floor((nowServer - serverStart) / 1000);
    let remaining = duration - elapsed;
    if (remaining < 0) remaining = 0;

    function pad(n){ return n.toString().padStart(2,'0'); }

    function updateTimer() {
        const timerEl = document.getElementById('timer');
        if (!timerEl) return;

        if (remaining <= 0) {
            timerEl.textContent = "00:00:00";
            clearInterval(interval);
            document.getElementById("form-tes")?.submit();
            return;
        }

        const hours   = pad(Math.floor(remaining/3600));
        const minutes = pad(Math.floor((remaining%3600)/60));
        const seconds = pad(remaining%60);
        timerEl.textContent = `${hours}:${minutes}:${seconds}`;
        remaining--;
    }

    updateTimer();
    const interval = setInterval(updateTimer, 1000);
})();

function openImageModal(src) {
    document.getElementById('modalImage').src = src;
    document.getElementById('imageModal').classList.remove('hidden');
}
function closeImageModal() {
    document.getElementById('imageModal').classList.add('hidden');
    document.getElementById('modalImage').src = '';
}
</script>
@endsection
