{{-- resources/views/dashboard/pages/pre-test/pretest-start.blade.php --}}
@extends('dashboard.layouts.main')

@section('title', 'Pre-Test')
@section('page-title', 'Pre-Test: Soal '.($currentQuestionIndex + 1))

@section('content')
<div class="bg-white p-6 rounded-2xl shadow-md fade-in">

    @if(isset($pertanyaan))
        @php
            $jawabanCollection = $percobaan->jawabanUser ?? collect();
            $totalSoal  = $pertanyaanList->count();
            $terjawab   = $jawabanCollection->count();
            $progress   = $totalSoal > 0 ? round(($terjawab / $totalSoal) * 100, 2) : 0;

            // default durasi 30 menit kalau null / 0
            $durasiMenit = (int) ($tes->durasi_menit ?? 0);
            if ($durasiMenit <= 0) $durasiMenit = 30;

            $existing = $jawabanCollection->firstWhere('pertanyaan_id', $pertanyaan->id);
            $existingOpsiId = $existing->opsi_jawaban_id ?? null;

            $soalImgUrl = !empty($pertanyaan->gambar)
                ? asset('storage/'.$pertanyaan->gambar)
                : null;
        @endphp

        {{-- HEADER: Timer + Progress --}}
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 mb-5">
            <div class="text-sm text-slate-600">
                Sisa waktu: <span id="timer" class="font-semibold text-slate-900"></span>
            </div>

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

        {{-- PERINGATAN UJIAN KETAT (anti-cheat) --}}
        <div class="mb-4 p-3 rounded-lg bg-amber-50 border border-amber-200 text-[11px] text-amber-800 flex gap-2">
            <span class="text-lg leading-none mt-0.5">⚠️</span>
            <div>
                <p class="font-semibold text-xs mb-1">Aturan Ujian:</p>
                <ul class="list-disc list-inside space-y-0.5">
                    <li>Jangan pindah tab, mengecilkan jendela, atau meninggalkan halaman.</li>
                    <li>Aktivitas tersebut akan terdeteksi sebagai <strong>kecurangan</strong>.</li>
                    <li>Jika terdeteksi kecurangan, <strong>nilai akhir tidak akan ditampilkan</strong>.</li>
                </ul>
            </div>
        </div>

        {{-- FORM --}}
        <form id="form-tes"
              action="{{ route('dashboard.pretest.submit', ['percobaan' => $percobaan->id]) }}"
              method="POST"
              class="space-y-5">
            @csrf

            {{-- GRID NOMOR SOAL: tampil 1-20 / 1-N --}}
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
                            class="w-9 h-9 flex items-center justify-center rounded-lg text-sm font-semibold transition {{ $btnClass }}">
                        {{ $loop->iteration }}
                    </button>
                @endforeach
            </div>

            {{-- PERTANYAAN --}}
            <div class="bg-slate-50 border border-slate-100 rounded-xl p-4">
                <div class="text-sm text-slate-500 mb-1">
                    Soal {{ $currentQuestionIndex + 1 }} dari {{ $totalSoal }}
                </div>

                <div class="text-slate-800 font-medium leading-relaxed">
                    {{ $currentQuestionIndex + 1 }}.
                    @php($plain = trim(strip_tags($pertanyaan->teks_pertanyaan ?? '')))
                    {!! $plain !== '' ? $pertanyaan->teks_pertanyaan : '-' !!}
                </div>

                {{-- Gambar pertanyaan --}}
                @if($soalImgUrl)
                    <div class="mt-3">
                        <img src="{{ $soalImgUrl }}"
                             alt="Gambar soal"
                             class="max-h-72 rounded-lg shadow cursor-zoom-in hover:opacity-95 transition"
                             onclick="openImageModal('{{ $soalImgUrl }}')">
                    </div>
                @endif
            </div>

            {{-- OPSI JAWABAN --}}
            <div class="space-y-2">
                @forelse($pertanyaan->opsiJawabans ?? [] as $opsi)
                    @php
                        $checked = $existingOpsiId == $opsi->id;
                        $opsiImgUrl = !empty($opsi->gambar)
                            ? asset('storage/'.$opsi->gambar)
                            : null;
                    @endphp

                    <label class="flex items-start gap-3 p-3 border rounded-xl hover:bg-slate-50 cursor-pointer transition">
                        <input
                            type="radio"
                            name="jawaban[{{ $pertanyaan->id }}]"
                            value="{{ $opsi->id }}"
                            class="mt-1 h-4 w-4"
                            {{ $checked ? 'checked' : '' }}
                            required
                        >

                        <div class="flex-1">
                            @if($opsiImgUrl)
                                <img src="{{ $opsiImgUrl }}"
                                     alt="Gambar opsi"
                                     class="w-16 h-16 object-cover rounded cursor-zoom-in hover:opacity-95 transition mb-1"
                                     onclick="openImageModal('{{ $opsiImgUrl }}')">
                            @endif

                            <div class="text-slate-800 text-sm leading-relaxed">
                                {{ $opsi->teks_opsi ?? '-' }}
                            </div>
                        </div>
                    </label>
                @empty
                    <div class="text-red-600 text-sm">
                        Belum ada opsi jawaban untuk pertanyaan ini.
                    </div>
                @endforelse
            </div>

            <input type="hidden" name="percobaan_id" value="{{ $percobaan->id }}">
            <input type="hidden" id="next_q_input" name="next_q" value="{{ $currentQuestionIndex + 1 }}">

            {{-- FLAG KECURANGAN --}}
            <input type="hidden" name="cheat_flag" id="cheat_flag" value="0">

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

{{-- MODAL CHEAT --}}
<div id="cheatModal"
     class="fixed inset-0 bg-black/70 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-xl shadow-xl max-w-md w-full p-5 space-y-3 text-sm">
        <div class="flex items-start gap-3">
            <span class="text-2xl mt-0.5">⚠️</span>
            <div>
                <h3 class="font-semibold text-slate-900 mb-1">
                    Aktivitas mencurigakan terdeteksi
                </h3>
                <p class="text-slate-700 text-xs md:text-sm">
                    Sistem mendeteksi bahwa Anda meninggalkan halaman tes, berpindah tab, atau mengecilkan jendela.
                    Sesuai tata tertib, percobaan ini akan diberi tanda <strong>kecurangan</strong> dan
                    <strong>nilai akhir tidak akan ditampilkan</strong>.
                </p>
            </div>
        </div>

        <div class="flex justify-end">
            <button type="button"
                    onclick="closeCheatModal()"
                    class="px-3 py-1.5 bg-blue-600 text-white text-xs rounded-lg hover:bg-blue-700 transition">
                Saya mengerti
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
function goToQuestion(idx){
    document.getElementById('next_q_input').value = idx;
    document.getElementById('form-tes').submit();
}

function openImageModal(src) {
    document.getElementById('modalImage').src = src;
    document.getElementById('imageModal').classList.remove('hidden');
}
function closeImageModal() {
    document.getElementById('imageModal').classList.add('hidden');
    document.getElementById('modalImage').src = '';
}

// TIMER (default 30 menit)
(function(){
    let duration = {{ $durasiMenit * 60 }};
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
            document.getElementById("form-tes")?.submit();
            return;
        }

        const h = pad(Math.floor(remaining/3600));
        const m = pad(Math.floor((remaining%3600)/60));
        const s = pad(remaining%60);
        el.textContent = `${h}:${m}:${s}`;
        remaining--;
    }

    updateTimer();
    const interval = setInterval(updateTimer, 1000);
})();

// ANTI-CHEAT
let cheatTriggered = false;
function triggerCheat(reason) {
    if (cheatTriggered) return;
    cheatTriggered = true;

    document.getElementById('cheat_flag').value = '1';
    document.getElementById('cheatModal').classList.remove('hidden');
    console.warn('Cheat detected:', reason);
}
function closeCheatModal() {
    document.getElementById('cheatModal').classList.add('hidden');
}
window.addEventListener('blur', () => triggerCheat('window_blur'));
document.addEventListener('visibilitychange', () => {
    if (document.visibilityState === 'hidden') triggerCheat('tab_hidden');
});
let initialHeight = window.innerHeight;
window.addEventListener('resize', () => {
    if (window.innerHeight < initialHeight * 0.6) triggerCheat('window_resize');
});
</script>
@endpush
@endsection
