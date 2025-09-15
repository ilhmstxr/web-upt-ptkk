@extends('dashboard.layouts.main')

@section('title', 'Mulai Post-Test')
@section('page-title', 'Mulai Post-Test: ' . ($tes->judul ?? ''))

@section('content')
<div class="max-w-md mx-auto bg-white p-6 rounded-lg shadow">
    <h2 class="text-lg font-bold mb-4">Informasi Peserta</h2>

    {{-- Tampilkan nama user & status survey --}}
    <div class="mb-4">
        <p class="text-gray-700"><strong>Nama:</strong> {{ $user->name ?? '-' }}</p>
        <p class="text-gray-700">
            <strong>Status Survey:</strong>
            @if($surveyStatus === 'done')
                <span class="text-green-600">Sudah Dikerjakan</span>
            @else
                <span class="text-red-600">Belum Dikerjakan</span>
            @endif
        </p>
    </div>

    @if(session('error'))
        <div class="mb-3 p-2 bg-red-100 text-red-700 rounded">{{ session('error') }}</div>
    @endif

    <form action="{{ route('dashboard.posttest.begin', $tes->id) }}" method="POST">
        @csrf

        {{-- peserta_id otomatis --}}
        <input type="hidden" name="peserta_id" value="{{ $user->id }}">

        {{-- Instansi opsional --}}
        <div class="mb-3">
            <label class="block text-sm font-medium mb-1">Instansi (opsional)</label>
            <input type="text" name="instansi" value="{{ old('instansi', $user->instansi ?? '') }}"
                   class="w-full p-2 border rounded" />
        </div>

        <div class="flex justify-end gap-2">
            <a href="{{ route('dashboard.posttest.index') }}" class="px-3 py-2 bg-gray-200 rounded">Batal</a>
            <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded">Mulai Post-Test</button>
        </div>
    </form>
</div>
@endsection
