@extends('dashboard.layouts.main')

@section('title', $materi->judul)
@section('page-title', $materi->judul)

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-4 gap-6 fade-in">
    
    <!-- Konten Materi -->
    <div class="lg:col-span-3 bg-white p-6 rounded-xl shadow-md">
        <div class="mb-4 flex flex-col md:flex-row md:justify-between md:items-center">
            <div>
                @if(isset($materi->kategori))
                    <span class="inline-block px-3 py-1 text-sm font-semibold bg-gray-200 rounded">{{ $materi->kategori }}</span>
                @endif
                <span class="text-gray-500 text-sm ml-2">{{ $materi->created_at->format('d M Y') }}</span>
            </div>
            <div class="mt-2 md:mt-0">
                @if(isset($materi->durasi))
                    <span class="text-gray-500 text-sm">Durasi: {{ $materi->durasi }} menit</span>
                @endif
            </div>
        </div>

        <h1 class="font-bold text-2xl mb-6">{{ $materi->judul }}</h1>
        
        <div class="prose max-w-full">
            {!! $materi->konten !!}
        </div>

        <!-- Navigasi Materi -->
        <div class="mt-8 flex justify-between">
            @if(isset($prevMateri))
                <a href="{{ route('dashboard.materi.show', $prevMateri->id) }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300 transition">
                    ← {{ Str::limit($prevMateri->judul, 30) }}
                </a>
            @else
                <span></span>
            @endif

            @if(isset($nextMateri))
                <a href="{{ route('dashboard.materi.show', $nextMateri->id) }}" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                    {{ Str::limit($nextMateri->judul, 30) }} →
                </a>
            @endif
        </div>
    </div>

    <!-- Sidebar Materi Terkait -->
    <aside class="bg-white p-4 rounded-xl shadow-md hidden lg:block">
        <h3 class="font-semibold text-lg mb-4">Materi Terkait</h3>
        <ul class="space-y-2">
            @foreach($relatedMateris ?? [] as $related)
                <li>
                    <a href="{{ route('dashboard.materi.show', $related->id) }}" class="text-blue-600 hover:underline">
                        {{ $related->judul }}
                    </a>
                </li>
            @endforeach
            @if(empty($relatedMateris))
                <li class="text-gray-500 text-sm">Tidak ada materi terkait.</li>
            @endif
        </ul>
    </aside>

</div>
@endsection
