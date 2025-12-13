@extends('peserta.monev.layout.main')

{{-- Judul halaman diambil dari judul tes/section --}}
@section('title', $section->judul)

@section('content')
    <header class="text-center mb-10">
        <div class="bg-white rounded-xl shadow-md inline-block px-6 py-4 mb-6">
            <h2 class="text-xl font-semibold text-indigo-700">
                <i class="fas fa-poll-h text-indigo-500 mr-2"></i>
                {{-- Judul tes/section --}}
                {{ $section->judul }}
            </h2>
            @if ($section->deskripsi)
                <p class="text-sm text-gray-500 mt-1">{{ $section->deskripsi }}</p>
            @endif
        </div>
    </header>

    @include('peserta.monev.survei.partials.participant_info', ['peserta' => $peserta])

    <div class="bg-blue-50 border border-blue-200 rounded-t-lg p-4 text-sm text-gray-700">
        📌 <em>Silakan isi semua pertanyaan berikut sesuai dengan persepsi Anda.</em>
    </div>

    {{--
        PERUBAHAN UTAMA:
        1. 'action' sekarang mengarah ke route 'survei.store'.
        2. @method('PUT') dihapus karena kita menggunakan metode POST.
        3. Menambahkan hidden input untuk 'peserta_id' dan 'tes_id'.
    --}}
    <form action="{{ route('survei.store') }}" method="POST">
        @csrf

        <input type="hidden" name="peserta_id" value="{{ $peserta->id }}">
        <input type="hidden" name="tes_id" value="{{ $section->id }}">

        <div class="bg-white rounded-b-xl shadow-md overflow-hidden">
            <div class="p-6">
                {{-- Menampilkan pesan error validasi jika ada --}}
                @if ($errors->any())
                    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                        <p class="font-bold">Terjadi Kesalahan</p>
                        <p>Pastikan semua pertanyaan telah diisi sebelum menyimpan.</p>
                    </div>
                @endif
                {{-- Loop untuk setiap GRUP pertanyaan --}}
                @foreach ($groupedQuestions as $group)
                    {{-- 1. Tampilkan Kategori untuk setiap grup --}}
                    <div class="bg-gray-100 p-3 rounded-lg border my-6">
                        <h3 class="text-md font-semibold text-gray-700">
                            {{-- Tampilkan langsung karena sudah berupa string --}}
                            Kategori Penilaian: {{ $group['kategori'] }}
                        </h3>
                    </div>

                    {{-- 2. Loop untuk setiap PERTANYAAN di dalam grup saat ini --}}
                    @foreach ($group['pertanyaan'] as $question)
                        @include('peserta.monev.survei.partials.question_card', [
                            'question' => $question,
                            'loop' => $loop,
                        ])
                    @endforeach
                @endforeach

                {{-- Tombol submit diubah menjadi 'Simpan' atau 'Selesai' --}}
                <div class="flex justify-end mt-8">
                    <button type="submit"
                        class="bg-indigo-600 text-white px-8 py-3 rounded-lg font-semibold hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-200">
                        Simpan Survei <i class="fas fa-check ml-2"></i>
                    </button>
                </div>
            </div>
        </div>
    </form>
@endsection

@push('scripts')
    {{-- Script dipindah ke @push untuk kerapian kode --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Script ini berfungsi untuk memberikan feedback visual saat opsi radio dipilih.
            const ratingContainers = document.querySelectorAll('.rating-options-container');

            ratingContainers.forEach(container => {
                container.addEventListener('click', function(event) {
                    const option = event.target.closest('.rating-option');
                    if (!option) return;

                    const radioInput = option.querySelector('input[type="radio"]');
                    if (!radioInput) return;

                    // Hapus kelas 'selected' dari semua pilihan dalam satu grup pertanyaan
                    container.querySelectorAll('.rating-option').forEach(opt => {
                        opt.classList.remove('selected', 'bg-indigo-100',
                            'border-indigo-400');
                        opt.classList.add('bg-gray-50', 'border-gray-200');
                    });

                    // Tambahkan kelas 'selected' pada pilihan yang diklik
                    option.classList.add('selected', 'bg-indigo-100', 'border-indigo-400');
                    option.classList.remove('bg-gray-50', 'border-gray-200');

                    // Pastikan radio button juga terpilih
                    radioInput.checked = true;
                });
            });
        });
    </script>
@endpush
