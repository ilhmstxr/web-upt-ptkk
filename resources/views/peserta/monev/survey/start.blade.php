@extends('peserta.monev.layout.main')

@section('title', 'Pendaftaran MONEV Kegiatan Vokasi')

@section('content')
    <div class="max-w-2xl mx-auto text-center px-4 py-8 sm:py-12">

        <img src="{{ asset('images/logo-upt-ptkk.png') }}" alt="Logo Kementerian Pendidikan"
            class="mx-auto h-20 sm:h-24 md:h-28 mb-6" onerror="this.style.display='none'">

        <h1 class="text-xl sm:text-2xl md:text-3xl font-bold text-indigo-800 mb-3 leading-tight break-words">
            MONEV KEGIATAN PENGEMBANGAN DAN PENINGKATAN KOMPETENSI VOKASI
        </h1>

        <p class="text-gray-600 sm:text-lg">
            Silakan masuk dengan Nama dan Email yang terdaftar untuk memulai penilaian
        </p>

    </div>


    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg relative mb-4" role="alert">
            <strong class="font-bold">Harap perbaiki error berikut:</strong>
            <ul class="list-disc list-inside mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <div class="bg-white rounded-xl shadow-lg overflow-hidden p-6 sm:p-8 max-w-xl mx-auto">
        <form action="{{ route('survey.start') }}" method="POST" class="space-y-6">
            @csrf
            <input type="hidden" name="tes_id" value="{{ $kuis->id }}">
            <div class="relative">

                <label for="nama" class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                    <i class="fas fa-user text-indigo-500 mr-2"></i> Nama Lengkap
                </label>

                <input type="text" id="nama" name="nama" value="{{ old('nama') }}"
                    class="form-input w-full px-4 py-2 sm:py-3 border {{ $errors->has('nama') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:ring-indigo-500 focus:border-indigo-500"
                    required placeholder="Ketik nama Anda, sistem akan membantu..." autocomplete="off">

                <div id="suggestions"
                    class="absolute z-10 w-full bg-white border border-gray-300 rounded-md mt-1 shadow-lg hidden">
                </div>

                @if ($errors->has('nama'))
                    <p class="text-red-500 text-xs mt-1">{{ $errors->first('nama') }}</p>
                @endif

            </div>
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                    <i class="fas fa-envelope text-indigo-500 mr-2"></i> Alamat Email
                </label>
                <div class="relative">
                    <input type="email" id="email" name="email" value="{{ old('email') }}"
                        class="form-input w-full px-4 py-2 sm:py-3 border {{ $errors->has('email') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:ring-indigo-500 focus:border-indigo-500"
                        required placeholder="Masukkan email sesuai registrasi">
                    <div id="status-icon" class="absolute inset-y-0 right-0 pr-3 flex items-center text-sm leading-5"></div>
                </div>
                <p id="status-message" class="text-xs mt-1"></p>
                @if ($errors->has('email'))
                    <p class="text-red-500 text-xs mt-1">{{ $errors->first('email') }}</p>
                @endif
            </div>

            <div class="pt-4">
                <button type="submit"
                    class="btn-primary w-full py-2 sm:py-3 px-4 text-white font-semibold rounded-lg flex items-center justify-center">
                    <span>Mulai Survei</span>
                    <i class="fas fa-arrow-right ml-2"></i>
                </button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        emailInput.addEventListener('input', function() {
            const email = emailInput.value.trim();
            if (email && email.includes('@')) {
                statusIcon.innerHTML = '<i class="fas fa-check-circle text-green-500"></i>';
                statusMessage.textContent = 'Format email valid.';
                statusMessage.className = 'text-xs mt-1 text-green-600';
            } else {
                statusIcon.innerHTML = '<i class="fas fa-times-circle text-red-500"></i>';
                statusMessage.textContent = 'Format email tidak valid.';
                statusMessage.className = 'text-xs mt-1 text-red-600';
            }
        });

        // Ambil elemen input nama dan div untuk saran
        const nameInput = document.getElementById('nama');
        const suggestionsPanel = document.getElementById('suggestions');

        // Fungsi debounce untuk mencegah terlalu banyak request ke server
        // Request hanya akan dikirim setelah pengguna berhenti mengetik selama 300ms
        function debounce(func, delay) {
            let timeout;
            return function(...args) {
                clearTimeout(timeout);
                timeout = setTimeout(() => func.apply(this, args), delay);
            };
        }

        // Fungsi utama untuk mengambil dan menampilkan data
        const getSuggestions = async (query) => {
            // Jika input kosong, sembunyikan panel
            if (query.length < 3) { // Hanya cari jika input lebih dari 2 karakter
                suggestionsPanel.innerHTML = '';
                suggestionsPanel.style.display = 'none';
                return;
            }

            try {
                // Panggil API yang sudah kita buat
                const response = await fetch(`/api/peserta/search?nama=${query}`);
                const suggestions = await response.json();

                // Kosongkan saran sebelumnya
                suggestionsPanel.innerHTML = '';

                if (suggestions.length > 0) {
                    // Tampilkan panel
                    suggestionsPanel.style.display = 'block';

                    // Tampilkan setiap saran di dalam panel
                    suggestions.forEach(suggestion => {
                        const suggestionDiv = document.createElement('div');
                        suggestionDiv.textContent = suggestion.nama;
                        suggestionDiv.className = 'p-2 hover:bg-gray-100 cursor-pointer';

                        // Tambahkan event listener saat saran di-klik
                        suggestionDiv.onclick = () => {
                            nameInput.value = suggestion.nama; // Isi input dengan nama yang dipilih
                            suggestionsPanel.innerHTML = ''; // Kosongkan saran
                            suggestionsPanel.style.display = 'none'; // Sembunyikan panel
                        };

                        suggestionsPanel.appendChild(suggestionDiv);
                    });
                } else {
                    // Jika tidak ada hasil, sembunyikan panel
                    suggestionsPanel.style.display = 'none';
                }
            } catch (error) {
                console.error('Error fetching suggestions:', error);
                suggestionsPanel.style.display = 'none';
            }
        };

        // Tambahkan event listener pada input nama
        nameInput.addEventListener('input', debounce((e) => {
            getSuggestions(e.target.value);
        }, 300));

        // Sembunyikan saran jika user mengklik di luar area
        document.addEventListener('click', function(e) {
            if (!nameInput.contains(e.target)) {
                suggestionsPanel.style.display = 'none';
            }
        });
    </script>
@endpush
