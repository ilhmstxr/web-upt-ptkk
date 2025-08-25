@extends('dashboard.layout.main')
@section('title', 'Post-Test')
@section('page-title', 'Post-Test')

@section('content')
<div class="bg-white p-6 rounded-xl shadow-md fade-in">
    <h2 class="font-semibold text-xl mb-4">Post-Test</h2>
    <p class="text-gray-600 mb-4">Kerjakan soal untuk mengevaluasi hasil belajar Anda.</p>

    @if(Route::has('dashboard.posttest.start'))
        <a href="{{ route('dashboard.posttest.start') }}" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
            Mulai Post-Test
        </a>
    @else
        <span class="text-red-500">Route Post-Test belum tersedia.</span>
    @endif
</div>
@endsection
