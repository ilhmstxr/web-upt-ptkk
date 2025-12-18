@extends('dashboard.layouts.main')

@section('title', 'Monev / Survei')
@section('page-title', 'Monev: Bagian '.(($currentSectionIndex ?? 0) + 1))

@section('content')
<div class="bg-white p-6 rounded-2xl shadow-md fade-in max-w-4xl mx-auto">

@php
    $hasSection = isset($pertanyaanList) && $pertanyaanList && $pertanyaanList->count() > 0;
@endphp

@if($hasSection)

    @php
        $jawabanCollection = $jawabanCollection ?? ($percobaan->jawabanUser ?? collect());
        $kategoriList = $kategoriList ?? collect();
        $totalBagian = method_exists($kategoriList, 'count') ? $kategoriList->count() : 0;

        $currentSectionIndex = (int) ($currentSectionIndex ?? 0);
        $currentKategori = $currentKategori ?? 'Tanpa Kategori';

        $totalSoalDalamBagian = $pertanyaanList->count();
        $terjawabDalamBagian = $pertanyaanList->filter(function ($p) use ($jawabanCollection) {
            return $jawabanCollection->contains('pertanyaan_id', $p->id);
        })->count();

        $progress = $totalSoalDalamBagian > 0
            ? round(($terjawabDalamBagian / $totalSoalDalamBagian) * 100, 2)
            : 0;
    @endphp

    {{-- HEADER + PROGRESS --}}
<div class="mb-6 space-y-4">

    <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-3">
        <div class="space-y-1">
            <div class="text-sm text-slate-600">
                Bagian <b>{{ $currentSectionIndex + 1 }}</b>
                @if($totalBagian) dari {{ $totalBagian }} @endif
            </div>

            <div class="text-base font-bold text-indigo-700 uppercase">
                {{ $currentKategori }}
            </div>

            <div class="text-xs text-slate-500">
                Terjawab {{ $terjawabDalamBagian }} / {{ $totalSoalDalamBagian }}
            </div>
        </div>

        {{-- Progress info (kanan) --}}
        <div class="text-xs text-slate-500 md:text-right">
            <span id="progress-text">{{ $progress }}</span>% selesai
        </div>
    </div>

    {{-- Progress bar --}}
    <div class="w-full">
        <div class="w-full bg-slate-200 rounded-full h-3 overflow-hidden">
            <div
                id="progress-bar"
                class="bg-indigo-600 h-3 rounded-full transition-all duration-300"
                style="width: {{ $progress }}%">
            </div>
        </div>
    </div>

    {{-- meta untuk JS (disembunyikan) --}}
    <div id="progress-meta" data-total="{{ $totalSoalDalamBagian }}" class="hidden"></div>
</div>

    {{-- FORM --}}
    <form
        id="form-monev"
        action="{{ route('dashboard.monev.submit', ['percobaan' => $percobaan->id]) }}"
        method="POST"
        class="space-y-6"
    >
        @csrf
        <input type="hidden" name="section" value="{{ $currentSectionIndex }}">
        <input type="hidden" name="percobaan_id" value="{{ $percobaan->id }}">

        {{-- LIST SOAL --}}
        @foreach($pertanyaanList as $idx => $pertanyaan)

            @php
                $existing = $jawabanCollection->firstWhere('pertanyaan_id', $pertanyaan->id);
                $existingNilai = $existing?->nilai_jawaban;
                $existingOpsi  = $existing?->opsi_jawaban_id;
                $existingTeks  = $existing?->jawaban_teks ?? '';
                $tipe = $pertanyaan->tipe_jawaban;
            @endphp

            <div class="border border-slate-100 rounded-2xl p-4 space-y-4">

                {{-- SOAL --}}
                <div class="bg-slate-50 p-4 rounded-xl border">
                    <div class="font-semibold text-slate-800">
                        {{ $idx + 1 }}. {!! $pertanyaan->teks_pertanyaan !!}
                    </div>

                    @if($pertanyaan->gambar)
                        <img
                            src="{{ asset('storage/'.$pertanyaan->gambar) }}"
                            class="mt-3 max-h-72 rounded shadow cursor-zoom-in"
                            onclick="openImageModal('{{ asset('storage/'.$pertanyaan->gambar) }}')"
                        >
                    @endif
                </div>

                {{-- SKALA LIKERT (EMOJI FIX, LABEL DINAMIS) --}}
                @if($tipe === 'skala_likert')
                    @php
                        $opsiList = $pertanyaan->opsiJawabans ?? collect();

                        $likertEmojis = [
                            1 => '😡',
                            2 => '😕',
                            3 => '🙂',
                            4 => '😍',
                        ];

                        $likert = [];
                        foreach ($likertEmojis as $nilai => $emoji) {
                            $teksLabel = $opsiList->get($nilai - 1)?->teks_opsi ?? match($nilai) {
                                1 => 'Sangat Tidak Setuju',
                                2 => 'Tidak Setuju',
                                3 => 'Setuju',
                                4 => 'Sangat Setuju',
                            };
                            $likert[$nilai] = [
                                'emoji' => $emoji,
                                'label' => $teksLabel,
                            ];
                        }
                    @endphp

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        @foreach($likert as $nilai => $data)
                            @php $checked = ((int) $existingNilai === $nilai); @endphp
                            <label class="flex items-center gap-3 p-3 border rounded-xl cursor-pointer
                                          hover:bg-indigo-50
                                          has-[:checked]:bg-indigo-50
                                          has-[:checked]:border-indigo-500
                                          has-[:checked]:ring-1
                                          has-[:checked]:ring-indigo-500">

                                <input
                                    type="radio"
                                    name="nilai[{{ $pertanyaan->id }}]"
                                    value="{{ $nilai }}"
                                    class="sr-only peer"
                                    @checked($checked)>

                                <span class="text-3xl grayscale peer-checked:grayscale-0">
                                    {{ $data['emoji'] }}
                                </span>

                                <div class="flex-1">
                                    <div class="font-semibold text-sm">
                                        {{ $data['label'] }}
                                    </div>
                                    <div class="text-xs text-slate-500">
                                        Skala {{ $nilai }}
                                    </div>
                                </div>
                            </label>
                        @endforeach
                    </div>

                    @error("nilai.$pertanyaan->id")
                        <p class="text-sm text-red-600">{{ $message }}</p>
                    @enderror
                @endif

                {{-- PILIHAN GANDA --}}
                @if($tipe === 'pilihan_ganda')
                    @foreach($pertanyaan->opsiJawabans as $opsi)
                        <label class="flex items-center gap-3 p-3 border rounded-xl cursor-pointer
                                      hover:bg-indigo-50
                                      has-[:checked]:bg-indigo-50
                                      has-[:checked]:border-indigo-500">

                            <input
                                type="radio"
                                name="jawaban[{{ $pertanyaan->id }}]"
                                value="{{ $opsi->id }}"
                                class="sr-only peer"
                                @checked((int) $existingOpsi === (int) $opsi->id)
                                required
                            >

                            <div class="flex-1 text-sm">
                                {!! $opsi->teks_opsi !!}
                            </div>
                        </label>
                    @endforeach

                    @error("jawaban.$pertanyaan->id")
                        <p class="text-sm text-red-600">{{ $message }}</p>
                    @enderror
                @endif

                {{-- ESSAY / TEKS BEBAS --}}
                @if($tipe === 'teks_bebas')
                    <textarea
                        name="teks[{{ $pertanyaan->id }}]"
                        rows="4"
                        class="w-full border rounded-xl p-3"
                        required
                    >{{ old("teks.$pertanyaan->id", $existingTeks) }}</textarea>

                    @error("teks.$pertanyaan->id")
                        <p class="text-sm text-red-600">{{ $message }}</p>
                    @enderror
                @endif

            </div>
        @endforeach

        {{-- NAVIGASI --}}
        <div class="flex justify-between pt-4 border-t">
            @if($currentSectionIndex > 0)
                <a
                    href="{{ route('dashboard.monev.show', [
                        'tes' => $tes->id,
                        'percobaan' => $percobaan->id,
                        'section' => $currentSectionIndex - 1
                    ]) }}"
                    class="px-4 py-2 bg-slate-500 text-white rounded-lg"
                >
                    ← Sebelumnya
                </a>
            @else
                <span></span>
            @endif

            <button
                type="submit"
                class="px-5 py-2 bg-indigo-600 text-white rounded-lg font-semibold"
            >
                {{ ($totalBagian && $currentSectionIndex + 1 === $totalBagian)
                    ? 'Selesai ✓'
                    : 'Selanjutnya →' }}
            </button>
        </div>
    </form>

@else
    <div class="text-center py-10">
        <div class="text-lg font-semibold">Terima kasih 🎉</div>
        <a
            href="{{ route('dashboard.monev.result', ['percobaan' => $percobaan->id]) }}"
            class="mt-3 inline-block px-5 py-2 bg-emerald-600 text-white rounded-lg"
        >
            Lihat Hasil
        </a>
    </div>
@endif
</div>

{{-- MODAL GAMBAR --}}
<div id="imageModal"
     class="fixed inset-0 bg-black/80 hidden items-center justify-center z-50"
     onclick="closeImageModal()">
    <img id="modalImage" class="max-h-[90vh] rounded shadow">
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {

    /* =============================
       PROGRESS BAR PER KLIK
    ============================== */
    const meta = document.getElementById('progress-meta');
    if (meta) {
        const total = Number(meta.dataset.total || 0);
        const bar   = document.getElementById('progress-bar');
        const text  = document.getElementById('progress-text');

        const updateProgress = () => {
            const answered = new Set();

            // Radio (Likert / Pilihan Ganda)
            document.querySelectorAll('input[type="radio"]:checked').forEach(el => {
                answered.add(el.name);
            });

            // Textarea (Essay)
            document.querySelectorAll('textarea').forEach(el => {
                if (el.value.trim() !== '') {
                    answered.add(el.name);
                }
            });

            const percent = total
                ? Math.round((answered.size / total) * 100)
                : 0;

            if (bar)  bar.style.width = percent + '%';
            if (text) text.textContent = percent;
        };

        // Event listener
        document.querySelectorAll('input[type="radio"]').forEach(input => {
            input.addEventListener('change', updateProgress);
        });

        document.querySelectorAll('textarea').forEach(textarea => {
            textarea.addEventListener('input', updateProgress);
        });
    }

    /* =============================
       MODAL GAMBAR
    ============================== */
    window.openImageModal = function (src) {
        const modal = document.getElementById('imageModal');
        const img   = document.getElementById('modalImage');

        if (!modal || !img) return;

        img.src = src;
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    };

    window.closeImageModal = function () {
        const modal = document.getElementById('imageModal');
        if (!modal) return;

        modal.classList.add('hidden');
        modal.classList.remove('flex');
    };

});
</script>
@endpush
@endsection
