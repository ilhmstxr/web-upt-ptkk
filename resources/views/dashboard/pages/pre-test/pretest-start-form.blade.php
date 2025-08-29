@extends('dashboard.layouts.main')

@section('title', 'Mulai Pre-Test')
@section('page-title', 'Mulai Pre-Test: ' . ($tes->judul ?? ''))

@section('content')
<div class="max-w-md mx-auto bg-white p-6 rounded-lg shadow">
    <h2 class="text-lg font-bold mb-4">Pilih Peserta untuk Memulai Pre-Test</h2>

    @if(session('error'))
        <div class="mb-3 p-2 bg-red-100 text-red-700 rounded">{{ session('error') }}</div>
    @endif

    <form action="{{ route('dashboard.pretest.begin', $tes->id) }}" method="POST">
        @csrf

        {{-- Pilih nama peserta --}}
        <div class="mb-3">
            <label class="block text-sm font-medium mb-1">Nama Peserta</label>
            <select name="peserta_id" required class="w-full p-2 border rounded">
                <option value="">-- Pilih Peserta --</option>
                @foreach($pesertas as $peserta)
                    <option value="{{ $peserta->id }}" {{ old('peserta_id') == $peserta->id ? 'selected' : '' }}>
                        {{ $peserta->nama }} - {{ $peserta->instansi->nama ?? '' }}
                    </option>
                @endforeach
            </select>
            @error('peserta_id') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        {{-- Instansi (opsional, jika mau input manual) --}}
        <div class="mb-3">
            <label class="block text-sm font-medium mb-1">Instansi (opsional)</label>
            <input type="text" name="instansi" value="{{ old('instansi') }}"
                   class="w-full p-2 border rounded" />
        </div>

        <div class="flex justify-end gap-2">
            <a href="{{ route('dashboard.pretest.index') }}" class="px-3 py-2 bg-gray-200 rounded">Batal</a>
            <button type="submit" class="px-4 py-2 bg-yellow-500 text-white rounded">Mulai Pre-Test</button>
        </div>
    </form>
</div>
@endsection
