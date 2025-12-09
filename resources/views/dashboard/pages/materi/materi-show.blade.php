{{-- resources/views/dashboard/pages/materi/show.blade.php --}}
@extends('dashboard.layouts.main')

@section('title', $materi->judul ?? 'Materi')
@section('page-title', $materi->judul ?? 'Detail Materi')

@section('content')
@php
    $judul    = $materi->judul ?? 'Materi';
    $tipe     = $materi->tipe ?? 'teks';
    $kategori = $materi->kategori ?? null;
    $durasi   = $materi->estimasi_menit ?? null;

    // aman untuk dummy (created_at bisa null / string)
    $tanggal = null;
    if (!empty($materi->created_at) && $materi->created_at instanceof \Carbon\CarbonInterface) {
        $tanggal = $materi->created_at->translatedFormat('d F Y');
    }

    $isDone = isset($materiProgress) && ($materiProgress->is_completed ?? false);

    // link materi utama (slug optional)
    $materiIdOrSlug = $materi->slug ?? $materi->id;

    // related materi aman jadi collection
    $relatedMateris = collect($relatedMateris ?? []);
@endphp

{{-- NOTIF SUCCESS / ERROR (tetap simple, tidak ubah layout utama) --}}
@if(session('success'))
    <div class="mb-4 bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-lg text-sm">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="mb-4 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg text-sm">
        {{ session('error') }}
    </div>
@endif

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

        {{-- COMPLETE BUTTON (layout sama persis) --}}
        <div class="mt-10 pt-6 border-t border-gray-200 flex justify-end">
            @if($isDone)
                {{-- sudah selesai -> tombol merah Review Materi --}}
                <a href="{{ route('dashboard.materi.show', $materiIdOrSlug) }}"
                   class="px-6 py-3 bg-red-600 hover:bg-red-700 text-white font-bold rounded-lg shadow">
                    Review Materi
                </a>
            @else
                {{-- belum selesai -> tombol biru Tandai Selesai --}}
                <form action="{{ route('dashboard.materi.complete', $materiIdOrSlug) }}" method="POST">
                    @csrf
                    <button type="submit"
                            class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg shadow">
                        Tandai Selesai
                    </button>
                </form>
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

                    // status selesai:
                    // - materi aktif ikut $isDone
                    // - selain aktif pakai is_done dari controller
                    $relatedDone = $isActive ? $isDone : (bool) data_get($related, 'is_done', false);

                    // nomor urut: pakai urutan dari DB kalau ada, fallback iteration
                    $nomor = data_get($related, 'urutan', $loop->iteration);
                @endphp

                <li class="
                    {{ $isActive ? 'bg-blue-50 border-l-4 border-blue-500 font-semibold' : '' }}
                    {{ (!$isActive && $relatedDone) ? 'bg-emerald-50 border-l-4 border-emerald-500' : '' }}
                ">
                    <a href="{{ route('dashboard.materi.show', $relatedIdOrSlug) }}"
                    class="
                            flex items-center justify-between gap-2
                            text-sm px-2 py-2 rounded
                            {{ $relatedDone ? 'text-emerald-700 hover:text-emerald-800 font-semibold' : 'text-blue-600 hover:text-blue-800' }}
                    ">

                        <div class="flex items-center gap-2 min-w-0">
                            {{-- sebelum selesai: nomor, sesudah selesai: checklist --}}
                            @if($relatedDone)
                                <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-emerald-500 text-white text-xs font-bold">
                                    ✓
                                </span>
                            @else
                                <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-slate-100 text-slate-700 text-xs font-bold">
                                    {{ $nomor }}
                                </span>
                            @endif

                            <span class="truncate">
                                {{ $related->judul ?? 'Materi' }}
                            </span>
                        </div>

                        @if($relatedDone)
                            <span class="text-[10px] bg-emerald-100 text-emerald-700 px-2 py-[2px] rounded-full">
                                Selesai
                            </span>
                        @endif
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
