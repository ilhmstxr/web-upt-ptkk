@extends('dashboard.layouts.main')

@section('title', 'Pre-Test')
@section('page-title', 'Pre-Test')

@section('content')
<div class="bg-white p-6 rounded-xl shadow-md fade-in">
    <h2 class="font-bold text-xl mb-4">Pre-Test</h2>
    <p class="text-gray-600 mb-4">Uji kemampuanmu sebelum memulai materi. Jawaban yang kamu berikan akan digunakan sebagai acuan perkembangan belajar.</p>

    @if($pretestAvailable ?? false)
        <a href="{{ route('dashboard.pretest.start') }}" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
            Mulai Pre-Test
        </a>
    @else
        <p class="text-gray-500">Pre-Test belum tersedia saat ini. Silakan cek kembali nanti.</p>
    @endif
</div>
@endsection
