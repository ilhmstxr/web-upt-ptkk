@extends('dashboard.layouts.main')
@section('title', 'Home')
@section('page-title', 'Dashboard Home')

@section('content')

@php
    // Pastikan semua variabel punya nilai default biar nggak undefined
    $pesertaAktif      = $pesertaAktif      ?? null;
    $preTestDone       = $preTestDone       ?? false;
    $postTestDone      = $postTestDone      ?? false;
    $monevDone         = $monevDone         ?? false;
    
    // VARIABEL UNTUK MATERI
    $materiDoneCount   = $materiDoneCount   ?? 0;
    $totalMateri       = $totalMateri       ?? 15; // CATATAN: Idealnya $totalMateri diambil dari Controller (hitung total materi di DB)
    $materiProgress    = ($totalMateri > 0) ? floor(($materiDoneCount / $totalMateri) * 100) : 0;

    $preTestScore      = $preTestScore      ?? null;
    $postTestScore     = $postTestScore     ?? null;
    $monevScore        = $monevScore        ?? null;
    $preTestAttempts   = $preTestAttempts   ?? 0;
    $postTestAttempts  = $postTestAttempts  ?? 0;
    $monevAttempts     = $monevAttempts     ?? 0;
@endphp

{{-- Flash messages --}}
@if (session('success'))
    <div class="mb-4 px-4 py-3 rounded-lg bg-green-100 text-green-700 font-semibold" role="alert">
        {{ session('success') }}
    </div>
@endif

@if (session('error'))
    <div class="mb-4 px-4 py-3 rounded-lg bg-red-100 text-red-700 font-semibold" role="alert">
        {{ session('error') }}
    </div>
@endif

@php $isBlur = false; @endphp

<div>
    @if ($pesertaAktif)
        <div class="mb-6 flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold">
                    Hai, {{ explode(' ', $pesertaAktif->nama)[0] ?? 'Peserta' }} 👋
                </h2>
                <p class="text-gray-600">
                    Selamat datang di dashboard pelatihan
                    @if(session('instansi_nama'))
                        <br>
                        <span class="text-sm text-gray-500">
                            {{ session('instansi_nama') }}{{ session('instansi_kota') ? ' ('.session('instansi_kota').')' : '' }}
                        </span>
                    @endif
                </p>
            </div>
            <div class="flex gap-2">
                {{-- Opsi action (disabled by default) --}}
            </div>
        </div>
    @endif

    {{-- Cards Ujian & Materi --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        
        {{-- Materi Pelatihan (NEW CARD) --}}
        <div class="bg-white p-6 rounded-2xl shadow-sm flex flex-col justify-between transition-transform duration-300 hover:-translate-y-1 hover:shadow-lg">
            <div>
                <div class="flex justify-between items-start">
                    <h3 class="text-xl font-bold text-gray-800">Materi Pelatihan</h3>
                    <span class="bg-indigo-100 text-indigo-700 text-xs font-semibold px-3 py-1 rounded-full">Inti</span>
                </div>
                <p class="text-gray-500 mt-2 mb-4">Akses semua modul dan materi yang harus kamu kuasai.</p>
            </div>
            
            @if($materiDoneCount >= $totalMateri && $totalMateri > 0)
                <div class="mt-4 text-center">
                    <button disabled class="inline-block px-3 py-2 bg-gray-100 text-gray-700 rounded cursor-not-allowed">
                        Semua Selesai
                    </button>
                    <p class="mt-2 text-sm text-gray-600">Total: {{ $materiDoneCount }} dari {{ $totalMateri }} modul</p>
                </div>
            @else
                <a href="{{ route('dashboard.materi.index') }}"
                    class="w-full block text-center bg-indigo-600 text-white font-semibold py-3 px-6 rounded-lg hover:bg-indigo-700 transition-colors">
                    Lanjutkan Materi
                </a>
            @endif
        </div>

        {{-- Pre-Test --}}
        <div class="bg-white p-6 rounded-2xl shadow-sm flex flex-col justify-between transition-transform duration-300 hover:-translate-y-1 hover:shadow-lg">
            <div>
                <div class="flex justify-between items-start">
                    <h3 class="text-lg font-bold text-gray-800">Pre-Test</h3>
                    <span class="bg-yellow-100 text-yellow-700 text-xs font-semibold px-3 py-1 rounded-full">Wajib</span>
                </div>
                <p class="text-gray-500 mt-2 mb-4">Cek kesiapanmu sebelum mengikuti materi.</p>
            </div>

            @if(!empty($preTestDone))
                <div class="mt-4 text-center">
                    <button disabled class="inline-block px-3 py-2 bg-gray-100 text-gray-700 rounded cursor-not-allowed">
                        Sudah dikerjakan
                    </button>
                    @if(!is_null($preTestScore))
                        <p class="mt-2 text-sm text-gray-600">Nilai: <strong>{{ $preTestScore }}</strong></p>
                    @endif
                </div>
            @else
                <a href="{{ route('dashboard.pretest.index') }}"
                    class="w-full block text-center bg-yellow-400 text-yellow-900 font-semibold py-3 px-6 rounded-lg hover:bg-yellow-500 transition-colors">
                    Kerjakan Pre-Test
                </a>
            @endif
        </div>

        {{-- Post-Test --}}
        <div class="bg-white p-6 rounded-2xl shadow-sm flex flex-col justify-between transition-transform duration-300 hover:-translate-y-1 hover:shadow-lg">
            <div>
                <div class="flex justify-between items-start">
                    <h3 class="text-lg font-bold text-gray-800">Post-Test</h3>
                    <span class="bg-green-100 text-green-700 text-xs font-semibold px-3 py-1 rounded-full">Wajib</span>
                </div>
                <p class="text-gray-500 mt-2 mb-4">Evaluasi hasil belajarmu untuk peningkatan.</p>
            </div>

            @if(!empty($postTestDone))
                <div class="mt-4 text-center">
                    <button disabled class="inline-block px-3 py-2 bg-gray-100 text-gray-700 rounded cursor-not-allowed">
                        Sudah dikerjakan
                    </button>

                    @if(!is_null($postTestScore))
                        <p class="mt-2 text-sm text-gray-600">Nilai: <strong>{{ $postTestScore }}</strong></p>
                    @endif
                </div>
            @else
                <a href="{{ route('dashboard.posttest.index') }}"
                    class="w-full block text-center bg-green-500 text-white font-semibold py-3 px-6 rounded-lg hover:bg-green-600 transition-colors">
                    Mulai Post-Test
                </a>
            @endif
        </div>

        {{-- MONEV / Survey --}}
        <div class="bg-white p-6 rounded-2xl shadow-sm flex flex-col justify-between transition-transform duration-300 hover:-translate-y-1 hover:shadow-lg">
            <div>
                <div class="flex justify-between items-start">
                    <h3 class="text-xl font-bold text-gray-800">MONEV</h3>
                    <span class="bg-blue-100 text-blue-700 text-xs font-semibold px-3 py-1 rounded-full">Wajib</span>
                </div>
                <p class="text-gray-500 mt-2">Akses Monitoring dan Evaluasi Selama Mengikuti Pelatihan.</p>
            </div>

            @if(!empty($monevDone))
                <div class="mt-4 text-center">
                    <button disabled class="inline-block px-3 py-2 bg-gray-100 text-gray-700 rounded cursor-not-allowed">
                        Sudah dikerjakan
                    </button>
                    @if(!is_null($monevScore))
                        <p class="mt-2 text-sm text-gray-600">Nilai: <strong>{{ $monevScore }}</strong></p>
                    @endif
                </div>
            @else
                <a href="{{ route('dashboard.survey') }}"
                    class="mt-6 block text-center w-full lg:w-auto bg-blue-600 text-white font-semibold py-3 px-6 rounded-lg hover:bg-blue-700 transition-colors">
                    Mulai Survey
                </a>
            @endif
        </div>
    </div>

    {{-- Progress (Ditambah Progress Materi) --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mt-6">
        
        {{-- Progress Materi (NEW PROGRESS BAR) --}}
        <div class="bg-white p-6 rounded-2xl shadow-sm transition-transform duration-300 hover:-translate-y-1 hover:shadow-lg">
            <h3 class="text-lg font-bold text-gray-800">Progress Materi</h3>
            <div class="flex items-baseline mt-2">
                <span class="text-3xl font-bold text-indigo-600">{{ $materiDoneCount }}</span>
                <span class="text-lg text-gray-500 ml-1">/ {{ $totalMateri }} modul selesai</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2.5 mt-4">
                <div class="bg-indigo-500 h-2.5 rounded-full" style="width: {{ $materiProgress }}%"></div>
            </div>
            <p class="mt-2 text-sm text-gray-600">Total progres: <strong>{{ $materiProgress }}%</strong></p>
        </div>

        {{-- Pre-Test --}}
        <div class="bg-white p-6 rounded-2xl shadow-sm transition-transform duration-300 hover:-translate-y-1 hover:shadow-lg">
            <h3 class="text-lg font-bold text-gray-800">Progress Pre-Test</h3>
            @php
                $preAttempts = $preTestAttempts ?? (($preTestDone ?? false) ? 1 : 0);
                $preBar = $preAttempts >= 1 ? 100 : 0;
            @endphp
            <div class="flex items-baseline mt-2">
                <span class="text-3xl font-bold text-yellow-600">{{ $preAttempts }}</span>
                <span class="text-lg text-gray-500 ml-1">/ 1 dikerjakan</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2.5 mt-4">
                <div class="bg-yellow-500 h-2.5 rounded-full" style="width: {{ $preBar }}%"></div>
            </div>
            @if(!is_null($preTestScore))
                <p class="mt-2 text-sm text-gray-600">Nilai terakhir: <strong>{{ $preTestScore }}</strong></p>
            @endif
        </div>

        {{-- Post-Test --}}
        <div class="bg-white p-6 rounded-2xl shadow-sm transition-transform duration-300 hover:-translate-y-1 hover:shadow-lg">
            <h3 class="text-lg font-bold text-gray-800">Progress Post-Test</h3>
            @php
                $postAttempts = $postTestAttempts ?? (($postTestDone ?? false) ? 1 : 0);
                $postBar = $postAttempts >= 1 ? 100 : 0;
            @endphp
            <div class="flex items-baseline mt-2">
                <span class="text-3xl font-bold text-green-600">{{ $postAttempts }}</span>
                <span class="text-lg text-gray-500 ml-1">/ 1 dikerjakan</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2.5 mt-4">
                <div class="bg-green-500 h-2.5 rounded-full" style="width: {{ $postBar }}%"></div>
            </div>
            @if(!is_null($postTestScore))
                <p class="mt-2 text-sm text-gray-600">Nilai terakhir: <strong>{{ $postTestScore }}</strong></p>
            @endif
        </div>

        {{-- MONEV --}}
        <div class="bg-white p-6 rounded-2xl shadow-sm transition-transform duration-300 hover:-translate-y-1 hover:shadow-lg">
            <h3 class="text-lg font-bold text-gray-800">Progress MONEV</h3>
            @php
                $monevAttempts = $monevAttempts ?? (($monevDone ?? false) ? 1 : 0);
                $monevBar = $monevAttempts >= 1 ? 100 : 0;
            @endphp
            <div class="flex items-baseline mt-2">
                <span class="text-3xl font-bold text-blue-600">{{ $monevAttempts }}</span>
                <span class="text-lg text-gray-500 ml-1">/ 1 dikerjakan</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2.5 mt-4">
                <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{ $monevBar }}%"></div>
            </div>
            @if(!is_null($monevScore))
                <p class="mt-2 text-sm text-gray-600">Nilai terakhir: <strong>{{ $monevScore }}</strong></p>
            @endif
        </div>
    </div>
</div>

@endsection
