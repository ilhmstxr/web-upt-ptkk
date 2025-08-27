@extends('dashboard.layout.main')

@section('title', 'Post-Test')
@section('page-title', 'Post-Test')

@section('content')
<div class="bg-white p-6 rounded-xl shadow-md">
    <h2 class="font-semibold text-xl mb-4">Daftar Soal Post Test</h2>
    <p class="text-gray-600 mb-4">Silakan klik tombol di bawah untuk memulai post test.</p>

    <form action="{{ route('dashboard.posttest.start') }}" method="POST">
        @csrf
        <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg shadow">
            Mulai Post Test
        </button>
    </form>
</div>
@endsection









