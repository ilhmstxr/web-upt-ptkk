{{-- resources/views/dashboard/pages/materi/show.blade.php --}}
@extends('dashboard.layouts.main')

@section('title', $materi->judul ?? 'Materi')
@section('page-title', $materi->judul ?? 'Detail Materi')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-4 gap-6 fade-in">
  <!-- Konten Materi Utama -->
  <div class="lg:col-span-3 bg-white p-6 rounded-xl shadow-lg">
    <div class="flex flex-wrap items-center gap-4 mb-4 pb-4 border-b border-gray-100">
      @if(!empty($materi->kategori))
        <span class="inline-flex items-center px-3 py-1 text-sm font-semibold bg-blue-100 text-blue-800 rounded-full">
          <i class="fas fa-tag mr-2 text-xs"></i>{{ $materi->kategori }}
        </span>
      @endif
      <span class="text-gray-500 text-sm inline-flex items-center">
        <i class="fas fa-clock mr-2 text-xs"></i> Durasi: {{ $materi->durasi ?? 'N/A' }} menit
      </span>
      <span class="text-gray-500 text-sm inline-flex items-center">
        <i class="fas fa-calendar-alt mr-2 text-xs"></i> Diunggah: {{ optional($materi->created_at)->translatedFormat('d F Y') }}
      </span>
    </div>

    <h1 class="font-bold text-3xl mb-6 text-gray-800 leading-tight">{{ $materi->judul }}</h1>

    <div class="prose max-w-full lg:prose-lg text-gray-700">
      {!! $materi->konten !!}
    </div>

    @if(!empty($materi->file_pendukung))
      <div class="mt-8 p-4 bg-yellow-50 border-l-4 border-yellow-500 text-yellow-800 rounded-lg">
        <p class="font-semibold mb-2">File Pendukung Tersedia</p>
        <a href="{{ asset('storage/' . ltrim($materi->file_pendukung, '/')) }}" target="_blank" class="text-blue-600 hover:text-blue-800 hover:underline transition">
          <i class="fas fa-download mr-1"></i> Unduh Lampiran
        </a>
      </div>
    @endif

    <div class="mt-10 pt-6 border-t border-gray-200 flex justify-end">
      @if(isset($materiProgress) && $materiProgress->is_completed)
        <button class="px-6 py-3 bg-green-500 text-white font-bold rounded-lg" disabled>Materi Sudah Selesai</button>
      @else
        <form action="{{ route('dashboard.materi.complete', $materi->slug ?? $materi->id) }}" method="POST">
          @csrf
          <button type="submit" class="px-6 py-3 bg-blue-600 text-white font-bold rounded-lg">Tandai Selesai</button>
        </form>
      @endif
    </div>
  </div>

  <!-- Sidebar -->
  <aside class="lg:col-span-1 bg-white p-4 rounded-xl shadow-lg hidden lg:block h-fit sticky top-6">
    <h3 class="font-extrabold text-xl mb-4 text-gray-800 border-b pb-2">Materi Terkait</h3>
    <ul class="space-y-3">
      @forelse($relatedMateris ?? [] as $related)
        <li class="{{ ($related->id === $materi->id) ? 'bg-blue-50 border-l-4 border-blue-500 font-semibold' : '' }}">
          <a href="{{ route('dashboard.materi.show', $related->slug ?? $related->id) }}" class="text-blue-600 hover:text-blue-800 block text-sm">
            {{ $related->judul }}
          </a>
        </li>
      @empty
        <li class="text-gray-500 text-sm">Tidak ada materi terkait lainnya.</li>
      @endforelse
    </ul>
  </aside>
</div>
@endsection