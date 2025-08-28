{{-- resources/views/dashboard/pretest/index.blade.php --}}
@extends('dashboard.layouts.main')

@section('title', 'Pre-Test')
@section('page-title', 'Pre-Test')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    @forelse($tes as $t)
        <div class="p-6 bg-white rounded-xl shadow-md hover:shadow-xl transition card-hover">
            <h3 class="font-bold text-lg mb-2">{{ $t->judul }}</h3>
            <p class="text-gray-600 mb-4">{{ $t->deskripsi }}</p>
            <p class="text-sm text-gray-500">Bidang: {{ $t->bidang->nama_bidang }}</p>
            <p class="text-sm text-gray-500">Pelatihan: {{ $t->pelatihan->nama_pelatihan }}</p>
            <a href="{{ route('dashboard.pretest.show', $t->id) }}" 
               class="mt-4 inline-block px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
               Kerjakan
            </a>
        </div>
    @empty
        <p class="text-gray-500">Tidak ada pre-test tersedia saat ini.</p>
    @endforelse
</div>
@endsection
