{{-- resources/views/dashboard/pages/monev/monev-start.blade.php --}}
@extends('dashboard.layouts.main')

@section('title', 'Monev / Survei')
@section('page-title', 'Monev: Bagian '.(($currentSectionIndex ?? 0) + 1))

@section('content')
<div class="bg-white p-6 rounded-2xl shadow-md fade-in max-w-4xl mx-auto">

    @php($hasSection = isset($pertanyaanList) && $pertanyaanList && $pertanyaanList->count() > 0)

    @if($hasSection)

        @php($jawabanCollection = $jawabanCollection ?? ($percobaan->jawabanUser ?? collect()))
        @php($kategoriList = $kategoriList ?? collect())
        @php($totalBagian = method_exists($kategoriList, 'count') ? $kategoriList->count() : 0)

        @php($currentSectionIndex = (int) ($currentSectionIndex ?? 0))
        @php($currentKategori = $currentKategori ?? 'Tanpa Kategori')

        @php($totalSoalDalamBagian = $pertanyaanList->count())

        @php($terjawabDalamBagian = $pertanyaanList->filter(function($p) use ($jawabanCollection) {
            return $jawabanCollection->contains('pertanyaan_id', $p->id);
        })->count())

        @php($progress = $totalSoalDalamBagian > 0 ? round(($terjawabDalamBagian / $totalSoalDalamBagian) * 100, 2) : 0)

        @php($likert = [
            1 => ['emoji' => '😡', 'label' => 'Sangat Tidak Setuju'],
            2 => ['emoji' => '😕', 'label' => 'Tidak Setuju'],
            3 => ['emoji' => '🙂', 'label' => 'Setuju'],
            4 => ['emoji' => '😍', 'label' => 'Sangat Setuju'],
        ])

        {{-- HEADER SUB SECTION / PROGRESS --}}
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 mb-5">
            <div class="text-sm text-slate-600 space-y-1">
                <div>
                    Bagian <b>{{ $currentSectionIndex + 1 }}</b>@if($totalBagian>0) dari {{ $totalBagian }}@endif
                </div>

                <div class="text-base font-bold text-indigo-700 uppercase tracking-wide">
                    {{ $currentKategori }}
                </div>

                <div class="text-xs text-slate-500">
                    Sub-soal: {{ $terjawabDalamBagian }} / {{ $totalSoalDalamBagian }}
                </div>
            </div>

            <div class="w-full md:w-1/2">
                <div class="w-full bg-slate-200 rounded-full h-3 overflow-hidden">
                    <div class="bg-indigo-600 h-3 rounded-full transition-all" style="width: {{ $progress }}%;"></div>
                </div>
                <p class="text-xs mt-1 text-slate-500">
                    Terisi: {{ $terjawabDalamBagian }} / {{ $totalSoalDalamBagian }} ({{ $progress }}%)
                </p>
            </div>
        </div>

        {{-- FORM: submit 1 section --}}
        <form
            id="form-monev"
            action="{{ route('dashboard.monev.submit', ['percobaan' => $percobaan->id]) }}"
            method="POST"
            class="space-y-5"
        >
            @csrf

            <input type="hidden" name="section" value="{{ $currentSectionIndex }}">
            <input type="hidden" name="percobaan_id" value="{{ $percobaan->id }}">

            {{-- LIST SUB-SOAL DALAM BAGIAN --}}
            <div class="space-y-6">
                @foreach($pertanyaanList as $idx => $pertanyaan)

                    @php($existing = $jawabanCollection->firstWhere('pertanyaan_id', $pertanyaan->id))
                    @php($existingNilai = $existing?->nilai_jawaban)
                    @php($existingOpsi  = $existing?->opsi_jawaban_id)
                    @php($existingTeks  = $existing?->jawaban_teks ?? '')

                    <div class="border border-slate-100 rounded-2xl p-4">

                        {{-- PERTANYAAN --}}
                        <div class="bg-slate-50 border border-slate-100 rounded-xl p-4 space-y-2">
                            <div class="text-slate-800 font-semibold leading-relaxed text-base">
                                {{ $idx + 1 }}.
                                @php($plain = trim(strip_tags($pertanyaan->teks_pertanyaan ?? '')))
                                {!! $plain !== '' ? $pertanyaan->teks_pertanyaan : '-' !!}
                            </div>

                            @if(!empty($pertanyaan->gambar))
                                <div class="mt-2">
                                    <img
                                        src="{{ asset('storage/'.$pertanyaan->gambar) }}"
                                        alt="Gambar soal"
                                        class="max-h-72 rounded-lg shadow cursor-zoom-in hover:opacity-95 transition"
                                        onclick="openImageModal('{{ asset('storage/'.$pertanyaan->gambar) }}')"
                                    >
                                </div>
                            @endif
                        </div>

                        {{-- JAWABAN BERDASARKAN TIPE --}}
                        @php($tipe = $pertanyaan->tipe_jawaban ?? null)

                        {{-- ===== SKALA LIKERT ===== --}}
                        @if($tipe === 'skala_likert')
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mt-4">
                                @foreach($likert as $nilai => $meta)
                                    @php($checked = ((int) $existingNilai === (int) $nilai))

                                    <label class="relative flex items-center gap-3 p-3 border rounded-xl cursor-pointer hover:bg-indigo-50 transition
                                        has-[:checked]:bg-indigo-50 has-[:checked]:border-indigo-500 has-[:checked]:ring-1 has-[:checked]:ring-indigo-500">
                                        <input
                                            type="radio"
                                            name="nilai[{{ $pertanyaan->id }}]"
                                            value="{{ $nilai }}"
                                            class="peer sr-only"
                                            @checked($checked)
                                            required
                                        >
                                        <span class="text-3xl leading-none grayscale peer-checked:grayscale-0 transition">
                                            {{ $meta['emoji'] }}
                                        </span>
                                        <div class="flex-1">
                                            <div class="font-semibold text-slate-800 text-sm">
                                                {{ $meta['label'] }}
                                            </div>
                                            <div class="text-xs text-slate-500">
                                                Skala {{ $nilai }}
                                            </div>
                                        </div>
                                        <span class="hidden peer-checked:inline-block text-[11px] px-2 py-1 rounded-full bg-indigo-100 text-indigo-700 font-semibold transition">
                                            Dipilih
                                        </span>
                                    </label>
                                @endforeach
                            </div>

                            @error("nilai.$pertanyaan->id")
                                <p class="mt-3 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        @endif

                        {{-- ===== PILIHAN GANDA ===== --}}
                        @if($tipe === 'pilihan_ganda')
                            @php($opsiList = $pertanyaan->opsiJawabans ?? collect())

                            @if($opsiList->count() > 0)
                                <div class="space-y-2 mt-4">
                                    @foreach($opsiList as $opsi)
                                        <label class="relative flex items-center gap-3 p-3 border rounded-xl cursor-pointer hover:bg-indigo-50 transition
                                            has-[:checked]:bg-indigo-50 has-[:checked]:border-indigo-500 has-[:checked]:ring-1 has-[:checked]:ring-indigo-500">
                                            <input
                                                type="radio"
                                                name="jawaban[{{ $pertanyaan->id }}]"
                                                value="{{ $opsi->id }}"
                                                class="peer sr-only"
                                                @checked((int) $existingOpsi === (int) $opsi->id)
                                                required
                                            >
                                            <div class="flex-1 text-slate-800 text-sm">
                                                {!! $opsi->teks_opsi ?? '-' !!}
                                            </div>
                                            <span class="hidden peer-checked:inline-block text-[11px] px-2 py-1 rounded-full bg-indigo-100 text-indigo-700 font-semibold transition">
                                                Dipilih
                                            </span>
                                        </label>
                                    @endforeach
                                </div>
                            @else
                                <p class="mt-3 text-sm text-amber-600">
                                    Opsi jawaban belum tersedia untuk pertanyaan ini.
                                </p>
                            @endif

                            @error("jawaban.$pertanyaan->id")
                                <p class="mt-3 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        @endif

                        {{-- ===== ESSAY / TEKS BEBAS ===== --}}
                        @if($tipe === 'teks_bebas')
                            <div class="mt-4">
                                <textarea
                                    name="teks[{{ $pertanyaan->id }}]"
                                    rows="4"
                                    class="w-full border border-slate-200 rounded-xl p-3 focus:outline-none focus:ring-2 focus:ring-indigo-400"
                                    placeholder="Tuliskan jawaban Anda..."
                                    required
                                >{{ old("teks.$pertanyaan->id", $existingTeks) }}</textarea>
                            </div>

                            @error("teks.$pertanyaan->id")
                                <p class="mt-3 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        @endif

                    </div>
                @endforeach
            </div>

            {{-- NAVIGASI SECTION --}}
            <div class="flex items-center justify-between pt-2 border-t border-slate-100">
                @if(($currentSectionIndex ?? 0) > 0)
                    <a
                        href="{{ route('dashboard.monev.show', ['tes' => $tes->id, 'percobaan' => $percobaan->id, 'section' => ($currentSectionIndex - 1)]) }}"
                        class="px-4 py-2 bg-slate-500 text-white rounded-lg hover:bg-slate-600 transition"
                    >
                        ← Sebelumnya
                    </a>
                @else
                    <span></span>
                @endif

                <button
                    type="submit"
                    class="px-5 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition font-semibold"
                >
                    {{ (($totalBagian > 0) && (($currentSectionIndex + 1) == $totalBagian)) ? 'Selesai ✓' : 'Selanjutnya →' }}
                </button>
            </div>
        </form>

    @else

        <div class="text-center py-8 space-y-3">
            <div class="text-slate-700 text-lg font-semibold">Terima kasih! 🎉</div>
            <div class="text-slate-500 text-sm">Survei sudah selesai diisi.</div>

            <a
                href="{{ route('dashboard.monev.result', ['percobaan' => $percobaan->id ?? 0]) }}"
                class="mt-2 inline-flex px-5 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition"
            >
                Lihat Ringkasan Hasil
            </a>
        </div>

    @endif
</div>

{{-- MODAL ZOOM GAMBAR --}}
<div
    id="imageModal"
    class="fixed inset-0 bg-black/75 flex items-center justify-center hidden z-50"
    onclick="closeImageModal()"
>
    <div class="relative max-w-5xl w-full px-4" onclick="event.stopPropagation()">
        <button
            type="button"
            onclick="closeImageModal()"
            class="absolute -top-4 -right-2 bg-white text-black rounded-full p-2 shadow hover:bg-gray-200"
        >
            ✕
        </button>
        <img id="modalImage" src="" class="max-w-full max-h-[90vh] rounded shadow-lg mx-auto">
    </div>
</div>

@push('scripts')
<script>
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
