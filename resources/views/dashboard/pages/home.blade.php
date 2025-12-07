@extends('dashboard.layouts.main')
@section('title', 'Home')
@section('page-title', 'Dashboard Home')

@section('content')

@php
    // ================================
    // SETUP DEFAULT STATE (GABUNGAN)
    // ================================
    $pesertaAktif       = $pesertaAktif       ?? null;

    // Opsional pakai overlay token login
    $useTokenOverlay    = $useTokenOverlay    ?? false;

    // Tentukan overlay mana yang aktif
    $showTokenOverlay   = $useTokenOverlay && is_null($pesertaAktif);
    $showPesertaOverlay = !$useTokenOverlay && empty($pesertaAktif);

    // Status progress
    $preTestDone        = $preTestDone        ?? false;
    $postTestDone       = $postTestDone       ?? false;
    $monevDone          = $monevDone          ?? false;

    // Materi
    $materiDoneCount    = $materiDoneCount    ?? 0;
    $totalMateri        = $totalMateri        ?? 0; // jangan hardcode kalau bisa dari controller
    $materiProgress     = ($totalMateri > 0) ? floor(($materiDoneCount / $totalMateri) * 100) : 0;

    // Scores
    $preTestScore       = $preTestScore       ?? null;
    $postTestScore      = $postTestScore      ?? null;
    $monevScore         = $monevScore         ?? null;

    // Attempts
    $preTestAttempts    = $preTestAttempts    ?? 0;
    $postTestAttempts   = $postTestAttempts   ?? 0;
    $monevAttempts      = $monevAttempts      ?? 0;
@endphp

{{-- Load Fonts --}}
<link href="https://fonts.googleapis.com/css2?family=Volkhov:wght@700&family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">

<style>
    @keyframes glassFadeUp {
        0% { opacity: 0; transform: translateY(20px) scale(0.95); }
        100% { opacity: 1; transform: translateY(0) scale(1); }
    }
    .animate-glass-up {
        animation: glassFadeUp 0.8s cubic-bezier(0.2, 0.8, 0.2, 1) forwards;
    }
</style>

{{-- ======================================================= --}}
{{-- OVERLAY TOKEN LOGIN (PREMIUM) --}}
{{-- ======================================================= --}}
@if($showTokenOverlay)
    <div class="fixed inset-0 z-[9999] flex flex-col items-center justify-center min-h-screen bg-[#F0F4F8]/70 backdrop-blur-md overflow-hidden">

        {{-- dekorasi blur --}}
        <div class="absolute top-[-10%] left-[-5%] w-[500px] h-[500px] bg-purple-300/30 rounded-full blur-[120px] pointer-events-none"></div>
        <div class="absolute bottom-[-10%] right-[-5%] w-[500px] h-[500px] bg-blue-300/30 rounded-full blur-[120px] pointer-events-none"></div>

        <div class="animate-glass-up relative w-full max-w-[557px] mx-4">
            <div class="relative flex flex-col items-center justify-center p-[50px] gap-[40px] bg-white/60 backdrop-blur-2xl border border-white/50 rounded-[30px] shadow-[0_20px_60px_-15px_rgba(0,0,0,0.1)]">

                <div class="text-center w-full space-y-3">
                    <h1 class="font-[Volkhov] font-bold text-[32px] text-[#1524AF] leading-tight tracking-tight drop-shadow-sm">
                        Login Assessment Peserta
                    </h1>
                    <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-white/50 border border-white/60 shadow-sm">
                        <span class="w-2 h-2 rounded-full bg-[#E83F5B]"></span>
                        <p class="font-[Montserrat] font-medium text-[#861D23] text-sm tracking-wide">
                            Pre Test, Post Test, Monev
                        </p>
                    </div>
                </div>

                <form action="{{ route('assessment.login.submit') }}" method="POST" class="w-full flex flex-col gap-[24px]">
                    @csrf

                    @if (session('error'))
                        <div class="bg-red-50/90 border border-red-100 text-red-600 px-4 py-3 rounded-2xl text-sm text-center font-[Montserrat] font-medium shadow-sm animate-pulse">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="space-y-2">
                        <label for="token" class="block font-[Montserrat] font-semibold text-base text-[#1e293b] ml-1">
                            ID Peserta
                        </label>
                        <input type="text"
                               id="token"
                               name="token"
                               class="w-full bg-white/80 border border-gray-200/80 rounded-2xl px-5 py-4 text-gray-700 placeholder-gray-400 font-[Montserrat] text-base shadow-sm focus:outline-none focus:ring-[4px] focus:ring-[#1524AF]/10 focus:border-[#1524AF] transition-all"
                               placeholder="Masukkan ID Peserta"
                               required autofocus autocomplete="off">
                    </div>

                    <div class="space-y-2">
                        <label for="password" class="block font-[Montserrat] font-semibold text-base text-[#1e293b] ml-1">
                            Password
                        </label>
                        <div class="relative group">
                            <input type="password"
                                   id="password"
                                   name="password"
                                   class="w-full bg-white/80 border border-gray-200/80 rounded-2xl px-5 py-4 text-gray-700 placeholder-gray-400 font-[Montserrat] text-base shadow-sm focus:outline-none focus:ring-[4px] focus:ring-[#1524AF]/10 focus:border-[#1524AF] transition-all tracking-widest"
                                   placeholder="ddmmyyyy"
                                   required>
                            <div class="absolute inset-y-0 right-0 pr-5 flex items-center pointer-events-none">
                                <span class="text-xs font-bold text-gray-300 font-mono bg-gray-50 px-2 py-1 rounded">123</span>
                            </div>
                        </div>
                    </div>

                    <div class="w-full h-px bg-gradient-to-r from-transparent via-gray-300 to-transparent my-2"></div>

                    <div class="flex items-center gap-4">
                        <a href="{{ url('/') }}"
                           class="flex-1 flex justify-center items-center py-4 px-6 rounded-2xl border-2 border-[#1524AF] text-[#1524AF] font-[Montserrat] font-bold text-base hover:bg-blue-50/50 transition-all duration-300">
                            Kembali
                        </a>
                        <button type="submit"
                                class="flex-[2] flex justify-center items-center gap-2 bg-[#1524AF] hover:bg-[#0f1a8e] text-white py-4 px-6 rounded-2xl font-[Montserrat] font-bold text-base shadow-[0_10px_30px_-10px_rgba(21,36,175,0.5)] transition-all duration-300 hover:shadow-[0_15px_35px_-10px_rgba(21,36,175,0.6)] transform hover:-translate-y-0.5 active:scale-95">
                            Login
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                            </svg>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="absolute bottom-8 text-center animate-glass-up" style="animation-delay: 0.3s;">
            <p class="text-slate-500 text-xs font-[Montserrat] font-medium tracking-wide bg-white/40 px-4 py-1.5 rounded-full backdrop-blur-sm border border-white/20">
                © {{ date('Y') }} UPT. PTKK Dinas Pendidikan Prov. Jawa Timur
            </p>
        </div>
    </div>
@endif


{{-- ======================================================= --}}
{{-- KONTEN DASHBOARD (blur jika overlay aktif) --}}
{{-- ======================================================= --}}
<div class="relative transition-all duration-700 ease-in-out
    {{ ($showTokenOverlay || $showPesertaOverlay)
        ? 'filter blur-[10px] opacity-40 scale-[0.98] pointer-events-none select-none min-h-screen overflow-hidden'
        : '' }}">

    {{-- Flash messages --}}
    @if (!$showTokenOverlay)
        @if (session('success'))
            <div class="mb-6 px-5 py-4 rounded-2xl bg-green-50 text-green-700 border border-green-100 flex items-center gap-3 shadow-sm animate-glass-up">
                <div class="bg-green-100 rounded-full p-1">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <span class="font-medium">{{ session('success') }}</span>
            </div>
        @endif

        @if (session('error'))
            <div class="mb-6 px-5 py-4 rounded-2xl bg-red-50 text-red-700 border border-red-100 flex items-center gap-3 shadow-sm animate-glass-up">
                <div class="bg-red-100 rounded-full p-1">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </div>
                <span class="font-medium">{{ session('error') }}</span>
            </div>
        @endif
    @endif

    {{-- Header Dashboard --}}
    <div class="mb-8">
        @if ($pesertaAktif)
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-gradient-to-r from-white to-blue-50/50 p-6 rounded-3xl border border-slate-100 shadow-sm">
                <div>
                    <h2 class="text-2xl md:text-3xl font-bold text-slate-800 font-[Volkhov]">
                        Hai, <span class="text-blue-600">{{ explode(' ', $pesertaAktif->nama)[0] ?? 'Peserta' }}</span> 👋
                    </h2>
                    <p class="text-slate-500 mt-2 font-[Montserrat]">
                        Selamat datang di dashboard pelatihan kompetensi.
                    </p>

                    @if(session('instansi_nama'))
                        <div class="mt-3 inline-flex items-center gap-2 px-3 py-1 bg-white border border-slate-200 rounded-full text-xs font-medium text-slate-600 shadow-sm">
                            <svg class="w-3.5 h-3.5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5"/>
                            </svg>
                            {{ session('instansi_nama') }}{{ session('instansi_kota') ? ' ('.session('instansi_kota').')' : '' }}
                        </div>
                    @endif
                </div>
            </div>
        @else
            {{-- dummy header kalau blur --}}
            <div class="p-6 rounded-3xl border border-dashed border-slate-200 bg-slate-50">
                <div class="h-8 w-1/3 bg-slate-200 rounded-lg mb-4"></div>
                <div class="h-4 w-1/2 bg-slate-200 rounded-lg"></div>
            </div>
        @endif
    </div>

    {{-- GRID MENU UTAMA (ISI ikut contoh 2) --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

        {{-- Materi --}}
        <div class="bg-white p-6 rounded-[24px] shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] border border-slate-100 flex flex-col justify-between hover:border-blue-200 hover:shadow-lg transition-all duration-300 group">
            <div>
                <div class="flex justify-between items-start mb-4">
                    <div class="p-3 bg-blue-50 text-blue-600 rounded-2xl group-hover:bg-blue-600 group-hover:text-white transition-colors duration-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13"/>
                        </svg>
                    </div>
                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-slate-100 text-slate-500 uppercase tracking-wide">Modul</span>
                </div>
                <h3 class="text-lg font-bold text-slate-800 mb-2">Materi Pelatihan</h3>
                <p class="text-sm text-slate-500 leading-relaxed mb-6">Akses modul pembelajaran dan materi inti.</p>
            </div>

            @if($materiDoneCount >= $totalMateri && $totalMateri > 0)
                <div class="mt-auto flex items-center gap-2 text-green-600 bg-green-50 px-4 py-3 rounded-xl border border-green-100">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="text-sm font-semibold">Selesai ({{ $materiDoneCount }}/{{ $totalMateri }})</span>
                </div>
            @else
                <a href="{{ route('dashboard.materi.index') }}"
                   class="mt-auto w-full flex items-center justify-center gap-2 bg-slate-900 text-white py-3 px-4 rounded-xl font-semibold text-sm hover:bg-slate-800 transition-colors group-hover:shadow-lg">
                    Lanjutkan Belajar
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                    </svg>
                </a>
            @endif
        </div>

        {{-- Pre-Test --}}
        <div class="bg-white p-6 rounded-[24px] shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] border border-slate-100 flex flex-col justify-between hover:border-yellow-200 hover:shadow-lg transition-all duration-300 group">
            <div>
                <div class="flex justify-between items-start mb-4">
                    <div class="p-3 bg-yellow-50 text-yellow-600 rounded-2xl group-hover:bg-yellow-500 group-hover:text-white transition-colors duration-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3"/>
                        </svg>
                    </div>
                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-red-100 text-red-600 uppercase tracking-wide">Wajib</span>
                </div>
                <h3 class="text-lg font-bold text-slate-800 mb-2">Pre-Test</h3>
                <p class="text-sm text-slate-500 leading-relaxed mb-6">Ukur kompetensi awal sebelum memulai.</p>
            </div>

            @if($preTestDone)
                <div class="mt-auto bg-yellow-50 rounded-xl p-4 border border-yellow-100 flex justify-between items-center">
                    <div>
                        <div class="text-[10px] uppercase text-slate-400 font-bold tracking-wider">Nilai</div>
                        <div class="text-2xl font-bold text-yellow-600">{{ $preTestScore ?? '-' }}</div>
                    </div>
                    <div class="h-8 w-8 rounded-full bg-white text-yellow-500 flex items-center justify-center shadow-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                </div>
            @else
                <a href="{{ route('dashboard.pretest.index') }}"
                   class="mt-auto w-full flex items-center justify-center gap-2 bg-yellow-500 text-white py-3 px-4 rounded-xl font-semibold text-sm hover:bg-yellow-600 transition-colors">
                    Kerjakan Sekarang
                </a>
            @endif
        </div>

        {{-- Post-Test --}}
        <div class="bg-white p-6 rounded-[24px] shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] border border-slate-100 flex flex-col justify-between hover:border-green-200 hover:shadow-lg transition-all duration-300 group">
            <div>
                <div class="flex justify-between items-start mb-4">
                    <div class="p-3 bg-green-50 text-green-600 rounded-2xl group-hover:bg-green-600 group-hover:text-white transition-colors duration-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10"/>
                        </svg>
                    </div>
                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-red-100 text-red-600 uppercase tracking-wide">Wajib</span>
                </div>
                <h3 class="text-lg font-bold text-slate-800 mb-2">Post-Test</h3>
                <p class="text-sm text-slate-500 leading-relaxed mb-6">Evaluasi akhir kompetensi.</p>
            </div>

            @if($postTestDone)
                <div class="mt-auto bg-green-50 rounded-xl p-4 border border-green-100 flex justify-between items-center">
                    <div>
                        <div class="text-[10px] uppercase text-slate-400 font-bold tracking-wider">Nilai</div>
                        <div class="text-2xl font-bold text-green-600">{{ $postTestScore ?? '-' }}</div>
                    </div>
                    <div class="h-8 w-8 rounded-full bg-white text-green-500 flex items-center justify-center shadow-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                </div>
            @else
                <a href="{{ route('dashboard.posttest.index') }}"
                   class="mt-auto w-full flex items-center justify-center gap-2 bg-green-600 text-white py-3 px-4 rounded-xl font-semibold text-sm hover:bg-green-700 transition-colors">
                    Mulai Ujian
                </a>
            @endif
        </div>

        {{-- MONEV --}}
        <div class="bg-white p-6 rounded-[24px] shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] border border-slate-100 flex flex-col justify-between hover:border-indigo-200 hover:shadow-lg transition-all duration-300 group">
            <div>
                <div class="flex justify-between items-start mb-4">
                    <div class="p-3 bg-indigo-50 text-indigo-600 rounded-2xl group-hover:bg-indigo-600 group-hover:text-white transition-colors duration-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11"/>
                        </svg>
                    </div>
                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-red-100 text-red-600 uppercase tracking-wide">Wajib</span>
                </div>
                <h3 class="text-lg font-bold text-slate-800 mb-2">MONEV</h3>
                <p class="text-sm text-slate-500 leading-relaxed mb-6">Survey evaluasi penyelenggaraan.</p>
            </div>

            @if($monevDone)
                <div class="mt-auto bg-indigo-50 rounded-xl p-4 border border-indigo-100 flex items-center gap-3">
                    <div class="h-8 w-8 rounded-full bg-white text-indigo-600 flex items-center justify-center shadow-sm shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <span class="text-sm font-semibold text-indigo-700">Survey Terisi</span>
                </div>
            @else
                <a href="{{ route('dashboard.survey') }}"
                   class="mt-auto w-full flex items-center justify-center gap-2 bg-indigo-600 text-white py-3 px-4 rounded-xl font-semibold text-sm hover:bg-indigo-700 transition-colors">
                    Isi Survey
                </a>
            @endif
        </div>
    </div>

    {{-- PROGRESS STATS BAWAH (ikut contoh 2) --}}
    <div class="mt-10 grid grid-cols-2 md:grid-cols-4 gap-6 px-2 md:px-0">
        @foreach([
            ['label' => 'Materi',   'val' => $materiDoneCount,         'max' => $totalMateri, 'color' => 'bg-blue-500'],
            ['label' => 'Pre-Test', 'val' => ($preTestDone?1:0),       'max' => 1,            'color' => 'bg-yellow-500'],
            ['label' => 'Post-Test','val' => ($postTestDone?1:0),      'max' => 1,            'color' => 'bg-green-500'],
            ['label' => 'Monev',    'val' => ($monevDone?1:0),         'max' => 1,            'color' => 'bg-indigo-500'],
        ] as $stat)
            @php
                $pct = ($stat['max'] > 0) ? ($stat['val'] / $stat['max']) * 100 : 0;
            @endphp

            <div class="bg-white p-4 rounded-2xl border border-slate-100 flex flex-col items-center justify-center shadow-sm hover:shadow-md transition">
                <div class="text-[10px] uppercase font-bold text-slate-400 mb-1">{{ $stat['label'] }}</div>
                <div class="text-xl font-bold text-slate-700 mb-2">
                    {{ $stat['val'] }}
                    <span class="text-slate-300 text-sm">/ {{ $stat['max'] }}</span>
                </div>
                <div class="w-full h-1.5 bg-slate-100 rounded-full overflow-hidden">
                    <div class="{{ $stat['color'] }} h-full rounded-full transition-all duration-1000" style="width: {{ $pct }}%"></div>
                </div>
            </div>
        @endforeach
    </div>

</div>
@endsection


{{-- ======================================================= --}}
{{-- OVERLAY PILIH PESERTA (tetap dipakai kalau bukan token overlay) --}}
{{-- ======================================================= --}}
@if ($showPesertaOverlay)
<div id="pesertaOverlayRoot"
     class="fixed inset-0 z-[99999] flex items-center justify-center bg-gray-900 bg-opacity-60"
     style="backdrop-filter: blur(4px); -webkit-backdrop-filter: blur(4px);">
    <div class="bg-white rounded-xl shadow-xl w-full max-w-md p-6 mx-4">
        <h2 class="text-xl font-bold mb-4 text-center">Pilih Peserta untuk Memulai</h2>

        @if ($errors->any())
            <div class="mb-3 p-2 bg-red-50 text-red-700 rounded">
                <ul class="list-disc pl-5 text-sm">
                    @foreach ($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form id="setPesertaForm" method="POST" action="{{ route('dashboard.setPeserta') }}" class="space-y-3">
            @csrf

            <div class="mb-3">
                <label for="nama_input" class="block text-sm font-medium mb-1">Nama Peserta</label>
                <input type="text" id="nama_input" name="nama"
                       class="w-full p-2 border rounded focus:outline-none"
                       placeholder="Tulis nama peserta…" required autocomplete="off"
                       enterkeyhint="done" inputmode="text"
                       value="{{ old('nama') }}">
                <p id="nama_helper" class="text-xs text-gray-500 mt-1 hidden"></p>
            </div>

            <div class="mb-3">
                <label for="sekolah_display" class="block text-sm font-medium mb-1">Instansi</label>
                <input type="text" id="sekolah_display"
                       class="w-full p-2 border rounded bg-gray-100 focus:outline-none"
                       placeholder="Instansi akan muncul otomatis" readonly />
            </div>

            <input type="hidden" name="peserta_id" id="peserta_id_hidden">

            <div class="flex justify-end gap-2 mt-4">
                <button type="submit" id="btnSavePeserta"
                        class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 disabled:opacity-50"
                        disabled>
                    Lanjutkan
                </button>
                <button type="button" id="btnCancelSelect"
                        class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">
                    Batal
                </button>
            </div>
        </form>

        <p class="text-xs text-gray-400 mt-4 text-center">
            Pengisian peserta untuk Pre-test, Post-test, dan Monev.
        </p>
    </div>
</div>
@endif

{{-- Scripts overlay pilih peserta --}}
@if ($showPesertaOverlay)
<script>
    document.getElementById('btnCancelSelect')?.addEventListener('click', function() {
        const f = document.getElementById('hiddenUnsetForm');
        if (f) { f.submit(); return; }
        location.reload();
    });

    document.getElementById('setPesertaForm')?.addEventListener('submit', function() {
        const btn = document.getElementById('btnSavePeserta');
        if (btn) { btn.disabled = true; btn.innerText = 'Menyimpan...'; }
    });

    (function(){
        const $form = document.getElementById('setPesertaForm');
        const $nama = document.getElementById('nama_input');
        const $inst = document.getElementById('sekolah_display');
        const $btn  = document.getElementById('btnSavePeserta');
        const $help = document.getElementById('nama_helper');
        const $pid  = document.getElementById('peserta_id_hidden');

        let t; let lastQuery = ''; let submitting = false;

        async function lookup(nama){
            const url = `{{ route('dashboard.ajax.peserta.instansiByNama') }}?nama=${encodeURIComponent(nama)}`;
            const res = await fetch(url, { headers: { 'X-Requested-With':'XMLHttpRequest' } });
            const data = await res.json().catch(()=>null);
            if (!res.ok || !data?.ok) throw new Error(data?.message || 'Lookup gagal');
            return data;
        }

        function setUI(data){
            const sekolah = data?.data?.instansi || '';
            const kota    = data?.data?.kota || '';
            const pid     = data?.data?.peserta_id || '';

            $inst.value   = sekolah ? (kota ? `${sekolah} (${kota})` : sekolah) : '';
            $pid.value    = pid;
            $btn.disabled = !(data?.ok && pid);
            if (data?.ok) $help.classList.add('hidden');

            if (data?.ok && pid && !submitting) {
                submitting = true;
                $btn.disabled = true;
                $btn.innerText = 'Menyimpan...';
                $form.requestSubmit();
            }
        }

        $nama?.addEventListener('input', () => {
            const q = ($nama.value || '').trim();
            $btn.disabled = true; $inst.value = ''; $pid.value = '';
            if (!q || submitting) return;

            clearTimeout(t);
            t = setTimeout(async () => {
                if (q === lastQuery || submitting) return;
                lastQuery = q;
                try {
                    const data = await lookup(q);
                    setUI(data);
                } catch (e) {
                    $btn.disabled = true; $inst.value = ''; $pid.value = '';
                    $help.textContent = 'Peserta tidak ditemukan. Coba tulis lebih spesifik.';
                    $help.classList.remove('hidden');
                }
            }, 250);
        });

        $nama?.addEventListener('keypress', (e) => {
            if (e.key === 'Enter' && !$btn.disabled && !submitting) {
                e.preventDefault();
                submitting = true;
                $btn.disabled = true;
                $btn.innerText = 'Menyimpan...';
                $form.requestSubmit();
            }
        });

        document.addEventListener('DOMContentLoaded', () => {
            const q = ($nama?.value || '').trim();
            if (q) $nama.dispatchEvent(new Event('input'));
        });
    })();
</script>
@endif
