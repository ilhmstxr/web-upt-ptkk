@extends('peserta.monev.layout.main')

@section('title', 'Pendaftaran MONEV Kegiatan Vokasi')

@section('content')
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
        <div class="p-8">
            <div class="flex items-start gap-4">
                <div
                    class="h-14 w-14 rounded-full bg-gradient-to-br from-indigo-500 to-purple-500 text-white flex items-center justify-center text-2xl">
                    <i class="fa-solid fa-check"></i>
                </div>
                <div>
                    <h1 class="text-2xl sm:text-3xl font-extrabold text-indigo-800 leading-snug">
                        Terima kasih! Pengisian survei Anda telah selesai.
                    </h1>
                    <p class="text-gray-600 mt-2">
                        Masukan Anda sangat berharga untuk peningkatan kualitas pelatihan kami.
                    </p>
                </div>
            </div>
            <div class="mt-8 flex flex-col sm:flex-row gap-3">
                {{-- <a href="{{ route('survey.index') }}" --}}
                <a href="{{ route('dashboard.home') }}"
                    class="inline-flex items-center justify-center px-5 py-3 rounded-lg bg-indigo-600 text-white hover:bg-indigo-700 transition">
                    <i class="fa-solid fa-house mr-2"></i>Kembali ke Halaman Awal
                </a>
            </div>
        </div>
    </div>
@endsection
