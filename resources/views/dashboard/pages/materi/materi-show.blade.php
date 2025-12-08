{{-- resources/views/dashboard/pages/materi/show.blade.php --}}
@extends('dashboard.layouts.main')

@section('title', $materi->judul ?? 'Materi')
@section('page-title', $materi->judul ?? 'Detail Materi')

@section('content')
@php
    $judul   = $materi->judul ?? 'Materi';
    $tipe    = $materi->tipe ?? 'teks';
    $kategori = $materi->kategori ?? null;
    $durasi  = $materi->estimasi_menit ?? null;
    $tanggal = optional($materi->created_at)->translatedFormat('d F Y');

    $isDone = isset($materiProgress) && ($materiProgress->is_completed ?? false);

    // link materi utama
    $materiIdOrSlug = $materi->slug ?? $materi->id;

    // related materi aman jadi collection
    $relatedMateris = collect($relatedMateris ?? []);
@endphp

<div class="grid grid-cols-1 lg:grid-cols-4 gap-6">

    {{-- MAIN CONTENT --}}
    <div class="lg:col-span-3 bg-white p-6 rounded-xl shadow-lg">

        {{-- META --}}
        <div class="flex flex-wrap items-center gap-4 mb-4 pb-4 border-b border-gray-100">
            @if(!empty($kategori))
                <span class="inline-flex items-center px-3 py-1 text-sm font-semibold bg-blue-100 text-blue-800 rounded-full">
                    {{ $kategori }}
                </span>
            @endif

            <span class="text-gray-500 text-sm inline-flex items-center">
                Durasi: {{ $durasi ? $durasi.' menit' : 'N/A' }}
            </span>

            <span class="text-gray-500 text-sm inline-flex items-center">
                Diunggah: {{ $tanggal ?: '—' }}
            </span>
        </div>

        {{-- TITLE --}}
        <h1 class="font-bold text-3xl mb-6 text-gray-800 leading-tight">
            {{ $judul }}
        </h1>

        {{-- BODY --}}
        <div class="prose max-w-full lg:prose-lg text-gray-700">

            {{-- TEKS --}}
            @if($tipe === 'teks')
                @if(!empty($materi->teks))
                    {!! $materi->teks !!}
                @elseif(!empty($materi->deskripsi))
                    {!! $materi->deskripsi !!}
                @else
                    <div class="text-sm text-gray-500 italic">
                        Konten materi belum tersedia.
                    </div>
                @endif

            {{-- VIDEO --}}
            @elseif($tipe === 'video')
                @if(!empty($materi->video_url))
                    {{-- tanpa aspect-w plugin, pakai wrapper biasa --}}
                    <div class="w-full overflow-hidden rounded-lg bg-black mb-6" style="aspect-ratio:16/9;">
                        <iframe
                            src="{{ $materi->video_url }}"
                            class="w-full h-full"
                            frameborder="0"
                            allowfullscreen>
                        </iframe>
                    </div>
                @else
                    <div class="text-sm text-gray-500 italic">
                        URL video belum diisi.
                    </div>
                @endif

            {{-- FILE --}}
            @elseif($tipe === 'file')
                @if(!empty($materi->file_path))
                    <div class="mb-6">
                        <a href="{{ asset('storage/' . ltrim($materi->file_path, '/')) }}"
                           target="_blank"
                           class="text-blue-600 hover:underline font-semibold">
                            Unduh Materi (PDF / PPT / DOC)
                        </a>
                    </div>
                @else
                    <div class="text-sm text-gray-500 italic">
                        File materi belum diunggah.
                    </div>
                @endif

            {{-- LINK --}}
            @elseif($tipe === 'link')
                @if(!empty($materi->link_url))
                    <div class="mb-6">
                        <a href="{{ $materi->link_url }}"
                           target="_blank"
                           rel="noopener"
                           class="text-blue-600 hover:underline font-semibold">
                            Buka Link Materi
                        </a>
                    </div>
                @else
                    <div class="text-sm text-gray-500 italic">
                        Link materi belum diisi.
                    </div>
                @endif

            {{-- UNKNOWN TYPE --}}
            @else
                <div class="text-sm text-gray-500 italic">
                    Tipe materi tidak dikenali atau belum diatur.
                </div>
            @endif
        </div>

        {{-- COMPLETE BUTTON --}}
        <div class="mt-10 pt-6 border-t border-gray-200 flex justify-end">
            @if($isDone)
                <button class="px-6 py-3 bg-green-500 text-white font-bold rounded-lg cursor-not-allowed" disabled>
                    Materi Sudah Selesai
                </button>
            @else
                @if(\Illuminate\Support\Facades\Route::has('dashboard.materi.complete'))
                    <form action="{{ route('dashboard.materi.complete', $materiIdOrSlug) }}" method="POST">
                        @csrf
                        <button type="submit" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg">
                            Tandai Selesai
                        </button>
                    </form>
                @else
                    <div class="text-sm text-gray-500 italic">
                        Tombol selesai belum aktif (route belum dibuat).
                    </div>
                @endif
            @endif
        </div>
    </div>

    {{-- SIDEBAR RELATED --}}
    <aside class="lg:col-span-1 bg-white p-4 rounded-xl shadow-lg hidden lg:block h-fit sticky top-6">
        <h3 class="font-extrabold text-xl mb-4 text-gray-800 border-b pb-2">
            Materi Terkait
        </h3>

        <ul class="space-y-3">
            @forelse($relatedMateris as $related)
                @php
                    $relatedIdOrSlug = $related->slug ?? $related->id;
                    $isActive = ($related->id ?? null) === ($materi->id ?? null);
                @endphp

                <li class="{{ $isActive ? 'bg-blue-50 border-l-4 border-blue-500 font-semibold' : '' }}">
                    <a href="{{ route('dashboard.materi.show', $relatedIdOrSlug) }}"
                       class="text-blue-600 hover:text-blue-800 block text-sm px-2 py-1 rounded">
                        {{ $related->judul ?? 'Materi' }}
                    </a>
                </li>
            @empty
                <li class="text-gray-500 text-sm">
                    Tidak ada materi terkait lainnya.
                </li>
            @endforelse
        </ul>
    </aside>
</div>
@endsection
