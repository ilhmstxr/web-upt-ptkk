{{-- resources/views/dashboard/pages/pre-test/pretest-result.blade.php --}}
@extends('dashboard.layouts.main')

@section('title', 'Hasil Pre-Test')
@section('page-title', 'Hasil Pre-Test')

@section('content')
<div class="bg-white p-6 rounded-xl shadow-md fade-in max-w-3xl mx-auto">
    <h2 class="font-bold text-xl mb-4 text-slate-800">Hasil Pre-Test</h2>

    @if(!empty($percobaan))

        @php
            // Nama peserta: kompatibel survei / peserta biasa
            $namaPeserta =
                $percobaan->pesertaSurvei?->nama
                ?? $percobaan->peserta?->nama
                ?? 'Tidak ada nama';

            // Skor tetap ambil dari percobaan (tidak diubah)
            $skor = $percobaan->skor ?? 0;

            // Waktu bisa string / Carbon → amankan tampilan saja
            $mulaiRaw = $percobaan->waktu_mulai ?? null;
            $selesaiRaw = $percobaan->waktu_selesai ?? null;

            $mulai = $mulaiRaw
                ? ( $mulaiRaw instanceof \Carbon\Carbon
                    ? $mulaiRaw
                    : \Illuminate\Support\Carbon::parse($mulaiRaw)
                  )
                : null;

            $selesai = $selesaiRaw
                ? ( $selesaiRaw instanceof \Carbon\Carbon
                    ? $selesaiRaw
                    : \Illuminate\Support\Carbon::parse($selesaiRaw)
                  )
                : null;
        @endphp

        {{-- Nama Peserta --}}
        <div class="mb-5">
            <h3 class="font-semibold text-lg text-slate-700">Nama Peserta</h3>
            <p class="text-slate-900 text-lg font-bold">
                {{ $namaPeserta }}
            </p>
        </div>

        {{-- Detail Skor & Waktu --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">

            {{-- Skor --}}
            <div class="p-4 bg-slate-50 rounded-lg border border-slate-100">
                <h3 class="font-semibold text-slate-700">Skor</h3>
                <p class="text-slate-900 text-2xl font-bold">
                    {{ $skor }}
                </p>
            </div>

            {{-- Waktu Mulai --}}
            <div class="p-4 bg-slate-50 rounded-lg border border-slate-100">
                <h3 class="font-semibold text-slate-700">Waktu Mulai</h3>
                <p class="text-slate-700">
                    {{ $mulai?->format('d M Y, H:i') ?? '-' }}
                </p>
            </div>

            {{-- Waktu Selesai --}}
            <div class="p-4 bg-slate-50 rounded-lg border border-slate-100">
                <h3 class="font-semibold text-slate-700">Waktu Selesai</h3>
                <p class="text-slate-700">
                    {{ $selesai?->format('d M Y, H:i') ?? 'Belum selesai' }}
                </p>
            </div>

            {{-- Pesan & Kesan --}}
            @if(!empty($percobaan->pesan_kesan))
                <div class="p-4 bg-slate-50 rounded-lg border border-slate-100 col-span-1 md:col-span-2">
                    <h3 class="font-semibold text-slate-700">Pesan & Kesan</h3>
                    <p class="text-slate-700 italic">
                        “{{ $percobaan->pesan_kesan }}”
                    </p>
                </div>
            @endif
        </div>
        <a href="{{ route('dashboard.home') }}"
           class="inline-flex px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
            Kembali ke Dashboard
        </a>

    @else
        <p class="text-gray-500">Hasil percobaan tidak ditemukan.</p>
        <a href="{{ route('dashboard.home') }}"
           class="mt-4 inline-block px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
            Kembali ke Dashboard
        </a>
    @endif
</div>
@endsection
