@extends('peserta.monev.layout.main')

@section('title', $section->title)

@section('content')
    <header class="text-center mb-10">
        <div class="bg-white rounded-xl shadow-md inline-block px-6 py-4 mb-6">
            <h2 class="text-xl font-semibold text-indigo-700">
                <i class="fas fa-graduation-cap text-indigo-500 mr-2"></i>
                {{ $section->order }}. {{ $section->title }}
            </h2>
        </div>
    </header>

    @include('peserta.monev.survey.partials.participant_info', ['peserta' => $peserta])

    <div class="bg-blue-50 border border-blue-200 rounded-t-lg p-4 text-sm text-gray-700">
        📌 <em>Silakan pilih salah satu jawaban yang paling sesuai dengan persepsi Anda.</em>
    </div>

    <form action="{{ route('survey.update', ['peserta' => $peserta->id, 'order' => $section->order]) }}" method="POST">
        @method('PUT')
        @csrf
        <div class="bg-white rounded-b-xl shadow-md overflow-hidden">
            <div class="p-6 border-b border-gray-200">
                <div class="grid grid-cols-5 gap-4 text-center">
                    <div>
                        <div class="text-3xl mb-1">🤩</div>
                        <p class="text-xs font-medium">Sangat Memuaskan</p>
                    </div>
                    <div>
                        <div class="text-3xl mb-1">😊</div>
                        <p class="text-xs font-medium">Memuaskan</p>
                    </div>
                    <div>
                        <div class="text-3xl mb-1">😐</div>
                        <p class="text-xs font-medium">Cukup Memuaskan</p>
                    </div>
                    <div>
                        <div class="text-3xl mb-1">😟</div>
                        <p class="text-xs font-medium">Tidak Memuaskan</p>
                    </div>
                    <div>
                        <div class="text-3xl mb-1">😠</div>
                        <p class="text-xs font-medium">Sangat Tdk Memuaskan</p>
                    </div>
                </div>
            </div>

            <div class="p-6">
                @if ($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4"
                        role="alert">
                        <strong class="font-bold">Oops!</strong>
                        <span class="block sm:inline">{{ $errors->first() }}</span>
                    </div>
                @endif

                @foreach ($questions as $question)
                    @include('peserta.monev.survey.partials.question_card', ['question' => $question])
                @endforeach

                <h3 class="text-lg font-bold text-indigo-700 mt-8 mb-4">Kesan dan Pesan (Opsional)</h3>
                <textarea name="comments" rows="4"
                    class="w-full p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-400"
                    placeholder="Berikan saran atau masukan untuk perbaikan program pelatihan ke depan...">{{ old('comments') }}</textarea>

                <div class="flex justify-end mt-8">
                    <button type="submit"
                        class="bg-indigo-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-indigo-700 transition-colors">
                        Selanjutnya<i class="fas fa-arrow-right ml-2"></i>
                    </button>
                </div>
            </div>
        </div>
    </form>

    <script>
        // Script untuk memberikan feedback visual saat opsi dipilih.
        document.addEventListener('DOMContentLoaded', function() {
            // Ambil semua elemen pilihan rating
            const ratingOptions = document.querySelectorAll('.rating-option');

            ratingOptions.forEach(option => {
                // Tambahkan event listener untuk setiap pilihan
                option.addEventListener('click', function() {
                    const radioInput = this.querySelector('input[type="radio"]');
                    if (!radioInput) return;

                    const questionName = radioInput.name;
                    const selectedValue = radioInput.value;

                    // [BARU] Tambahkan log ke konsol untuk debugging
                    console.log(
                        `Pilihan untuk pertanyaan '${questionName}' diubah menjadi: ${selectedValue}`
                    );

                    // 1. Hapus kelas 'selected' dari semua pilihan dalam pertanyaan yang sama
                    document.querySelectorAll(`input[name="${questionName}"]`).forEach(radio => {
                        radio.closest('.rating-option').classList.remove('selected');
                    });

                    // 2. Tambahkan kelas 'selected' hanya pada pilihan yang diklik
                    this.classList.add('selected');
                });
            });
        });
    </script>

@endsection
