@extends('dashboard.layout.main')

@section('title', 'Home')
@section('page-title', 'Dashboard Home')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <!-- Materi Hari Ini -->
    <div class="bg-white p-6 rounded-xl shadow-md hover:shadow-lg transition card-hover">
        <h3 class="font-semibold text-lg mb-2">Materi Hari Ini</h3>
        <p class="text-gray-600">Silakan akses materi sesuai jadwal hari ini.</p>
        <a href="{{ route('dashboard.materi') }}" 
           class="mt-3 inline-block text-blue-600 hover:underline">
           Lihat Materi
        </a>
    </div>

    <!-- Pre-Test -->
    <div class="bg-white p-6 rounded-xl shadow-md hover:shadow-lg transition card-hover">
        <h3 class="font-semibold text-lg mb-2">Pre-Test</h3>
        <p class="text-gray-600">Cek kesiapanmu sebelum mengikuti materi.</p>
        <a href="{{ route('dashboard.pretest') }}" 
           class="mt-3 inline-block text-blue-600 hover:underline">
           Kerjakan Pre-Test
        </a>
    </div>

    <!-- Post-Test & Feedback -->
    <div class="bg-white p-6 rounded-xl shadow-md hover:shadow-lg transition card-hover">
        <h3 class="font-semibold text-lg mb-2">Post-Test & Feedback</h3>
        <p class="text-gray-600">Evaluasi hasil belajarmu dan berikan feedback.</p>
        <div class="mt-3 space-x-3">
            <a href="{{ route('dashboard.posttest') }}" 
               class="inline-block text-blue-600 hover:underline">
               Post-Test
            </a>
            <a href="{{ route('dashboard.feedback') }}" 
               class="inline-block text-blue-600 hover:underline">
               Feedback
            </a>
        </div>
    </div>
</div>
@endsection
