@extends('dashboard.layouts.main')

@section('title', 'Home')
@section('page-title', 'Dashboard Home')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-3 gap-6">

    <!-- Materi Hari Ini -->
    <div class="p-6 bg-white rounded-xl shadow-md hover:shadow-xl transition card-hover relative">
        <span class="absolute top-4 right-4 bg-blue-100 text-blue-700 text-xs font-semibold px-2 py-1 rounded-full">Hari Ini</span>
        <h3 class="font-semibold text-xl mb-2">Materi Hari Ini</h3>
        <p class="text-gray-600">Akses materi sesuai jadwal hari ini dan pelajari topik terbaru.</p>
        <a href="{{ route('dashboard.materi') }}" 
           class="mt-4 inline-block px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
           Lihat Materi
        </a>
    </div>

    <!-- Pre-Test -->
    <div class="p-6 bg-white rounded-xl shadow-md hover:shadow-xl transition card-hover relative">
        <span class="absolute top-4 right-4 bg-yellow-100 text-yellow-700 text-xs font-semibold px-2 py-1 rounded-full">Wajib</span>
        <h3 class="font-semibold text-xl mb-2">Pre-Test</h3>
        <p class="text-gray-600">Cek kesiapanmu sebelum mengikuti materi agar hasil maksimal.</p>
        <a href="{{ route('dashboard.pretest.index') }}" 
           class="mt-4 inline-block px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition">
           Kerjakan Pre-Test
        </a>
    </div>

    <!-- Post-Test & Survey -->
    <div class="p-6 bg-white rounded-xl shadow-md hover:shadow-xl transition card-hover relative">
        <span class="absolute top-4 right-4 bg-green-100 text-green-700 text-xs font-semibold px-2 py-1 rounded-full">Wajib</span>
        <h3 class="font-semibold text-xl mb-2">Post-Test</h3>
        <p class="text-gray-600">Evaluasi hasil belajarmu untuk peningkatan materi.</p>
        <div class="mt-4 flex flex-col space-y-2">
            <a href="{{ route('dashboard.posttest.index') }}" 
               class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition text-center">
               Mulai Post-Test
            </a>
        </div>
    </div>

</div>

<!-- Sekilas Statistik -->
<div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
    <div class="p-6 bg-white rounded-xl shadow-md card-hover flex items-center justify-between">
        <div>
            <h4 class="font-bold text-lg">Materi Selesai</h4>
            <p class="text-gray-600 mt-1">5 / 10 materi</p>
        </div>
        <div class="text-blue-600 text-3xl font-bold">📘</div>
    </div>
    <div class="p-6 bg-white rounded-xl shadow-md card-hover flex items-center justify-between">
        <div>
            <h4 class="font-bold text-lg">Pre-Test</h4>
            <p class="text-gray-600 mt-1">2 / 3 sudah dikerjakan</p>
        </div>
        <div class="text-yellow-500 text-3xl font-bold">📝</div>
    </div>
    <div class="p-6 bg-white rounded-xl shadow-md card-hover flex items-center justify-between">
        <div>
            <h4 class="font-bold text-lg">Post-Test</h4>
            <p class="text-gray-600 mt-1">Belum dikerjakan</p>
        </div>
        <div class="text-green-500 text-3xl font-bold">💬</div>
    </div>
</div>
@endsection
