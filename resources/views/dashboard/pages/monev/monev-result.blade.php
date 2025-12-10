{{-- resources/views/dashboard/pages/monev/monev-result.blade.php --}}
@extends('dashboard.layouts.main')

@section('title', 'Hasil Monev / Survey')
@section('page-title', 'Hasil Monev / Survey')

@section('content')
<div class="bg-white p-6 rounded-2xl shadow-md fade-in max-w-4xl mx-auto">

    <div class="flex items-center justify-between mb-5">
        <h2 class="font-bold text-xl text-slate-800">Hasil Monev / Survey</h2>

        <a href="{{ route('dashboard.monev.index') }}"
           class="text-sm text-blue-600 font-semibold hover:underline">
            ← Kembali ke Daftar Monev
        </a>
    </div>

    @if(!empty($percobaan))
        @php
            $namaPeserta =
                $percobaan->pesertaSurvei?->nama
                ?? $percobaan->peserta?->nama
                ?? 'Peserta';

            $jawabanCollection = $percobaan->jawabanUser ?? collect();

            $likertMap = [
                1 => ['emoji' => '😡', 'label' => 'Sangat Tidak Setuju'],
                2 => ['emoji' => '😕', 'label' => 'Tidak Setuju'],
                3 => ['emoji' => '🙂', 'label' => 'Setuju'],
                4 => ['emoji' => '😍', 'label' => 'Sangat Setuju'],
            ];

            // Rata-rata nilai (opsional)
            $avg = $jawabanCollection->whereNotNull('nilai_jawaban')->avg('nilai_jawaban');
            $avg = $avg ? round($avg, 2) : null;
        @endphp

        {{-- Nama --}}
        <div class="mb-6">
            <div class="text-sm text-slate-500">Nama Peserta</div>
            <div class="text-lg font-bold text-slate-800 mt-1">{{ $namaPeserta }}</div>
        </div>

        {{-- Ringkasan --}}
        <div class="p-4 bg-indigo-50 border border-indigo-100 rounded-xl mb-6">
            <div class="text-sm text-indigo-800 font-semibold">
                🎯 Terima kasih sudah mengisi survey!
            </div>
            <div class="text-xs text-indigo-700 mt-1">
                @if($avg !== null)
                    Rata-rata respon Anda: <b>{{ $avg }}</b> dari skala 4.
                @else
                    Respon Anda sudah tersimpan.
                @endif
            </div>
        </div>

        {{-- Detail Jawaban --}}
        <div class="space-y-3">
            @foreach($pertanyaanList ?? [] as $idx => $p)
                @php
                    $jawaban = $jawabanCollection->firstWhere('pertanyaan_id', $p->id);
                    $nilai = $jawaban->nilai_jawaban ?? null;
                    $meta = $nilai ? ($likertMap[$nilai] ?? null) : null;
                @endphp

                <div class="p-4 bg-white border border-slate-100 rounded-xl">
                    <div class="text-sm font-semibold text-slate-800">
                        {{ $loop->iteration }}. {{ $p->teks_pertanyaan ?? '-' }}
                    </div>

                    @if($meta)
                        <div class="mt-2 flex items-center gap-3">
                            <span class="text-2xl">{{ $meta['emoji'] }}</span>
                            <div class="text-sm text-slate-700">
                                {{ $meta['label'] }} <span class="text-xs text-slate-500">(Skala {{ $nilai }})</span>
                            </div>
                        </div>
                    @else
                        <div class="mt-2 text-xs text-slate-500 italic">
                            Belum dijawab.
                        </div>
                    @endif
                </div>
            @endforeach
        </div>

        {{-- Pesan Kesan --}}
        @if(!empty($percobaan->pesan_kesan))
            <div class="p-4 bg-slate-50 rounded-xl border border-slate-100 mt-6">
                <div class="text-sm font-semibold text-slate-800 mb-1">Pesan & Kesan</div>
                <div class="text-sm text-slate-600 italic">
                    “{{ $percobaan->pesan_kesan }}”
                </div>
            </div>
        @endif

        {{-- CTA --}}
        <div class="flex flex-wrap gap-3 mt-6">
            <a href="{{ route('dashboard.home') }}"
               class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                Kembali ke Dashboard
            </a>
        </div>

    @else
        <div class="py-10 text-center text-slate-500">
            Hasil percobaan tidak ditemukan.
        </div>
        <div class="text-center">
            <a href="{{ route('dashboard.home') }}"
               class="inline-block px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                Kembali ke Dashboard
            </a>
        </div>
    @endif

</div>
@endsection
