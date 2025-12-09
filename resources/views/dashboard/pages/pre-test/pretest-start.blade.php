{{-- resources/views/dashboard/pages/pre-test/pretest-start.blade.php
   atau dashboard/pages/tes/do.blade.php kalau kamu pakai satu view untuk pre/post
--}}
@extends('dashboard.layouts.main')

@section('title', 'Pre-Test')
@section('page-title', 'Pre-Test: Soal '.($currentQuestionIndex + 1))

@section('content')
<div class="bg-white p-6 rounded-2xl shadow-md fade-in">

    @if(isset($pertanyaan))
        @php
            $jawabanCollection = $percobaan->jawabanUser ?? collect();
            $totalSoal = $pertanyaanList->count();
            $terjawab = $jawabanCollection->count();
            $progress = $totalSoal > 0 ? round(($terjawab / $totalSoal) * 100, 2) : 0;

            $durasiMenit = (int) ($tes->durasi_menit ?? 0);
        @endphp

        {{-- HEADER: Timer + Progress --}}
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 mb-5">
            @if($durasiMenit > 0)
                <div class="text-sm text-slate-600">
                    Sisa waktu: <span id="timer" class="font-semibold text-slate-900"></span>
                </div>
            @endif

            <div class="w-full md:w-1/2">
                <div class="w-full bg-slate-200 rounded-full h-3 overflow-hidden">
                    <div class="bg-blue-600 h-3 rounded-full transition-all"
                         style="width: {{ $progress }}%;"></div>
                </div>
                <p class="text-xs mt-1 text-slate-500">
                    Terjawab: {{ $terjawab }} / {{ $totalSoal }} ({{ $progress }}%)
                </p>
            </div>
        </div>

        {{-- FORM --}}
        <form id="form-tes"
              action="{{ route('dashboard.pretest.submit', ['percobaan' => $percobaan->id]) }}"
              method="POST"
              class="space-y-5">
            @csrf

            {{-- GRID NOMOR SOAL --}}
            <div class="flex flex-wrap gap-2">
                @foreach($pertanyaanList as $idx => $p)
                    @php
                        $answered = $jawabanCollection->contains('pertanyaan_id', $p->id);
                        $isActive = $idx === $currentQuestionIndex;

                        $btnClass = $isActive
                            ? 'bg-blue-600 text-white ring-2 ring-blue-200'
                            : ($answered
                                ? 'bg-emerald-100 text-emerald-700 hover:bg-emerald-200'
                                : 'bg-slate-100 text-slate-600 hover:bg-slate-200');
                    @endphp

                    <button type="button"
                            onclick="goToQuestion({{ $idx }})"
                            class="px-3 py-1 rounded-lg text-sm font-semibold transition {{ $btnClass }}">
                        {{ $p->nomor ?? ($idx+1) }}
                    </button>
                @endforeach
            </div>

            {{-- PERTANYAAN --}}
            <div class="bg-slate-50 border border-slate-100 rounded-xl p-4">
                <div class="text-sm text-slate-500 mb-1">
                    Soal {{ $currentQuestionIndex + 1 }} dari {{ $totalSoal }}
                </div>

                <div class="text-slate-800 font-medium leading-relaxed">
                    {{ $pertanyaan->nomor ?? ($currentQuestionIndex+1) }}.
                    {{ $pertanyaan->teks_pertanyaan ?? '-' }}
                </div>

                {{-- Gambar pertanyaan --}}
                @if(!empty($pertanyaan->gambar))
                    <div class="mt-3">
                        <img src="{{ asset('images/pertanyaan/'.$pertanyaan->gambar) }}"
                             alt="Gambar soal"
                             class="max-h-72 rounded-lg shadow cursor-zoom-in hover:opacity-95 transition"
                             onclick="openImageModal('{{ asset('images/pertanyaan/'.$pertanyaan->gambar) }}')">
                    </div>
                @endif
            </div>

            {{-- OPSI JAWABAN --}}
            <div class="space-y-2">
                @if($pertanyaan->opsiJawabans && $pertanyaan->opsiJawabans->count() > 0)
                    @php
                        $existing = $jawabanCollection->firstWhere('pertanyaan_id', $pertanyaan->id);
                        $existingOpsiId = $existing->opsi_jawaban_id ?? null;
                    @endphp

                    @foreach($pertanyaan->opsiJawabans as $opsi)
                        @php
                            $checked = $existingOpsiId == $opsi->id;
                        @endphp

                        <label class="flex items-center gap-3 p-3 border rounded-xl hover:bg-slate-50 cursor-pointer transition">
                            <input
                                type="radio"
                                name="jawaban[{{ $pertanyaan->id }}]"
                                value="{{ $opsi->id }}"
                                class="h-4 w-4"
                                {{ $checked ? 'checked' : '' }}
                                required
                            >

                            @if(!empty($opsi->gambar))
                                <img src="{{ asset('images/opsi-jawaban/'.$opsi->gambar) }}"
                                     alt="Gambar opsi"
                                     class="w-12 h-12 object-cover rounded cursor-zoom-in hover:opacity-95 transition"
                                     onclick="openImageModal('{{ asset('images/opsi-jawaban/'.$opsi->gambar) }}')">
                            @endif

                            <div class="text-slate-800 text-sm leading-relaxed">
                                {{ $opsi->teks_opsi ?? '-' }}
                            </div>
                        </label>
                    @endforeach
                @else
                    <div class="text-red-600 text-sm">
                        Belum ada opsi jawaban untuk pertanyaan ini.
                    </div>
                @endif
            </div>

            <input type="hidden" name="percobaan_id" value="{{ $percobaan->id }}">
            <input type="hidden" id="next_q_input" name="next_q" value="{{ $currentQuestionIndex + 1 }}">

            {{-- NAVIGASI --}}
            <div class="flex items-center justify-between pt-2">
                @if($currentQuestionIndex > 0)
                    <button type="button"
                            onclick="goToQuestion({{ $currentQuestionIndex - 1 }})"
                            class="px-4 py-2 bg-slate-500 text-white rounded-lg hover:bg-slate-600 transition">
                        ← Sebelumnya
                    </button>
                @else
                    <span></span>
                @endif

                <button type="submit"
                        class="px-5 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-semibold">
                    {{ ($currentQuestionIndex + 1) == $totalSoal ? 'Selesai ✓' : 'Selanjutnya →' }}
                </button>
            </div>
        </form>

    @else
        {{-- Semua soal selesai --}}
        <div class="text-center py-8">
            <div class="text-slate-500">Semua soal telah selesai.</div>
            <div class="text-lg font-semibold mt-2">
                Nilai Anda: {{ $percobaan->skor ?? 0 }}
                <span class="{{ ($percobaan->lulus ?? false) ? 'text-emerald-600' : 'text-red-600' }}">
                    ({{ ($percobaan->lulus ?? false) ? 'Lulus' : 'Tidak Lulus' }})
                </span>
            </div>

            <a href="{{ route('dashboard.pretest.result', ['percobaan' => $percobaan->id ?? 0]) }}"
               class="mt-4 inline-flex px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition">
                Lihat Hasil Detail
            </a>
        </div>
    @endif
</div>

{{-- MODAL ZOOM GAMBAR --}}
<div id="imageModal"
     class="fixed inset-0 bg-black/75 flex items-center justify-center hidden z-50"
     onclick="closeImageModal()">
    <div class="relative max-w-5xl w-full px-4" onclick="event.stopPropagation()">
        <button onclick="closeImageModal()"
                class="absolute -top-4 -right-2 bg-white text-black rounded-full p-2 shadow hover:bg-gray-200">
            ✕
        </button>
        <img id="modalImage" src="" class="max-w-full max-h-[90vh] rounded shadow-lg mx-auto">
    </div>
</div>

@push('scripts')
<script>
/** pindah nomor soal tanpa double submit */
function goToQuestion(idx){
    const nextInput = document.getElementById('next_q_input');
    nextInput.value = idx;
    document.getElementById('form-tes').submit();
}

/** modal gambar */
function openImageModal(src) {
    document.getElementById('modalImage').src = src;
    document.getElementById('imageModal').classList.remove('hidden');
}
function closeImageModal() {
    document.getElementById('imageModal').classList.add('hidden');
    document.getElementById('modalImage').src = '';
}

/** timer aman */
(function(){
    const durasiMenit = {{ (int) ($tes->durasi_menit ?? 0) }};
    if(durasiMenit <= 0) return;

    let duration = durasiMenit * 60;
    const serverStart = new Date("{{ $percobaan->waktu_mulai ?? now() }}").getTime();
    const nowServer = new Date("{{ now() }}").getTime();
    let elapsed = Math.floor((nowServer - serverStart) / 1000);
    let remaining = duration - elapsed;
    if (remaining < 0) remaining = 0;

    function pad(n){ return n.toString().padStart(2,'0'); }

    function updateTimer() {
        const el = document.getElementById('timer');
        if (!el) return;

        if (remaining <= 0) {
            el.textContent = "00:00:00";
            clearInterval(interval);
            const form = document.getElementById("form-tes");
            if (form) form.submit(); // auto-submit
            return;
        }

        const hours = pad(Math.floor(remaining/3600));
        const minutes = pad(Math.floor((remaining%3600)/60));
        const seconds = pad(remaining%60);
        el.textContent = `${hours}:${minutes}:${seconds}`;
        remaining--;
    }

    updateTimer();
    const interval = setInterval(updateTimer, 1000);
})();
</script>
@endpush
@endsection
