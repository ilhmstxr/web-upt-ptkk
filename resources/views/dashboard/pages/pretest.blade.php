@extends('dashboard.layout.main')
@section('title', 'Pre-Test')
@section('page-title', 'Pre-Test')

@section('content')
<div class="bg-white p-6 rounded-xl shadow-md fade-in">
    <h2 class="font-semibold text-xl mb-4">Pre-Test</h2>
    <p class="text-gray-600 mb-4">Kerjakan soal berikut untuk mengecek pemahaman awal Anda.</p>

    @if(Route::has('dashboard.pretest.start'))
        <a href="{{ route('dashboard.pretest.start') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
            Mulai Pre-Test
        </a>
    @else
        <span class="text-red-500">Route Pre-Test belum tersedia.</span>
    @endif
</div>
@endsection
