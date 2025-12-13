{{-- resources/views/dashboard/pages/post-test/posttest-start.blade.php --}}
@extends('dashboard.layouts.main')

@section('title', 'Post-Test')
@section('page-title', 'Post-Test: Soal '.($currentQuestionIndex + 1))

@section('content')
<div class="bg-white p-6 rounded-2xl shadow-md fade-in">

    @if(isset($pertanyaan))
        @php
            $jawabanCollection = $percobaan->jawabanUser ?? collect();
            $totalSoal  = $pertanyaanList->count();
            $terjawab   = $jawabanCollection->count();
            $progress   = $totalSoal > 0 ? round(($terjawab / $totalSoal) * 100, 2) : 0;

            $durasiMenit = (int) ($tes->durasi_menit ?? 0);
            if ($durasiMenit <= 0) $durasiMenit = 30;

            $existing = $jawabanCollection->firstWhere('pertanyaan_id', $pertanyaan->id);
            $existingOpsiId = $existing->opsi_jawaban_id ?? null;

            $soalImgUrl = !empty($pertanyaan->gambar)
                ? asset('storage/'.$pertanyaan->gambar)
                : null;
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

        <form id="form-tes"
              action="{{ route('dashboard.posttest.submit', ['percobaan' => $percobaan->id]) }}"
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

            {{-- Pertanyaan --}}
            <div class="bg-slate-50 border border-slate-100 rounded-xl p-4">
                <div class="text-sm text-slate-500 mb-1">
                    Soal {{ $currentQuestionIndex + 1 }} dari {{ $totalSoal }}
                </div>
                <div class="text-slate-800 font-semibold text-base md:text-lg">
                    {{ $currentQuestionIndex + 1 }}.
                    @php($plain = trim(strip_tags($pertanyaan->teks_pertanyaan ?? '')))
                    {!! $plain !== '' ? $pertanyaan->teks_pertanyaan : '-' !!}
                </div>
            </div>

            {{-- Gambar pertanyaan --}}
            @if($soalImgUrl)
                <img src="{{ $soalImgUrl }}"
                     class="mb-4 rounded-lg shadow cursor-pointer hover:scale-[1.01] transition"
                     onclick="openImageModal('{{ $soalImgUrl }}')">
            @endif

            {{-- Opsi jawaban --}}
            <div class="space-y-2 mb-6">
                @forelse($pertanyaan->opsiJawabans ?? [] as $opsi)
                    @php
                        $checked = $existingOpsiId == $opsi->id;
                        $opsiImgUrl = !empty($opsi->gambar)
                            ? asset('storage/'.$opsi->gambar)
                            : null;
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
                            @if($opsiImgUrl)
                                <img src="{{ $opsiImgUrl }}"
                                     class="w-16 h-16 mb-2 rounded cursor-pointer hover:scale-[1.03] transition"
                                     onclick="openImageModal('{{ $opsiImgUrl }}')">
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
            <input type="hidden" id="next_q_input" name="next_q" value="{{ $currentQuestionIndex + 1 }}">

            {{-- FLAG KECURANGAN --}}
            <input type="hidden" name="cheat_flag" id="cheat_flag" value="0">

            {{-- Navigasi prev/next --}}
            <div class="flex justify-between">
                @if($currentQuestionIndex > 0)
                    <button type="button"
                            onclick="goToQuestion({{ $currentQuestionIndex - 1 }})"
                            class="px-4 py-2 bg-slate-500 text-white rounded-lg hover:bg-slate-600 transition text-sm font-semibold">
                        ← Sebelumnya
                    </button>
                @else
                    <span></span>
                @endif

                <button type="submit"
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

{{-- Modal Cheat --}}
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

<script>
function goToQuestion(idx){
    document.getElementById('next_q_input').value = idx;
    document.getElementById('form-tes').submit();
}

(function(){
    let duration = {{ $durasiMenit * 60 }};
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

// anti-cheat
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
@endsection
