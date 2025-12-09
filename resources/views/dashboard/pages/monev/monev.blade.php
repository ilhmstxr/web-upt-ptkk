@extends('dashboard.layouts.main')

@section('title', 'monev')
@section('page-title', 'monev')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    @forelse($tes as $t)
        <div class="p-6 bg-white rounded-xl shadow-md hover:shadow-xl transition card-hover">
            <h3 class="font-bold text-lg mb-2">{{ $t->judul }}</h3>
            <p class="text-gray-600 mb-4">{{ $t->deskripsi }}</p>
            <p class="text-sm text-gray-500">
                Kompetensi: {{ $t->kompetensi->nama_kompetensi ?? '-' }}
            </p>
            <p class="text-sm text-gray-500">
                Pelatihan: {{ $t->pelatihan->nama_pelatihan ?? '-' }}
            </p>
            <a href="{{ route('dashboard.posttest.start', $t->id) }}" 
               class="mt-4 inline-block px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition">
               Kerjakan {{ ucfirst($t->sub_tipe) ?? 'Tes' }}
            </a>
        </div>
    @empty
        <p class="text-gray-500">Tidak ada data tes tersedia saat ini.</p>
    @endforelse
</div>
@endsection