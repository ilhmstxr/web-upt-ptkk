@extends('dashboard.layout.main')
@section('title', 'Materi')
@section('page-title', 'Materi Pelatihan')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    @forelse($materis ?? [] as $materi)
    <div class="bg-white p-6 rounded-xl shadow-md card-hover fade-in">
        <h3 class="font-semibold text-lg mb-2">{{ $materi->judul }}</h3>
        <p class="text-gray-600 mb-2">{{ $materi->deskripsi }}</p>
        @if(Route::has('dashboard.materi.show'))
            <a href="{{ route('dashboard.materi.show', $materi->id) }}" class="text-blue-600 hover:underline">Buka Materi</a>
        @else
            <span class="text-red-500">Route materi belum tersedia.</span>
        @endif
    </div>
    @empty
    <p class="text-gray-500">Belum ada materi tersedia.</p>
    @endforelse
</div>
@endsection
