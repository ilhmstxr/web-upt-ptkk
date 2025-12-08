@extends('dashboard.layouts.main')

@section('title', 'Home')
@section('page-title', 'Dashboard Home')

@push('styles')
    {{-- Font khusus halaman home --}}
    <link href="https://fonts.googleapis.com/css2?family=Volkhov:wght@700&family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        @keyframes glassFadeUp {
            0% { opacity: 0; transform: translateY(20px) scale(0.96); }
            100% { opacity: 1; transform: translateY(0) scale(1); }
        }
        .animate-glass-up {
            animation: glassFadeUp 0.8s cubic-bezier(0.2, 0.8, 0.2, 1) forwards;
        }
    </style>
@endpush

@section('content')
@php
    // =========================
    // SETUP DATA & LOGIC
    // =========================
    $pesertaAktif = $pesertaAktif ?? null;

    // ✅ overlay ditampilkan kalau belum ada session peserta_id
    $showTokenOverlay = !session()->has('peserta_id') || is_null($pesertaAktif);

    // status progress tes
    $preTestDone  = $preTestDone  ?? false;
    $postTestDone = $postTestDone ?? false;
    $monevDone    = $monevDone    ?? false;

    // materi progress
    $materiDoneCount = $materiDoneCount ?? 0;
    $totalMateri     = $totalMateri     ?? 15;
    $materiProgress  = ($totalMateri > 0)
        ? floor(($materiDoneCount / $totalMateri) * 100)
        : 0;

    // skor
    $preTestScore  = $preTestScore  ?? null;
    $postTestScore = $postTestScore ?? null;
    $monevScore    = $monevScore    ?? null;

    // attempts
    $preTestAttempts  = $preTestAttempts  ?? 0;
    $postTestAttempts = $postTestAttempts ?? 0;
    $monevAttempts    = $monevAttempts    ?? 0;

    // list materi (optional)
    $materiList = $materiList ?? collect();
@endphp

{{-- ================================================================== --}}
{{-- FULLSCREEN OVERLAY LOGIN (blur SELURUH halaman via backdrop-blur) --}}
{{-- ================================================================== --}}
@if($showTokenOverlay)
    <div class="fixed inset-0 z-[9999] flex flex-col items-center justify-center
                bg-slate-50/70 backdrop-blur-xl overflow-hidden">

        {{-- dekorasi gradient blob --}}
        <div class="absolute top-[-12%] left-[-8%] w-[560px] h-[560px]
                    bg-purple-300/30 rounded-full blur-[130px] pointer-events-none"></div>
        <div class="absolute bottom-[-12%] right-[-8%] w-[560px] h-[560px]
                    bg-blue-300/30 rounded-full blur-[130px] pointer-events-none"></div>

        {{-- card kaca --}}
        <div class="animate-glass-up relative w-full max-w-[560px] mx-4">
            <div class="relative flex flex-col items-center justify-center p-[46px] gap-[34px]
                        bg-white/60 backdrop-blur-2xl border border-white/50 rounded-[30px]
                        shadow-[0_20px_60px_-15px_rgba(0,0,0,0.12)]">

                {{-- header --}}
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

                {{-- form --}}
                <form action="{{ route('assessment.login.submit') }}" method="POST"
                      class="w-full flex flex-col gap-[22px]">
                    @csrf

                    {{-- error --}}
                    @if (session('error'))
                        <div class="bg-red-50/90 border border-red-100 text-red-600 px-4 py-3 rounded-2xl text-sm text-center font-[Montserrat] font-medium shadow-sm animate-pulse">
                            {{ session('error') }}
                        </div>
                    @endif

                    {{-- nomor registrasi --}}
                    <div class="space-y-2">
                        <label for="token" class="block font-[Montserrat] font-semibold text-base text-slate-800 ml-1">
                            Nomor Registrasi / Token
                        </label>
                        <input type="text" id="token" name="token"
                               class="w-full bg-white/85 border border-gray-200/80 rounded-2xl px-5 py-4
                                      text-gray-700 placeholder-gray-400 font-[Montserrat] text-base shadow-sm
                                      focus:outline-none focus:ring-[4px] focus:ring-[#1524AF]/10 focus:border-[#1524AF] transition-all"
                               placeholder="Masukkan Nomor Registrasi"
                               required autofocus autocomplete="off">
                    </div>

                    {{-- password tanggal lahir --}}
                    <div class="space-y-2">
                        <label for="password" class="block font-[Montserrat] font-semibold text-base text-slate-800 ml-1">
                            Password (Tanggal Lahir)
                        </label>
                        <div class="relative">
                            <input type="password" id="password" name="password"
                                   class="w-full bg-white/85 border border-gray-200/80 rounded-2xl px-5 py-4
                                          text-gray-700 placeholder-gray-400 font-[Montserrat] text-base shadow-sm
                                          focus:outline-none focus:ring-[4px] focus:ring-[#1524AF]/10 focus:border-[#1524AF]
                                          transition-all tracking-widest"
                                   placeholder="ddmmyyyy" required>
                            <div class="absolute inset-y-0 right-0 pr-5 flex items-center pointer-events-none">
                                <span class="text-xs font-bold text-gray-300 font-mono bg-gray-50 px-2 py-1 rounded">123</span>
                            </div>
                        </div>
                    </div>

                    <div class="w-full h-px bg-gradient-to-r from-transparent via-gray-300 to-transparent my-1"></div>

                    {{-- actions --}}
                    <div class="flex items-center gap-4">
                        <a href="{{ url('/') }}"
                           class="flex-1 flex justify-center items-center py-3.5 px-6 rounded-2xl border-2 border-[#1524AF]
                                  text-[#1524AF] font-[Montserrat] font-bold text-base hover:bg-blue-50/50 transition-all duration-300">
                            Kembali
                        </a>
                        <button type="submit"
                                class="flex-[2] flex justify-center items-center gap-2 bg-[#1524AF] hover:bg-[#0f1a8e]
                                       text-white py-3.5 px-6 rounded-2xl font-[Montserrat] font-bold text-base
                                       shadow-[0_10px_30px_-10px_rgba(21,36,175,0.5)]
                                       transition-all duration-300 transform hover:-translate-y-0.5 active:scale-95">
                            Login
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                      d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                            </svg>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- footer --}}
        <div class="absolute bottom-8 text-center animate-glass-up" style="animation-delay: 0.3s;">
            <p class="text-slate-500 text-xs font-[Montserrat] font-medium tracking-wide
                      bg-white/40 px-4 py-1.5 rounded-full backdrop-blur-sm border border-white/20">
                © {{ date('Y') }} UPT. PTKK Dinas Pendidikan Prov. Jawa Timur
            </p>
        </div>
    </div>
@endif

{{-- ===================================================== --}}
{{-- DASHBOARD CONTENT (normal) --}}
{{-- ===================================================== --}}
<div class="grid grid-cols-1 xl:grid-cols-12 gap-6">

    {{-- ===================== --}}
    {{-- KONTEN UTAMA (LEFT) --}}
    {{-- ===================== --}}
    <div class="xl:col-span-9">

        {{-- Header Dashboard --}}
        <div class="mb-8">
            @if ($pesertaAktif)
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4
                            bg-gradient-to-r from-white to-blue-50/50 p-6 rounded-3xl
                            border border-slate-100 shadow-sm">
                    <div>
                        <h2 class="text-2xl md:text-3xl font-bold text-slate-800 font-[Volkhov]">
                            Hai, <span class="text-blue-600">{{ explode(' ', $pesertaAktif->nama)[0] }}</span> 👋
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
            @endif
        </div>

        {{-- Grid Menu Utama --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

            {{-- Materi --}}
            <div class="bg-white p-6 rounded-[24px] shadow-[0_4px_20px_-4px_rgba(0,0,0,0.06)]
                        border border-slate-100 flex flex-col justify-between hover:border-blue-200 hover:shadow-lg
                        transition-all duration-300 group">
                <div>
                    <div class="flex justify-between items-start mb-4">
                        <div class="p-3 bg-blue-50 text-blue-600 rounded-2xl group-hover:bg-blue-600 group-hover:text-white transition-colors duration-300">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253"/>
                            </svg>
                        </div>
                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-slate-100 text-slate-500 uppercase tracking-wide">
                            Modul
                        </span>
                    </div>
                    <h3 class="text-lg font-bold text-slate-800 mb-2">Materi Pelatihan</h3>
                    <p class="text-sm text-slate-500 leading-relaxed mb-6">
                        Akses modul pembelajaran dan materi inti.
                    </p>
                </div>

                @if($materiDoneCount >= $totalMateri && $totalMateri > 0)
                    <div class="mt-auto flex items-center gap-2 text-green-600 bg-green-50 px-4 py-3 rounded-xl border border-green-100">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4"/>
                        </svg>
                        <span class="text-sm font-semibold">Selesai ({{ $materiDoneCount }}/{{ $totalMateri }})</span>
                    </div>
                @else
                    <a href="{{ route('dashboard.materi.index') }}"
                       class="mt-auto w-full flex items-center justify-center gap-2 bg-slate-900 text-white
                              py-3 px-4 rounded-xl font-semibold text-sm hover:bg-slate-800 transition-colors">
                        Lanjutkan Belajar
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                        </svg>
                    </a>
                @endif
            </div>

            {{-- Pretest --}}
            <div class="bg-white p-6 rounded-[24px] shadow-[0_4px_20px_-4px_rgba(0,0,0,0.06)]
                        border border-slate-100 flex flex-col justify-between hover:border-yellow-200 hover:shadow-lg
                        transition-all duration-300 group">
                <div>
                    <div class="flex justify-between items-start mb-4">
                        <div class="p-3 bg-yellow-50 text-yellow-600 rounded-2xl group-hover:bg-yellow-500 group-hover:text-white transition-colors duration-300">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707"/>
                            </svg>
                        </div>
                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-red-100 text-red-600 uppercase tracking-wide">
                            Wajib
                        </span>
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
                            ✓
                        </div>
                    </div>
                @else
                    <a href="{{ route('dashboard.pretest.index') }}"
                       class="mt-auto w-full flex items-center justify-center gap-2 bg-yellow-500 text-white
                              py-3 px-4 rounded-xl font-semibold text-sm hover:bg-yellow-600 transition-colors">
                        Kerjakan Sekarang
                    </a>
                @endif
            </div>

            {{-- Posttest --}}
            <div class="bg-white p-6 rounded-[24px] shadow-[0_4px_20px_-4px_rgba(0,0,0,0.06)]
                        border border-slate-100 flex flex-col justify-between hover:border-green-200 hover:shadow-lg
                        transition-all duration-300 group">
                <div>
                    <div class="flex justify-between items-start mb-4">
                        <div class="p-3 bg-green-50 text-green-600 rounded-2xl group-hover:bg-green-600 group-hover:text-white transition-colors duration-300">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M9 5H7a2 2 0 00-2 2v12"/>
                            </svg>
                        </div>
                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-red-100 text-red-600 uppercase tracking-wide">
                            Wajib
                        </span>
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
                            ✓
                        </div>
                    </div>
                @else
                    <a href="{{ route('dashboard.posttest.index') }}"
                       class="mt-auto w-full flex items-center justify-center gap-2 bg-green-600 text-white
                              py-3 px-4 rounded-xl font-semibold text-sm hover:bg-green-700 transition-colors">
                        Mulai Ujian
                    </a>
                @endif
            </div>

            {{-- Monev --}}
            <div class="bg-white p-6 rounded-[24px] shadow-[0_4px_20px_-4px_rgba(0,0,0,0.06)]
                        border border-slate-100 flex flex-col justify-between hover:border-indigo-200 hover:shadow-lg
                        transition-all duration-300 group">
                <div>
                    <div class="flex justify-between items-start mb-4">
                        <div class="p-3 bg-indigo-50 text-indigo-600 rounded-2xl group-hover:bg-indigo-600 group-hover:text-white transition-colors duration-300">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M11 5H6a2 2 0 00-2 2v11"/>
                            </svg>
                        </div>
                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-red-100 text-red-600 uppercase tracking-wide">
                            Wajib
                        </span>
                    </div>
                    <h3 class="text-lg font-bold text-slate-800 mb-2">MONEV</h3>
                    <p class="text-sm text-slate-500 leading-relaxed mb-6">Survey evaluasi penyelenggaraan.</p>
                </div>

                @if($monevDone)
                    <div class="mt-auto bg-indigo-50 rounded-xl p-4 border border-indigo-100 flex items-center gap-2">
                        <div class="h-8 w-8 rounded-full bg-white text-indigo-600 flex items-center justify-center shadow-sm">
                            ✓
                        </div>
                        <span class="text-sm font-semibold text-indigo-700">Survey Terisi</span>
                    </div>
                @else
                    <a href="{{ route('dashboard.survey') }}"
                       class="mt-auto w-full flex items-center justify-center gap-2 bg-indigo-600 text-white
                              py-3 px-4 rounded-xl font-semibold text-sm hover:bg-indigo-700 transition-colors">
                        Isi Survey
                    </a>
                @endif
            </div>
        </div>

        {{-- Progress ringkas --}}
        <div class="mt-10 grid grid-cols-2 md:grid-cols-4 gap-6">
            @foreach([
                ['label'=>'Materi',   'val'=>$materiDoneCount,    'max'=>$totalMateri, 'color'=>'bg-blue-500'],
                ['label'=>'Pre-Test', 'val'=>($preTestDone?1:0),  'max'=>1,            'color'=>'bg-yellow-500'],
                ['label'=>'Post-Test','val'=>($postTestDone?1:0), 'max'=>1,            'color'=>'bg-green-500'],
                ['label'=>'Monev',    'val'=>($monevDone?1:0),    'max'=>1,            'color'=>'bg-indigo-500'],
            ] as $stat)
                <div class="bg-white p-4 rounded-2xl border border-slate-100 flex flex-col items-center shadow-sm">
                    <div class="text-[10px] uppercase font-bold text-slate-400 mb-1">{{ $stat['label'] }}</div>
                    <div class="text-xl font-bold text-slate-700 mb-2">
                        {{ $stat['val'] }} <span class="text-slate-300 text-sm">/ {{ $stat['max'] }}</span>
                    </div>
                    <div class="w-full h-1.5 bg-slate-100 rounded-full overflow-hidden">
                        <div class="{{ $stat['color'] }} h-full rounded-full transition-all duration-1000"
                             style="width: {{ $stat['max'] > 0 ? ($stat['val']/$stat['max'])*100 : 0 }}%">
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    {{-- ===================== --}}
    {{-- SIDEBAR MATERI (RIGHT) --}}
    {{-- ===================== --}}
    <aside class="xl:col-span-3">
        <div class="sticky top-6 space-y-4">

            <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-5">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="font-bold text-slate-800">Materi Pelatihan</h3>
                    <span class="text-xs font-semibold text-slate-500">
                        {{ $materiDoneCount }}/{{ $totalMateri }}
                    </span>
                </div>

                {{-- progress materi --}}
                <div class="mb-4">
                    <div class="flex justify-between text-xs text-slate-500 font-medium mb-1">
                        <span>Progress</span>
                        <span>{{ $materiProgress }}%</span>
                    </div>
                    <div class="w-full h-2 bg-slate-100 rounded-full overflow-hidden">
                        <div class="bg-blue-600 h-full rounded-full transition-all duration-1000"
                             style="width: {{ $materiProgress }}%"></div>
                    </div>
                </div>

                {{-- list materi --}}
                <div class="space-y-2 max-h-[420px] overflow-auto pr-1">
                    @forelse($materiList as $i => $m)
                        @php
                            $judul = $m->judul ?? $m['judul'] ?? ('Materi '.($i+1));
                            $done  = $m->is_done ?? $m['is_done'] ?? false;
                            $route = $m->route ?? $m['route'] ?? route('dashboard.materi.index');
                        @endphp

                        <a href="{{ $route }}"
                           class="flex items-center gap-3 p-3 rounded-2xl border
                                  {{ $done ? 'bg-green-50 border-green-100' : 'bg-slate-50 border-slate-100 hover:bg-slate-100' }}
                                  transition">
                            <div class="h-8 w-8 rounded-xl flex items-center justify-center text-xs font-bold
                                        {{ $done ? 'bg-green-600 text-white' : 'bg-white text-slate-600 border border-slate-200' }}">
                                {{ $i+1 }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="text-sm font-semibold text-slate-800 truncate">{{ $judul }}</div>
                                <div class="text-[11px] text-slate-500">
                                    {{ $done ? 'Selesai' : 'Belum dikerjakan' }}
                                </div>
                            </div>
                            @if($done)
                                <span class="text-green-600 font-bold text-sm">✓</span>
                            @endif
                        </a>
                    @empty
                        @for($i=1; $i<=5; $i++)
                            <div class="flex items-center gap-3 p-3 rounded-2xl border bg-slate-50 border-slate-100">
                                <div class="h-8 w-8 rounded-xl bg-white border border-slate-200 flex items-center justify-center text-xs font-bold text-slate-600">
                                    {{ $i }}
                                </div>
                                <div class="flex-1">
                                    <div class="h-3 w-3/4 bg-slate-200 rounded"></div>
                                    <div class="h-2 w-1/2 bg-slate-100 rounded mt-2"></div>
                                </div>
                            </div>
                        @endfor
                        <p class="text-xs text-slate-400 mt-2 text-center">
                            Materi akan muncul setelah data dikirim dari controller.
                        </p>
                    @endforelse
                </div>

                <div class="mt-4">
                    <a href="{{ route('dashboard.materi.index') }}"
                       class="w-full inline-flex items-center justify-center gap-2 bg-blue-600 text-white py-3 rounded-2xl font-semibold text-sm hover:bg-blue-700 transition">
                        Buka Semua Materi
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                        </svg>
                    </a>
                </div>
            </div>

            <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-5">
                <h4 class="font-semibold text-slate-800 mb-1">Catatan</h4>
                <p class="text-sm text-slate-500 leading-relaxed">
                    Selesaikan semua materi sebelum Post-Test dan Monev untuk hasil terbaik.
                </p>
            </div>

        </div>
    </aside>
</div>
@endsection
