@extends('dashboard.layouts.main')

@section('title', 'Post-Test')
@section('page-title', 'Post-Test')

@section('content')
<div class="bg-white p-6 rounded-xl shadow-md fade-in">
    <h2 class="font-bold text-xl mb-6">Post-Test: Soal {{ $currentQuestion ?? 1 }}</h2>

    @if(isset($question))
        <form action="{{ route('dashboard.posttest.submit') }}" method="POST">
            @csrf
            <p class="text-gray-700 mb-4">{{ $question->nomor }}. {{ $question->question }}</p>
            <div class="space-y-2 mb-4">
                @foreach(['a','b','c','d'] as $option)
                    <label class="block p-2 border rounded hover:bg-gray-100 cursor-pointer">
                        <input type="radio" name="answer" value="{{ $option }}" class="mr-2">
                        {{ $question->{'option_'. $option} }}
                    </label>
                @endforeach
            </div>
            <input type="hidden" name="question_id" value="{{ $question->id }}">
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                Kirim Jawaban
            </button>
        </form>
    @else
        <p class="text-gray-500">Semua soal telah selesai.</p>
        <a href="{{ route('dashboard.posttest.result') }}" class="mt-4 inline-block px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition">
            Lihat Hasil
        </a>
    @endif
</div>
@endsection
