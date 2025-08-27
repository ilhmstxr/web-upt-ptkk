@extends('dashboard.layouts.main')

@section('title', 'Materi')
@section('page-title', 'Daftar Materi Pelatihan')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse($materis ?? [] as $materi)
        <div class="bg-white p-6 rounded-xl shadow-md card-hover fade-in flex flex-col justify-between">
            <div>
                <h3 class="font-bold text-xl mb-2">{{ $materi->judul }}</h3>
                <p class="text-gray-600 mb-4">{{ Str::limit($materi->deskripsi, 120) }}</p>

                @if(isset($materi->kategori))
                    <span class="inline-block px-2 py-1 text-xs font-semibold bg-gray-200 rounded">{{ $materi->kategori }}</span>
                @endif
            </div>
            
            <div class="mt-4">
                @if(Route::has('dashboard.materi.show'))
                    <a href="{{ route('dashboard.materi.show', $materi->id) }}" 
                       class="inline-block px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                       Buka Materi
                    </a>
                @else
                    <span class="text-red-500 font-semibold">Materi belum tersedia.</span>
                @endif
            </div>
        </div>
    @empty
        <p class="text-gray-500 col-span-full text-center">Belum ada materi tersedia saat ini.</p>
    @endforelse
</div>
@endsection
