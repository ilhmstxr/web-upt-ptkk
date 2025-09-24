@extends('dashboard.layouts.main')

@section('title', 'Pre-Test')
@section('page-title', 'Pre-Test: Soal '.($currentQuestionIndex + 1))

@section('content')
<div class="bg-white p-6 rounded-xl shadow-md fade-in">

    @if(isset($pertanyaan))
        @php
            $jawabanCollection = $percobaan->jawabanUser ?? collect();
            $totalSoal = $pertanyaanList->count();
            $terjawab = $jawabanCollection->count();
            $progress = $totalSoal > 0 ? round(($terjawab / $totalSoal) * 100, 2) : 0;
        @endphp

        {{-- Timer --}}
        <p class="text-sm text-gray-600 mb-4">
            Sisa waktu: <span id="timer"></span>
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

        {{-- Form Jawaban --}}
        <form id="form-tes"
              action="{{ route('dashboard.pretest.submit', ['percobaan' => $percobaan->id]) }}"
              method="POST">
            @csrf

            {{-- Nomor Soal --}}
            <div class="mb-4 flex flex-wrap gap-2">
                @foreach($pertanyaanList as $p)
                    @php
                        $answered = $jawabanCollection->contains('pertanyaan_id', $p->id);
                        $status = $answered ? 'bg-green-500' : 'bg-red-500';
                    @endphp
                    <button type="submit"
                            name="next_q"
                            value="{{ $loop->index }}"
                            class="px-3 py-1 rounded text-white {{ $status }}">
                        {{ $p->nomor }}
                    </button>
                @endforeach
            </div>

            {{-- Pertanyaan --}}
            <p class="text-gray-700 mb-4">
                {{ $pertanyaan->nomor ?? '?' }}. {{ $pertanyaan->teks_pertanyaan ?? '-' }}
            </p>

            {{-- Gambar pertanyaan --}}
            @if(!empty($pertanyaan->gambar))
                <img src="{{ asset('images/pertanyaan/'.$pertanyaan->gambar) }}" 
                     class="mb-4 rounded shadow cursor-pointer hover:scale-105 transition"
                     onclick="openImageModal('{{ asset('storage/'.$pertanyaan->gambar) }}')">
            @endif

            {{-- Opsi Jawaban --}}
            <div class="space-y-2 mb-4">
                @if($pertanyaan->opsiJawabans && $pertanyaan->opsiJawabans->count() > 0)
                    @foreach($pertanyaan->opsiJawabans as $opsi)
                        @php
                            $existing = $jawabanCollection->firstWhere('pertanyaan_id', $pertanyaan->id);
                            $checked = $existing && ($existing->opsi_jawaban_id ?? 0) == $opsi->id;
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
                                <img src="{{ asset('images/opsi-jawaban/'.$opsi->gambar) }}" 
                                     class="inline-block w-12 h-12 mr-2 rounded align-middle cursor-pointer hover:scale-105 transition"
                                     onclick="openImageModal('{{ asset('storage/'.$opsi->gambar) }}')">
                            @endif
                            <span class="align-middle">{{ $opsi->teks_opsi ?? '-' }}</span>
                        </label>
                    @endforeach
                @else
                    <p class="text-red-500">Belum ada opsi jawaban untuk pertanyaan ini.</p>
                @endif
            </div>

            <input type="hidden" name="percobaan_id" value="{{ $percobaan->id }}">

            {{-- Navigasi --}}
            <div class="flex justify-between">
                @if($currentQuestionIndex > 0)
                    <button type="submit"
                            name="next_q"
                            value="{{ $currentQuestionIndex - 1 }}"
                            class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600 transition">
                        Sebelumnya
                    </button>
                @else
                    <span></span>
                @endif

                <button type="submit"
                        name="next_q"
                        value="{{ $currentQuestionIndex + 1 }}"
                        class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
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

        <a href="{{ route('dashboard.pretest.result', ['percobaan' => $percobaan->id ?? 0]) }}"
           class="mt-4 inline-block px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition">
           Lihat Hasil Detail
        </a>
    @endif
</div>

{{-- Modal Zoom Gambar --}}
<div id="imageModal" class="fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center hidden z-50" onclick="closeImageModal()">
    <div class="relative" onclick="event.stopPropagation()">
        <button onclick="closeImageModal()" 
                class="absolute -top-4 -right-4 bg-white text-black rounded-full p-2 shadow hover:bg-gray-200">
            ✕
        </button>
        <img id="modalImage" src="" class="max-w-full max-h-screen rounded shadow-lg">
    </div>
</div>

{{-- Timer JS --}}
<script>
(function(){
    let duration = {{ $tes->durasi_menit * 60 }};
    const serverStart = new Date("{{ $percobaan->waktu_mulai ?? now() }}").getTime();
    const nowServer = new Date("{{ now() }}").getTime();
    let elapsed = Math.floor((nowServer - serverStart) / 1000);
    let remaining = duration - elapsed;
    if (remaining < 0) remaining = 0;

    function pad(n){ return n.toString().padStart(2,'0'); }

    function updateTimer() {
        if (remaining <= 0) {
            document.getElementById('timer').textContent = "00:00:00";
            clearInterval(interval);
            let form = document.getElementById("form-tes");
            if (form) {
                // auto-submit terakhir → skor dihitung
                form.submit();
            }
            return;
        }
        const hours = pad(Math.floor(remaining/3600));
        const minutes = pad(Math.floor((remaining%3600)/60));
        const seconds = pad(remaining%60);
        document.getElementById('timer').textContent = `${hours}:${minutes}:${seconds}`;
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
