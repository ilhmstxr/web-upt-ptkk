@extends('dashboard.layouts.main')

@section('title', 'Pre-Test')
@section('page-title', 'Pre-Test: Soal {{ $currentQuestion ?? 1 }}')

@section('content')
<div class="bg-white p-6 rounded-xl shadow-md fade-in">
    @if(isset($pertanyaan))
        <form action="{{ route('dashboard.pretest.submit', $percobaan->id) }}" method="POST">
            @csrf
            <p class="text-gray-700 mb-4">
                {{ $pertanyaan->nomor }}. {{ $pertanyaan->teks_pertanyaan }}
            </p>

            @if($pertanyaan->gambar)
                <img src="{{ asset('storage/'.$pertanyaan->gambar) }}" class="mb-4 rounded shadow">
            @endif

            <div class="space-y-2 mb-4">
                @foreach($pertanyaan->opsiJawaban as $opsi)
                    <label class="block p-2 border rounded hover:bg-gray-100 cursor-pointer">
                        <input type="radio" name="jawaban[{{ $pertanyaan->id }}]" value="{{ $opsi->id }}" class="mr-2">
                        @if($opsi->gambar)
                            <img src="{{ asset('storage/'.$opsi->gambar) }}" class="inline-block w-12 h-12 mr-2 rounded">
                        @endif
                        {{ $opsi->teks_opsi }}
                    </label>
                @endforeach
            </div>

            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                Kirim Jawaban
            </button>
        </form>
    @else
        <p class="text-gray-500">Semua soal telah selesai.</p>
        <a href="{{ route('dashboard.pretest.result', $percobaan->id) }}" 
           class="mt-4 inline-block px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition">
           Lihat Hasil
        </a>
    @endif
</div>
@endsection
