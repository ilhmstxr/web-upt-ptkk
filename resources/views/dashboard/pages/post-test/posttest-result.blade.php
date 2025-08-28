{{-- resources/views/dashboard/posttest/result.blade.php --}}
@extends('dashboard.layouts.main')

@section('title', 'Hasil Post-Test')
@section('page-title', 'Hasil Post-Test')

@section('content')
<div class="bg-white p-6 rounded-xl shadow-md fade-in">
    <h2 class="font-bold text-xl mb-4">Hasil Post-Test</h2>

    @if(!empty($percobaan))
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
            <div class="p-4 bg-gray-50 rounded-lg shadow">
                <h3 class="font-semibold text-lg">Skor</h3>
                <p class="text-gray-700 text-xl font-bold">
                    {{ $percobaan->skor ?? 0 }}
                </p>
            </div>
            <div class="p-4 bg-gray-50 rounded-lg shadow">
                <h3 class="font-semibold text-lg">Status</h3>
                <p class="text-gray-700">
                    {{ ($percobaan->skor ?? 0) >= 75 ? 'Lulus' : 'Belum Lulus' }}
                </p>
            </div>
        </div>

        <a href="{{ route('dashboard.home') }}" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition">
            Kembali ke Dashboard
        </a>
    @else
        <p class="text-gray-500">Hasil percobaan tidak ditemukan.</p>
        <a href="{{ route('dashboard.home') }}" class="mt-4 inline-block px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition">
            Kembali ke Dashboard
        </a>
    @endif
</div>
@endsection
