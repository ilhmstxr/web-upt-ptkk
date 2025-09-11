@extends('dashboard.layouts.main')

@section('title', 'Home')
@section('page-title', 'Dashboard Home')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

    {{-- ================= BARIS PERTAMA: KARTU AKSI ================= --}}

    {{-- Card Pre-Test --}}
    <div class="bg-white p-6 rounded-2xl shadow-sm flex flex-col justify-between transition hover:-translate-y-1 hover:shadow-lg">
        <div>
            <div class="flex justify-between items-start">
                <h3 class="text-lg font-bold text-gray-800">Pre-Test</h3>
                <span class="bg-yellow-100 text-yellow-700 text-xs font-semibold px-3 py-1 rounded-full">
                    Wajib
                </span>
            </div>
            <p class="text-gray-500 mt-2 mb-4">Cek kesiapanmu sebelum mengikuti materi.</p>
        </div>
        <a href="{{ route('dashboard.pretest.index') }}"
           class="w-full block text-center bg-yellow-400 text-yellow-900 font-semibold py-3 px-6 rounded-lg hover:bg-yellow-500 transition">
            Kerjakan Pre-Test
        </a>
    </div>

    {{-- Card Post-Test --}}
    <div class="bg-white p-6 rounded-2xl shadow-sm flex flex-col justify-between transition hover:-translate-y-1 hover:shadow-lg">
        <div>
            <div class="flex justify-between items-start">
                <h3 class="text-lg font-bold text-gray-800">Post-Test</h3>
                <span class="bg-green-100 text-green-700 text-xs font-semibold px-3 py-1 rounded-full">
                    Wajib
                </span>
            </div>
            <p class="text-gray-500 mt-2 mb-4">Evaluasi hasil belajarmu setelah materi.</p>
        </div>
        <a href="{{ route('dashboard.posttest.index') }}"
           class="w-full block text-center bg-green-500 text-white font-semibold py-3 px-6 rounded-lg hover:bg-green-600 transition">
            Mulai Post-Test
        </a>
    </div>

    {{-- Card MONEV --}}
    <div class="bg-white p-6 rounded-2xl shadow-sm flex flex-col justify-between transition hover:-translate-y-1 hover:shadow-lg">
        <div>
            <div class="flex justify-between items-start">
                <h3 class="text-lg font-bold text-gray-800">MONEV</h3>
                <span class="bg-blue-100 text-blue-700 text-xs font-semibold px-3 py-1 rounded-full">
                    Wajib
                </span>
            </div>
            <p class="text-gray-500 mt-2">Monitoring dan Evaluasi selama pelatihan.</p>
        </div>
        <a href="{{ route('dashboard.survey') }}"
           class="mt-6 block text-center w-full bg-blue-600 text-white font-semibold py-3 px-6 rounded-lg hover:bg-blue-700 transition">
            Mulai Survey
        </a>
    </div>

    {{-- ================= BARIS KEDUA: KARTU PROGRES ================= --}}

    {{-- Progress Pre-Test --}}
    <div class="bg-white p-6 rounded-2xl shadow-sm transition hover:-translate-y-1 hover:shadow-lg">
        <h3 class="text-lg font-bold text-gray-800">Progress Pre-Test</h3>
        <div class="flex items-baseline mt-2">
            <span class="text-3xl font-bold text-yellow-600">{{ $preTestAttempts }}</span>
            <span class="text-lg text-gray-500 ml-1">/ {{ $preTestMax }} dikerjakan</span>
        </div>
        @php
            $prePercent = $preTestMax > 0 ? round(($preTestAttempts / $preTestMax) * 100) : 0;
        @endphp
        <div class="w-full bg-gray-200 rounded-full h-2.5 mt-4">
            <div class="bg-yellow-500 h-2.5 rounded-full" style="width: {{ $prePercent }}%"></div>
        </div>
    </div>

    {{-- Progress Post-Test --}}
    <div class="bg-white p-6 rounded-2xl shadow-sm transition hover:-translate-y-1 hover:shadow-lg">
        <h3 class="text-lg font-bold text-gray-800">Progress Post-Test</h3>
        @php
            $postAttempts = $postTestDone ? 1 : 0;
            $postPercent = $postTestMax > 0 ? round(($postAttempts / $postTestMax) * 100) : 0;
        @endphp
        <div class="flex items-baseline mt-2">
            <span class="text-3xl font-bold text-green-600">{{ $postAttempts }}</span>
            <span class="text-lg text-gray-500 ml-1">/ {{ $postTestMax }} dikerjakan</span>
        </div>
        <div class="w-full bg-gray-200 rounded-full h-2.5 mt-4">
            <div class="bg-green-500 h-2.5 rounded-full" style="width: {{ $postPercent }}%"></div>
        </div>
    </div>

    {{-- Progress MONEV --}}
    <div class="bg-white p-6 rounded-2xl shadow-sm transition hover:-translate-y-1 hover:shadow-lg">
        <h3 class="text-lg font-bold text-gray-800">Progress MONEV</h3>
        @php
            $monevAttempts = $monevDone ? 1 : 0;
            $monevPercent = $monevMax > 0 ? round(($monevAttempts / $monevMax) * 100) : 0;
        @endphp
        <div class="flex items-baseline mt-2">
            <span class="text-3xl font-bold text-blue-600">{{ $monevAttempts }}</span>
            <span class="text-lg text-gray-500 ml-1">/ {{ $monevMax }} dikerjakan</span>
        </div>
        <div class="w-full bg-gray-200 rounded-full h-2.5 mt-4">
            <div class="bg-blue-500 h-2.5 rounded-full" style="width: {{ $monevPercent }}%"></div>
        </div>
    </div>

</div>
@endsection
