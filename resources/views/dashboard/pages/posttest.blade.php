@extends('dashboard.layouts.main')

@section('title', 'Post-Test')
@section('page-title', 'Post-Test')

@section('content')
<div class="bg-white p-6 rounded-xl shadow-md fade-in">
    <h2 class="font-bold text-xl mb-4">Post-Test</h2>
    <p class="text-gray-600 mb-4">Kerjakan post-test setelah menyelesaikan materi. Hasil akan membantu evaluasi pemahamanmu.</p>

    @if($posttestAvailable ?? false)
        <a href="{{ route('dashboard.posttest.start') }}" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
            Mulai Post-Test
        </a>
    @else
        <p class="text-gray-500">Post-Test belum tersedia saat ini. Silakan cek kembali nanti.</p>
    @endif
</div>
@endsection
