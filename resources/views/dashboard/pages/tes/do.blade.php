{{-- resources/views/dashboard/pages/tes/do.blade.php --}}
@extends('dashboard.layouts.main')

@php
    /** @var string $mode  pre-test | post-test | monev|survei */
    $isMonev = $mode === 'monev' || $mode === 'survei';
    $pageTitle = match($mode) {
        'pre-test' => 'Pre-Test',
        'post-test' => 'Post-Test',
        default => 'Monev / Survei',
    };

    $routePrefix = match($mode) {
        'pre-test' => 'pretest',
        'post-test' => 'posttest',
        default => 'monev',
    };
@endphp

@section('title', $pageTitle)
@section('page-title', ($isMonev ? '📊 ' : '').$pageTitle.' – Soal '.($currentQuestionIndex + 1))

@section('content')
<div class="bg-white p-6 rounded-xl shadow-md fade-in max-w-4xl mx-auto">
    @if(isset($pertanyaan))
        @php
            $jawabanCollection = $percobaan->jawabanUser ?? collect();
            $totalSoal  = $pertanyaanList->count();
            $terjawab   = $jawabanCollection->count();
            $progress   = $totalSoal > 0 ? round(($terjawab / $totalSoal) * 100, 1) : 0;
        @endphp

        {{-- Informasi & Timer --}}
        <div class="flex items-center justify-between mb-4 gap-3">
            <div class="text-xs text-slate-500">
                Soal ke <span class="font-semibold">{{ $currentQuestionIndex + 1 }}</span> dari
                <span class="font-semibold">{{ $totalSoal }}</span>
            </div>

            @if(!empty($tes->durasi_menit) && !$isMonev)
                <p class="text-xs text-slate-600">
                    Sisa waktu:
                    <span id="timer" class="font-mono font-semibold text-slate-900"></span>
                </p>
            @else
                <p class="text-xs text-slate-500">
                    Isi dengan jujur. Halaman diawasi otomatis.
                </p>
            @endif
        </div>

        {{-- Progress Bar --}}
        <div class="mb-5">
            <div class="w-full bg-slate-200 rounded-full h-2 overflow-hidden">
                <div class="h-2 rounded-full bg-blue-600 transition-all"
                     style="width: {{ $progress }}%;"></div>
            </div>
            <p class="text-[11px] mt-1 text-slate-500">
                Terjawab: {{ $terjawab }} / {{ $totalSoal }} ({{ $progress }}%)
            </p>
        </div>

        {{-- PERINGATAN UJIAN KETAT --}}
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

        {{-- Form Jawaban --}}
        <form id="form-tes"
              action="{{ route('dashboard.'.$routePrefix.'.submit', ['percobaan' => $percobaan->id]) }}"
              method="POST"
              class="space-y-5">
            @csrf

            {{-- Nomor Soal Quick Nav --}}
            <div class="flex flex-wrap gap-2 mb-4">
                @foreach($pertanyaanList as $p)
                    @php
                        $answered = $jawabanCollection->contains('pertanyaan_id', $p->id);
                        $statusClass = $answered
                            ? 'bg-emerald-500 hover:bg-emerald-600'
                            : 'bg-slate-300 hover:bg-slate-400';
                    @endphp
                    <button type="submit"
                            name="next_q"
                            value="{{ $loop->index }}"
                            class="px-2.5 py-1 rounded-full text-white text-[11px] {{ $statusClass }} transition">
                        {{ $p->nomor ?? $loop->iteration }}
                    </button>
                @endforeach
            </div>

            {{-- Pertanyaan --}}
            <div class="space-y-3">
                <p class="text-slate-800 text-sm md:text-base font-semibold">
                    {{ $pertanyaan->nomor ?? '?' }}. {{ $pertanyaan->teks_pertanyaan ?? '-' }}
                </p>

                {{-- Gambar pertanyaan --}}
                @if(!empty($pertanyaan->gambar))
                    <img src="{{ asset('storage/'.$pertanyaan->gambar) }}"
                         class="mb-2 rounded-lg shadow max-h-64 object-contain cursor-pointer hover:scale-[1.02] transition"
                         onclick="openImageModal('{{ asset('storage/'.$pertanyaan->gambar) }}')">
                @endif
            </div>

            {{-- Opsi Jawaban --}}
            <div class="space-y-2">
                @if($pertanyaan->opsiJawabans && $pertanyaan->opsiJawabans->count() > 0)
                    @php
                        $existing = $jawabanCollection->firstWhere('pertanyaan_id', $pertanyaan->id);
                        $existingId = $existing->opsi_jawaban_id ?? null;

                        // Untuk survei/monev: mapping emoji otomatis berdasarkan index
                        $emojiMap = ['😡','😕','🙂','😄','😍'];
                    @endphp

                    @foreach($pertanyaan->opsiJawabans as $opsiIndex => $opsi)
                        @php
                            $checked = $existingId === $opsi->id;
                            $emoji = $emojiMap[$opsiIndex] ?? '🙂';
                        @endphp

                        @if($isMonev)
                            {{-- MODE SURVEI / MONEV: tampilan emoji --}}
                            <label class="flex items-center gap-3 p-2.5 border rounded-lg cursor-pointer
                                           hover:bg-indigo-50 transition text-sm">
                                <input
                                    type="radio"
                                    name="jawaban[{{ $pertanyaan->id }}]"
                                    value="{{ $opsi->id }}"
                                    class="hidden"
                                    {{ $checked ? 'checked' : '' }}
                                    required
                                >
                                <span class="text-2xl leading-none">
                                    {{ $emoji }}
                                </span>
                                <div class="flex-1">
                                    <p class="font-medium text-slate-800">
                                        {{ $opsi->teks_opsi ?? '-' }}
                                    </p>
                                    @if(!empty($opsi->deskripsi))
                                        <p class="text-[11px] text-slate-500">
                                            {{ $opsi->deskripsi }}
                                        </p>
                                    @endif
                                </div>
                            </label>
                        @else
                            {{-- MODE PRE/POST TEST BIASA --}}
                            <label class="flex items-start gap-2 p-2.5 border rounded-lg cursor-pointer
                                           hover:bg-slate-50 transition text-sm">
                                <input
                                    type="radio"
                                    name="jawaban[{{ $pertanyaan->id }}]"
                                    value="{{ $opsi->id }}"
                                    class="mt-1"
                                    {{ $checked ? 'checked' : '' }}
                                    required
                                >

                                <div class="flex-1">
                                    @if(!empty($opsi->gambar))
                                        <img src="{{ asset('storage/'.$opsi->gambar) }}"
                                             class="mb-1 rounded-md shadow-sm max-h-16 object-contain cursor-pointer hover:scale-[1.02] transition"
                                             onclick="openImageModal('{{ asset('storage/'.$opsi->gambar) }}')">
                                    @endif
                                    <span class="align-middle text-slate-800">
                                        {{ $opsi->teks_opsi ?? '-' }}
                                    </span>
                                </div>
                            </label>
                        @endif
                    @endforeach
                @else
                    <p class="text-red-500 text-sm">
                        Belum ada opsi jawaban untuk pertanyaan ini.
                    </p>
                @endif
            </div>

            <input type="hidden" name="percobaan_id" value="{{ $percobaan->id }}">
            {{-- FLAG KECURANGAN – diubah lewat JS kalau terdeteksi --}}
            <input type="hidden" name="cheat_flag" id="cheat_flag" value="0">

            {{-- Navigasi --}}
            <div class="flex justify-between items-center pt-3 border-t border-slate-100 mt-4">
                @if($currentQuestionIndex > 0)
                    <button type="submit"
                            name="next_q"
                            value="{{ $currentQuestionIndex - 1 }}"
                            class="px-4 py-2 bg-slate-500 text-white rounded-lg hover:bg-slate-600 text-xs md:text-sm transition">
                        ‹ Sebelumnya
                    </button>
                @else
                    <span></span>
                @endif

                <button type="submit"
                        name="next_q"
                        value="{{ $currentQuestionIndex + 1 }}"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-xs md:text-sm transition">
                    {{ $currentQuestionIndex + 1 == $totalSoal ? 'Selesai' : 'Selanjutnya ›' }}
                </button>
            </div>
        </form>
    @else
        <p class="text-slate-500">Semua soal telah selesai.</p>
    @endif
</div>

{{-- Modal Zoom Gambar --}}
<div id="imageModal"
     class="fixed inset-0 bg-black/70 flex items-center justify-center hidden z-50"
     onclick="closeImageModal()">
    <div class="relative max-w-full max-h-full" onclick="event.stopPropagation()">
        <button onclick="closeImageModal()"
                class="absolute -top-4 -right-4 bg-white text-black rounded-full p-1.5 shadow hover:bg-slate-100 text-sm">
            ✕
        </button>
        <img id="modalImage" src="" class="max-w-[90vw] max-h-[80vh] rounded shadow-lg">
    </div>
</div>

{{-- Modal Peringatan Kecurangan --}}
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
    // ---------- Modal Gambar ----------
    function openImageModal(src) {
        document.getElementById('modalImage').src = src;
        document.getElementById('imageModal').classList.remove('hidden');
    }
    function closeImageModal() {
        document.getElementById('imageModal').classList.add('hidden');
        document.getElementById('modalImage').src = '';
    }

    // ---------- Timer (untuk pre/post) ----------
    @if(!empty($tes->durasi_menit) && !$isMonev)
    (function(){
        let duration = {{ (int) $tes->durasi_menit * 60 }};
        const serverStart = new Date("{{ $percobaan->waktu_mulai ?? now() }}").getTime();
        const nowServer   = new Date("{{ now() }}").getTime();
        let elapsed   = Math.floor((nowServer - serverStart) / 1000);
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
                if (form) form.submit();
                return;
            }
            const hours   = pad(Math.floor(remaining/3600));
            const minutes = pad(Math.floor((remaining%3600)/60));
            const seconds = pad(remaining%60);
            el.textContent = `${hours}:${minutes}:${seconds}`;
            remaining--;
        }

        updateTimer();
        const interval = setInterval(updateTimer, 1000);
    })();
    @endif

    // ---------- Deteksi Kecurangan ----------
    let cheatTriggered = false;

    function triggerCheat(reason) {
        if (cheatTriggered) return;
        cheatTriggered = true;

        const cheatInput = document.getElementById('cheat_flag');
        if (cheatInput) cheatInput.value = '1';

        const modal = document.getElementById('cheatModal');
        if (modal) modal.classList.remove('hidden');
        console.warn('Cheat detected:', reason);
    }

    function closeCheatModal() {
        const modal = document.getElementById('cheatModal');
        if (modal) modal.classList.add('hidden');
    }

    // Ketika window blur (pindah aplikasi / tab)
    window.addEventListener('blur', () => {
        triggerCheat('window_blur');
    });

    // Ketika tab disembunyikan
    document.addEventListener('visibilitychange', () => {
        if (document.visibilityState === 'hidden') {
            triggerCheat('tab_hidden');
        }
    });

    // Ketika window terlalu kecil (indikasi minimize / resize mencurigakan)
    let initialHeight = window.innerHeight;
    window.addEventListener('resize', () => {
        // jika tinggi berkurang drastis, anggap mencurigakan
        if (window.innerHeight < initialHeight * 0.6) {
            triggerCheat('window_resize');
        }
    });
</script>
@endpush
@endsection
