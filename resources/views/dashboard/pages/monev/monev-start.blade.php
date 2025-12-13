{{-- resources/views/dashboard/pages/monev/monev-start.blade.php --}}
@extends('dashboard.layouts.main')

@section('title', 'Monev / Survey')
@section('page-title', 'Monev: Soal '.($currentQuestionIndex + 1))

@section('content')
<div class="bg-white p-6 rounded-2xl shadow-md fade-in max-w-4xl mx-auto">

    @if(isset($pertanyaan))
    @php
    $jawabanCollection = $percobaan->jawabanUser ?? collect();
    $totalSoal = $pertanyaanList->count();
    $terjawab = $jawabanCollection->count();
    $progress = $totalSoal > 0 ? round(($terjawab / $totalSoal) * 100, 2) : 0;

    $existing = $jawabanCollection->firstWhere('pertanyaan_id', $pertanyaan->id);
    $existingNilai = $existing->nilai_jawaban ?? null;

    // ✅ Likert 1-4 pakai emoji lucu
    $likert = [
    1 => ['emoji' => '😡', 'label' => 'Sangat Tidak Setuju'],
    2 => ['emoji' => '😕', 'label' => 'Tidak Setuju'],
    3 => ['emoji' => '🙂', 'label' => 'Setuju'],
    4 => ['emoji' => '😍', 'label' => 'Sangat Setuju'],
    ];
    @endphp

    {{-- HEADER PROGRESS --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 mb-5">
        <div class="text-sm text-slate-600">
            Soal <b>{{ $currentQuestionIndex + 1 }}</b> dari {{ $totalSoal }}
        </div>

        <div class="w-full md:w-1/2">
            <div class="w-full bg-slate-200 rounded-full h-3 overflow-hidden">
                <div class="bg-indigo-600 h-3 rounded-full transition-all"
                    style="width: {{ $progress }}%;"></div>
            </div>
            <p class="text-xs mt-1 text-slate-500">
                Terjawab: {{ $terjawab }} / {{ $totalSoal }} ({{ $progress }}%)
            </p>
        </div>
    </div>

    {{-- FORM --}}
    <form id="form-monev"
        action="{{ route('dashboard.monev.submit', ['percobaan' => $percobaan->id]) }}"
        method="POST"
        class="space-y-5">
        @csrf

        {{-- NAV NOMOR SOAL (1-20 / 1-N) --}}
        <div class="flex flex-wrap gap-2">
            @foreach($pertanyaanList as $idx => $p)
            @php
            $answered = $jawabanCollection->contains('pertanyaan_id', $p->id);
            $isActive = $idx === $currentQuestionIndex;

            $btnClass = $isActive
            ? 'bg-indigo-600 text-white ring-2 ring-indigo-200'
            : ($answered
            ? 'bg-emerald-100 text-emerald-700 hover:bg-emerald-200'
            : 'bg-slate-100 text-slate-600 hover:bg-slate-200');
            @endphp

            <button type="button"
                onclick="goToQuestion({{ $idx }})"
                class="w-9 h-9 rounded-lg text-sm font-bold grid place-items-center transition {{ $btnClass }}">
                {{ $loop->iteration }}
            </button>
            @endforeach
        </div>

        {{-- PERTANYAAN --}}
        <div class="bg-slate-50 border border-slate-100 rounded-xl p-4 space-y-2">
            <div class="text-slate-800 font-semibold leading-relaxed text-base">
                {{ ($currentQuestionIndex + 1) }}.
                @php($plain = trim(strip_tags($pertanyaan->teks_pertanyaan ?? '')))
                {!! $plain !== '' ? $pertanyaan->teks_pertanyaan : '-' !!}

            </div>

            {{-- Gambar pertanyaan --}}
            @if(!empty($pertanyaan->gambar))
            <div class="mt-2">
                <img src="{{ asset('storage/'.$pertanyaan->gambar) }}"
                    alt="Gambar soal"
                    class="max-h-72 rounded-lg shadow cursor-zoom-in hover:opacity-95 transition"
                    onclick="openImageModal('{{ asset('storage/'.$pertanyaan->gambar) }}')">
            </div>
            @endif
        </div>

        {{-- LIKERT OPSI (1-4) --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            @foreach($likert as $nilai => $meta)
            @php $checked = ((int)$existingNilai === (int)$nilai); @endphp

            <label class="relative flex items-center gap-3 p-3 border rounded-xl cursor-pointer hover:bg-indigo-50 transition
                                  has-[:checked]:bg-indigo-50 has-[:checked]:border-indigo-500 has-[:checked]:ring-1 has-[:checked]:ring-indigo-500">
                <input type="radio"
                    name="nilai[{{ $pertanyaan->id }}]"
                    value="{{ $nilai }}"
                    class="peer sr-only"
                    {{ $checked ? 'checked' : '' }}
                    required>

                <span class="text-3xl leading-none grayscale peer-checked:grayscale-0 transition">{{ $meta['emoji'] }}</span>

                <div class="flex-1">
                    <div class="font-semibold text-slate-800 text-sm">
                        {{ $meta['label'] }}
                    </div>
                    <div class="text-xs text-slate-500">
                        Skala {{ $nilai }}
                    </div>
                </div>

                {{-- Badge "Dipilih" hanya muncul jika checked --}}
                <span class="hidden peer-checked:inline-block text-[11px] px-2 py-1 rounded-full bg-indigo-100 text-indigo-700 font-semibold transition">
                    Dipilih
                </span>
            </label>
            @endforeach
        </div>

        <input type="hidden" name="percobaan_id" value="{{ $percobaan->id }}">
        <input type="hidden" id="next_q_input" name="next_q" value="{{ $currentQuestionIndex + 1 }}">

        {{-- NAVIGASI --}}
        <div class="flex items-center justify-between pt-2 border-t border-slate-100">
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
                class="px-5 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition font-semibold">
                {{ ($currentQuestionIndex + 1) == $totalSoal ? 'Selesai ✓' : 'Selanjutnya →' }}
            </button>
        </div>
    </form>

    @else
    {{-- Semua soal selesai --}}
    <div class="text-center py-8 space-y-3">
        <div class="text-slate-700 text-lg font-semibold">Terima kasih! 🎉</div>
        <div class="text-slate-500 text-sm">Survey sudah selesai diisi.</div>

        <a href="{{ route('dashboard.monev.result', ['percobaan' => $percobaan->id ?? 0]) }}"
            class="mt-2 inline-flex px-5 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition">
            Lihat Ringkasan Hasil
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
    function goToQuestion(idx) {
        document.getElementById('next_q_input').value = idx;
        document.getElementById('form-monev').submit();
    }

    function openImageModal(src) {
        document.getElementById('modalImage').src = src;
        document.getElementById('imageModal').classList.remove('hidden');
    }

    function closeImageModal() {
        document.getElementById('imageModal').classList.add('hidden');
        document.getElementById('modalImage').src = '';
    }
</script>
@endpush
@endsection