@extends('dashboard.layouts.main')
@section('title', 'Home')
@section('page-title', 'Dashboard Home')

@section('content')

@php
    // --- SETUP DATA & LOGIC ---
    $pesertaAktif       = $pesertaAktif       ?? null;
    $showTokenOverlay   = is_null($pesertaAktif); 

    // Status Progress
    $preTestDone        = $preTestDone        ?? false;
    $postTestDone       = $postTestDone       ?? false;
    $monevDone          = $monevDone          ?? false;
    
    // Variabel Materi
    $materiDoneCount    = $materiDoneCount    ?? 0;
    $totalMateri        = $totalMateri        ?? 15; 
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
    /* Animasi Masuk yang Lembut */
    @keyframes glassFadeUp {
        0% { opacity: 0; transform: translateY(20px) scale(0.95); }
        100% { opacity: 1; transform: translateY(0) scale(1); }
    }
    .animate-glass-up {
        animation: glassFadeUp 0.8s cubic-bezier(0.2, 0.8, 0.2, 1) forwards;
    }
    
    /* Background Animasi Lambat */
    @keyframes gradientMove {
        0% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
        100% { background-position: 0% 50%; }
    }
    .animated-bg {
        background: linear-gradient(-45deg, #ee7752, #e73c7e, #23a6d5, #23d5ab); /* Fallback */
        background-size: 400% 400%;
        /* animation: gradientMove 15s ease infinite; */ /* Opsional jika ingin gerak */
    }
</style>

{{-- ========================================== --}}
{{-- FULLSCREEN PREMIUM GLASS OVERLAY --}}
{{-- ========================================== --}}
@if($showTokenOverlay)
    <div class="fixed inset-0 z-[9999] flex flex-col items-center justify-center min-h-screen bg-[#F0F4F8]/70 backdrop-blur-md overflow-hidden">
        
        {{-- Dekorasi Bola Gradient (Untuk Efek Depth) --}}
        <div class="absolute top-[-10%] left-[-5%] w-[500px] h-[500px] bg-purple-300/30 rounded-full blur-[120px] pointer-events-none"></div>
        <div class="absolute bottom-[-10%] right-[-5%] w-[500px] h-[500px] bg-blue-300/30 rounded-full blur-[120px] pointer-events-none"></div>

        {{-- KARTU KACA UTAMA --}}
        <div class="animate-glass-up relative w-full max-w-[557px] mx-4">
            
            {{-- Container Glassmorphism --}}
            <div class="relative flex flex-col items-center justify-center p-[50px] gap-[40px] bg-white/60 backdrop-blur-2xl border border-white/50 rounded-[30px] shadow-[0_20px_60px_-15px_rgba(0,0,0,0.1)]">
                
                {{-- 1. Header Section --}}
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

                {{-- 2. Form Section --}}
                <form action="{{ route('assessment.login.submit') }}" method="POST" class="w-full flex flex-col gap-[24px]">
                    @csrf

                    {{-- Alert Error (Floating Style) --}}
                    @if (session('error'))
                        <div class="bg-red-50/90 border border-red-100 text-red-600 px-4 py-3 rounded-2xl text-sm text-center font-[Montserrat] font-medium shadow-sm animate-pulse">
                            {{ session('error') }}
                        </div>
                    @endif

                    {{-- Input: ID Peserta --}}
                    <div class="space-y-2">
                        <label for="token" class="block font-[Montserrat] font-semibold text-base text-[#1e293b] ml-1">
                            ID Peserta
                        </label>
                        <div class="relative group transition-all duration-300">
                            <input type="text" 
                                   id="token" 
                                   name="token" 
                                   class="w-full bg-white/80 border border-gray-200/80 rounded-2xl px-5 py-4 text-gray-700 placeholder-gray-400 font-[Montserrat] text-base shadow-sm focus:outline-none focus:ring-[4px] focus:ring-[#1524AF]/10 focus:border-[#1524AF] transition-all"
                                   placeholder="Masukkan ID Peserta"
                                   required
                                   autofocus
                                   autocomplete="off">
                        </div>
                    </div>

                    {{-- Input: Password --}}
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
                            
                            {{-- Hint di kanan --}}
                            <div class="absolute inset-y-0 right-0 pr-5 flex items-center pointer-events-none">
                                <span class="text-xs font-bold text-gray-300 font-mono bg-gray-50 px-2 py-1 rounded">123</span>
                            </div>
                        </div>
                    </div>

                    {{-- Divider Tipis --}}
                    <div class="w-full h-px bg-gradient-to-r from-transparent via-gray-300 to-transparent my-2"></div>

                    {{-- 3. Action Buttons --}}
                    <div class="flex items-center gap-4">
                        <a href="{{ url('/') }}" 
                           class="flex-1 flex justify-center items-center py-4 px-6 rounded-2xl border-2 border-[#1524AF] text-[#1524AF] font-[Montserrat] font-bold text-base hover:bg-blue-50/50 transition-all duration-300">
                            Kembali
                        </a>
                        <button type="submit" 
                                class="flex-[2] flex justify-center items-center gap-2 bg-[#1524AF] hover:bg-[#0f1a8e] text-white py-4 px-6 rounded-2xl font-[Montserrat] font-bold text-base shadow-[0_10px_30px_-10px_rgba(21,36,175,0.5)] transition-all duration-300 hover:shadow-[0_15px_35px_-10px_rgba(21,36,175,0.6)] transform hover:-translate-y-0.5 active:scale-95">
                            Login
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </button>
                    </div>

                </form>
            </div>
        </div>

        {{-- Footer UPT --}}
        <div class="absolute bottom-8 text-center animate-glass-up" style="animation-delay: 0.3s;">
            <p class="text-slate-500 text-xs font-[Montserrat] font-medium tracking-wide bg-white/40 px-4 py-1.5 rounded-full backdrop-blur-sm border border-white/20">
                © {{ date('Y') }} UPT. PTKK Dinas Pendidikan Prov. Jawa Timur
            </p>
        </div>

    </div>
@endif


{{-- ========================================== --}}
{{-- KONTEN DASHBOARD (Blur Background Effect) --}}
{{-- ========================================== --}}
<div class="relative transition-all duration-700 ease-in-out {{ $showTokenOverlay ? 'filter blur-[12px] opacity-40 scale-[0.98] pointer-events-none select-none h-screen overflow-hidden' : '' }}">

    {{-- Flash Messages --}}
    @if (!$showTokenOverlay)
        @if (session('success'))
            <div class="mb-6 px-5 py-4 rounded-2xl bg-green-50 text-green-700 border border-green-100 flex items-center gap-3 shadow-sm animate-glass-up">
                <div class="bg-green-100 rounded-full p-1"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg></div>
                <span class="font-medium">{{ session('success') }}</span>
            </div>
        @endif
        @if (session('error'))
            <div class="mb-6 px-5 py-4 rounded-2xl bg-red-50 text-red-700 border border-red-100 flex items-center gap-3 shadow-sm animate-glass-up">
                <div class="bg-red-100 rounded-full p-1"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></div>
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
                        Hai, <span class="text-blue-600">{{ explode(' ', $pesertaAktif->nama)[0] }}</span> 👋
                    </h2>
                    <p class="text-slate-500 mt-2 font-[Montserrat]">
                        Selamat datang di dashboard pelatihan kompetensi.
                    </p>
                    @if(session('instansi_nama'))
                        <div class="mt-3 inline-flex items-center gap-2 px-3 py-1 bg-white border border-slate-200 rounded-full text-xs font-medium text-slate-600 shadow-sm">
                            <svg class="w-3.5 h-3.5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                            {{ session('instansi_nama') }}{{ session('instansi_kota') ? ' ('.session('instansi_kota').')' : '' }}
                        </div>
                    @endif
                </div>
            </div>
        @else
            {{-- Dummy Header (Visual saat diblur) --}}
            <div class="p-6 rounded-3xl border border-dashed border-slate-200 bg-slate-50">
                <div class="h-8 w-1/3 bg-slate-200 rounded-lg mb-4"></div>
                <div class="h-4 w-1/2 bg-slate-200 rounded-lg"></div>
            </div>
        @endif
    </div>

    {{-- Grid Menu Utama --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        
        {{-- Card 1: Materi --}}
        <div class="bg-white p-6 rounded-[24px] shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] border border-slate-100 flex flex-col justify-between hover:border-blue-200 hover:shadow-lg transition-all duration-300 group">
            <div>
                <div class="flex justify-between items-start mb-4">
                    <div class="p-3 bg-blue-50 text-blue-600 rounded-2xl group-hover:bg-blue-600 group-hover:text-white transition-colors duration-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    </div>
                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-slate-100 text-slate-500 uppercase tracking-wide">Modul</span>
                </div>
                <h3 class="text-lg font-bold text-slate-800 mb-2">Materi Pelatihan</h3>
                <p class="text-sm text-slate-500 leading-relaxed mb-6">Akses modul pembelajaran dan materi inti.</p>
            </div>
            
            @if($materiDoneCount >= $totalMateri && $totalMateri > 0)
                <div class="mt-auto flex items-center gap-2 text-green-600 bg-green-50 px-4 py-3 rounded-xl border border-green-100">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span class="text-sm font-semibold">Selesai ({{ $materiDoneCount }}/{{ $totalMateri }})</span>
                </div>
            @else
                <a href="{{ route('dashboard.materi.index') }}" class="mt-auto w-full flex items-center justify-center gap-2 bg-slate-900 text-white py-3 px-4 rounded-xl font-semibold text-sm hover:bg-slate-800 transition-colors group-hover:shadow-lg">
                    Lanjutkan Belajar
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </a>
            @endif
        </div>

        {{-- Card 2: Pre-Test --}}
        <div class="bg-white p-6 rounded-[24px] shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] border border-slate-100 flex flex-col justify-between hover:border-yellow-200 hover:shadow-lg transition-all duration-300 group">
            <div>
                <div class="flex justify-between items-start mb-4">
                    <div class="p-3 bg-yellow-50 text-yellow-600 rounded-2xl group-hover:bg-yellow-500 group-hover:text-white transition-colors duration-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>
                    </div>
                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-red-100 text-red-600 uppercase tracking-wide">Wajib</span>
                </div>
                <h3 class="text-lg font-bold text-slate-800 mb-2">Pre-Test</h3>
                <p class="text-sm text-slate-500 leading-relaxed mb-6">Ukur kompetensi awal sebelum memulai.</p>
            </div>

            @if(!empty($preTestDone))
                <div class="mt-auto bg-yellow-50 rounded-xl p-4 border border-yellow-100 flex justify-between items-center">
                    <div>
                        <div class="text-[10px] uppercase text-slate-400 font-bold tracking-wider">Nilai</div>
                        <div class="text-2xl font-bold text-yellow-600">{{ $preTestScore ?? '-' }}</div>
                    </div>
                    <div class="h-8 w-8 rounded-full bg-white text-yellow-500 flex items-center justify-center shadow-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    </div>
                </div>
            @else
                <a href="{{ route('dashboard.pretest.index') }}" class="mt-auto w-full flex items-center justify-center gap-2 bg-yellow-500 text-white py-3 px-4 rounded-xl font-semibold text-sm hover:bg-yellow-600 transition-colors shadow-yellow-200">
                    Kerjakan Sekarang
                </a>
            @endif
        </div>

        {{-- Card 3: Post-Test --}}
        <div class="bg-white p-6 rounded-[24px] shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] border border-slate-100 flex flex-col justify-between hover:border-green-200 hover:shadow-lg transition-all duration-300 group">
            <div>
                <div class="flex justify-between items-start mb-4">
                    <div class="p-3 bg-green-50 text-green-600 rounded-2xl group-hover:bg-green-600 group-hover:text-white transition-colors duration-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                    </div>
                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-red-100 text-red-600 uppercase tracking-wide">Wajib</span>
                </div>
                <h3 class="text-lg font-bold text-slate-800 mb-2">Post-Test</h3>
                <p class="text-sm text-slate-500 leading-relaxed mb-6">Evaluasi akhir kompetensi.</p>
            </div>

            @if(!empty($postTestDone))
                <div class="mt-auto bg-green-50 rounded-xl p-4 border border-green-100 flex justify-between items-center">
                    <div>
                        <div class="text-[10px] uppercase text-slate-400 font-bold tracking-wider">Nilai</div>
                        <div class="text-2xl font-bold text-green-600">{{ $postTestScore ?? '-' }}</div>
                    </div>
                    <div class="h-8 w-8 rounded-full bg-white text-green-500 flex items-center justify-center shadow-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    </div>
                </div>
            @else
                <a href="{{ route('dashboard.posttest.index') }}" class="mt-auto w-full flex items-center justify-center gap-2 bg-green-600 text-white py-3 px-4 rounded-xl font-semibold text-sm hover:bg-green-700 transition-colors shadow-green-200">
                    Mulai Ujian
                </a>
            @endif
        </div>

        {{-- Card 4: Monev --}}
        <div class="bg-white p-6 rounded-[24px] shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] border border-slate-100 flex flex-col justify-between hover:border-indigo-200 hover:shadow-lg transition-all duration-300 group">
            <div>
                <div class="flex justify-between items-start mb-4">
                    <div class="p-3 bg-indigo-50 text-indigo-600 rounded-2xl group-hover:bg-indigo-600 group-hover:text-white transition-colors duration-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    </div>
                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-red-100 text-red-600 uppercase tracking-wide">Wajib</span>
                </div>
                <h3 class="text-lg font-bold text-slate-800 mb-2">MONEV</h3>
                <p class="text-sm text-slate-500 leading-relaxed mb-6">Survey evaluasi penyelenggaraan.</p>
            </div>

            @if(!empty($monevDone))
                <div class="mt-auto bg-indigo-50 rounded-xl p-4 border border-indigo-100 flex items-center gap-3">
                    <div class="h-8 w-8 rounded-full bg-white text-indigo-600 flex items-center justify-center shadow-sm shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    </div>
                    <span class="text-sm font-semibold text-indigo-700">Survey Terisi</span>
                </div>
            @else
                <a href="{{ route('dashboard.survey') }}" class="mt-auto w-full flex items-center justify-center gap-2 bg-indigo-600 text-white py-3 px-4 rounded-xl font-semibold text-sm hover:bg-indigo-700 transition-colors shadow-indigo-200">
                    Isi Survey
                </a>
            @endif
        </div>
    </div>

    {{-- Progress Stats Section (Optional Decoration) --}}
    <div class="mt-10 grid grid-cols-2 md:grid-cols-4 gap-6 px-4">
        @foreach([
            ['label' => 'Materi', 'val' => $materiDoneCount, 'max' => $totalMateri, 'color' => 'bg-blue-500'],
            ['label' => 'Pre-Test', 'val' => ($preTestDone?1:0), 'max' => 1, 'color' => 'bg-yellow-500'],
            ['label' => 'Post-Test', 'val' => ($postTestDone?1:0), 'max' => 1, 'color' => 'bg-green-500'],
            ['label' => 'Monev', 'val' => ($monevDone?1:0), 'max' => 1, 'color' => 'bg-indigo-500']
        ] as $stat)
            <div class="bg-white p-4 rounded-2xl border border-slate-100 flex flex-col items-center justify-center shadow-sm">
                <div class="text-[10px] uppercase font-bold text-slate-400 mb-1">{{ $stat['label'] }}</div>
                <div class="text-xl font-bold text-slate-700 mb-2">{{ $stat['val'] }} <span class="text-slate-300 text-sm">/ {{ $stat['max'] }}</span></div>
                <div class="w-full h-1.5 bg-slate-100 rounded-full overflow-hidden">
                    <div class="{{ $stat['color'] }} h-full rounded-full transition-all duration-1000" style="width: {{ ($stat['max'] > 0) ? ($stat['val']/$stat['max'])*100 : 0 }}%"></div>
                </div>
            </div>
        @endforeach
    </div>

</div>

@endsection