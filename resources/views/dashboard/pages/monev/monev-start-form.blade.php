@extends('dashboard.layouts.main')

@section('title', 'Mulai Post-Test')
@section('page-title', 'Mulai Post-Test: ' . ($tes->judul ?? ''))

@section('content')
<div class="max-w-md mx-auto bg-white p-6 rounded-lg shadow">
    <h2 class="text-lg font-bold mb-4">Tata Tertib Post-Test</h2>

    {{-- Notifikasi error --}}
    @if(session('error'))
        <div class="mb-3 p-2 bg-red-100 text-red-700 rounded">{{ session('error') }}</div>
    @endif

    <form action="{{ route('dashboard.posttest.begin', $tes->id) }}" method="POST">
        @csrf

        {{-- Hidden peserta_survei_id dari session --}}
        <input type="hidden" name="peserta_survei_id" value="{{ session('pesertaSurvei_id') }}">

        {{-- Tampilkan tatib --}}
        <div class="mb-4 text-sm text-gray-700 leading-relaxed">
            <ol class="list-decimal list-inside space-y-2">
                <li>Kerjakan soal dengan jujur dan tanpa bantuan orang lain.</li>
                <li>Waktu pengerjaan sudah ditentukan, pastikan fokus.</li>
                <li>Jangan keluar dari halaman ujian sebelum selesai.</li>
                <li>Hasil tes akan otomatis tersimpan setelah waktu habis atau tombol submit ditekan.</li>
            </ol>
        </div>

        <div class="flex justify-end gap-2">
            <a href="{{ route('dashboard.posttest.index') }}" class="px-3 py-2 bg-gray-200 rounded">Batal</a>
            <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded">Mulai Post-Test</button>
        </div>
    </form>
</div>
@endsection