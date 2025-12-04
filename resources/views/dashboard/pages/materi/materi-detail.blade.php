@extends('dashboard.layouts.main')

@section('title', $materi->judul)
@section('page-title', $materi->judul)

@section('content')

<div class="grid grid-cols-1 lg:grid-cols-4 gap-6 fade-in">

<!-- Konten Materi Utama -->
<div class="lg:col-span-3 bg-white p-6 rounded-xl shadow-lg">

    <div class="flex flex-wrap items-center gap-4 mb-4 pb-4 border-b border-gray-100">
        @if(isset($materi->kategori))
            <span class="inline-flex items-center px-3 py-1 text-sm font-semibold bg-blue-100 text-blue-800 rounded-full">
                <i class="fas fa-tag mr-2 text-xs"></i>
                {{ $materi->kategori }}
            </span>
        @endif
        <span class="text-gray-500 text-sm inline-flex items-center">
            <i class="fas fa-clock mr-2 text-xs"></i>
            Durasi: {{ $materi->durasi ?? 'N/A' }} menit
        </span>
        <span class="text-gray-500 text-sm inline-flex items-center">
            <i class="fas fa-calendar-alt mr-2 text-xs"></i>
            Diunggah: {{ $materi->created_at->translatedFormat('d F Y') }}
        </span>
    </div>

    <h1 class="font-bold text-3xl mb-6 text-gray-800 leading-tight">{{ $materi->judul }}</h1>
    
    <div class="prose max-w-full lg:prose-lg text-gray-700">
        {!! $materi->konten !!}
    </div>

    @if(isset($materi->file_pendukung))
        <div class="mt-8 p-4 bg-yellow-50 border-l-4 border-yellow-500 text-yellow-800 rounded-lg">
            <p class="font-semibold mb-2">File Pendukung Tersedia</p>
            <a href="{{ asset('storage/' . $materi->file_pendukung) }}" target="_blank" class="text-blue-600 hover:text-blue-800 hover:underline transition">
                <i class="fas fa-download mr-1"></i> Unduh Lampiran
            </a>
        </div>
    @endif

    <!-- Aksi Selesai Materi -->
    <div class="mt-10 pt-6 border-t border-gray-200 flex justify-end">
        @if(isset($materiProgress) && $materiProgress->is_completed)
            <button class="px-6 py-3 bg-green-500 text-white font-bold rounded-lg cursor-not-allowed shadow-lg" disabled>
                <i class="fas fa-check-circle mr-2"></i> Materi Sudah Selesai
            </button>
        @else
            {{-- Form untuk menandai selesai. Pastikan route 'materi.complete' tersedia --}}
            <form action="{{ route('dashboard.materi.complete', $materi->slug ?? $materi->id) }}" method="POST">
                @csrf
                <button type="submit" class="px-6 py-3 bg-blue-600 text-white font-bold rounded-lg hover:bg-blue-700 transition shadow-lg hover:shadow-xl focus:outline-none focus:ring-4 focus:ring-blue-500 focus:ring-opacity-50">
                    <i class="fas fa-graduation-cap mr-2"></i> Tandai Selesai
                </button>
            </form>
        @endif
    </div>

    <!-- Navigasi Materi -->
    <div class="mt-8 flex justify-between pt-4 border-t border-gray-100">
        @if(isset($prevMateri))
            <a href="{{ route('dashboard.materi.show', $prevMateri->slug ?? $prevMateri->id) }}" class="flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition group">
                <i class="fas fa-arrow-left mr-2 group-hover:text-blue-500"></i>
                <div class="flex flex-col text-sm">
                    <span class="text-xs text-gray-500">Materi Sebelumnya</span>
                    <span class="font-semibold">{{ Str::limit($prevMateri->judul, 30) }}</span>
                </div>
            </a>
        @else
            <span></span>
        @endif

        @if(isset($nextMateri))
            <a href="{{ route('dashboard.materi.show', $nextMateri->slug ?? $nextMateri->id) }}" class="flex items-center px-4 py-2 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition group ml-auto">
                <div class="flex flex-col text-sm text-right">
                    <span class="text-xs text-gray-500">Materi Selanjutnya</span>
                    <span class="font-semibold">{{ Str::limit($nextMateri->judul, 30) }}</span>
                </div>
                <i class="fas fa-arrow-right ml-2 group-hover:text-blue-500"></i>
            </a>
        @endif
    </div>
</div>

<!-- Sidebar Materi Terkait -->
<aside class="lg:col-span-1 bg-white p-4 rounded-xl shadow-lg hidden lg:block h-fit sticky top-6">
    <h3 class="font-extrabold text-xl mb-4 text-gray-800 border-b pb-2">Materi Terkait</h3>
    <ul class="space-y-3">
        @foreach($relatedMateris ?? [] as $related)
            <li class="hover:bg-gray-50 p-2 rounded-lg transition {{ ($related->id === $materi->id) ? 'bg-blue-50 border-l-4 border-blue-500 font-semibold' : '' }}">
                <a href="{{ route('dashboard.materi.show', $related->slug ?? $related->id) }}" class="text-blue-600 hover:text-blue-800 block text-sm">
                    {{ $related->judul }}
                </a>
            </li>
        @endforeach
        @if(empty($relatedMateris))
            <li class="text-gray-500 text-sm p-2">Tidak ada materi terkait lainnya.</li>
        @endif
    </ul>
</aside>


</div>
@endsection