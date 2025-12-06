@extends('peserta.monev.layout.main')

@section('title', 'Pendaftaran MONEV Kegiatan Vokasi')

@section('content')
    <div class="max-w-2xl mx-auto text-center px-4 py-8 sm:py-12">

        <img src="{{ asset('images/logo-upt-ptkk.jpg') }}" alt="Logo Kementerian Pendidikan"
            class="mx-auto h-20 sm:h-24 md:h-28 mb-6" onerror="this.style.display='none'">

        <h1 class="text-xl sm:text-2xl md:text-3xl font-bold text-indigo-800 mb-3 leading-tight break-words">
            MONEV KEGIATAN PENGEMBANGAN DAN PENINGKATAN KOMPETENSI VOKASI
        </h1>

        <p class="text-gray-600 sm:text-lg">
            silakan masuk dengan nama dan email untuk memulai penilaian
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
            <input type="hidden" name="tes_id" value="{{ $tes->id }}">
            {{-- Menggunakan Grid Layout dari Tailwind CSS untuk membuat 2 kolom --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-8">

                {{-- Kolom 1: Nama Lengkap --}}
                <div class="relative">
                    <label for="nama" class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                        <i class="fas fa-user text-indigo-500 mr-2"></i> Nama Lengkap
                    </label>
                    <input type="text" id="nama" name="nama" value="{{ old('nama') }}"
                        class="form-input w-full px-4 py-3 border {{ $errors->has('nama') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:ring-indigo-500 focus:border-indigo-500"
                        required placeholder="Ketik nama Anda..." autocomplete="off">

                    <div id="suggestions"
                        class="absolute z-10 w-full bg-white border border-gray-300 rounded-md mt-1 shadow-lg hidden">
                    </div>

                    @if ($errors->has('nama'))
                        <p class="text-red-500 text-xs mt-1">{{ $errors->first('nama') }}</p>
                    @endif
                </div>

                {{-- Kolom 2: Periode Kegiatan --}}
                {{-- <div>
                    <label for="periode_kegiatan" class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                        <i class="fas fa-calendar-alt text-indigo-500 mr-2"></i> Periode Kegiatan
                    </label>
                    <input type="text" id="periode_kegiatan" name="periode_kegiatan"
                        value="{{ old('periode_kegiatan') }}"
                        class="form-input w-full px-4 py-3 border {{ $errors->has('periode_kegiatan') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:ring-indigo-500 focus:border-indigo-500"
                        required placeholder="Contoh: Angkatan I">

                    @if ($errors->has('periode_kegiatan'))
                        <p class="text-red-500 text-xs mt-1">{{ $errors->first('periode_kegiatan') }}</p>
                    @endif
                </div> --}}

                {{-- Kolom 3: Kompetensi --}}
                <div>
                    <label for="kompetensi_id" class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                        <i class="fas fa-graduation-cap text-indigo-500 mr-2"></i> Kompetensi
                    </label>
                    <select id="kompetensi_id" name="kompetensi_id"
                        class="form-select w-full px-4 py-3 border {{ $errors->has('kompetensi_id') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:ring-indigo-500 focus:border-indigo-500"
                        required>
                        <option value="" disabled selected>Pilih Kompetensi</option>
                        @foreach ($kompetensi as $b)
                            <option value="{{ $b->id }}" {{ old('kompetensi_id') == $b->id ? 'selected' : '' }}>
                                {{ $b->nama_kompetensi }}
                            </option>
                        @endforeach
                    </select>

                    @if ($errors->has('kompetensi_id'))
                        <p class="text-red-500 text-xs mt-1">{{ $errors->first('kompetensi_id') }}</p>
                    @endif
                </div>


                {{-- Kolom 5: Alamat Email --}}
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                        <i class="fas fa-envelope text-indigo-500 mr-2"></i> Alamat Email
                    </label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}"
                        class="form-input w-full px-4 py-3 border {{ $errors->has('email') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:ring-indigo-500 focus:border-indigo-500"
                        required placeholder="Masukkan email sesuai registrasi">

                    @if ($errors->has('email'))
                        <p class="text-red-500 text-xs mt-1">{{ $errors->first('email') }}</p>
                    @endif
                </div>

                {{-- angkatan --}}
                <div>
                    <label for="angkatan" class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                        <i class="fas fa-envelope text-indigo-500 mr-2"></i> Angkatan
                    </label>
                    <input type="text" id="angkatan" name="angkatan" value="{{ old('angkatan') }}"
                        class="form-input w-full px-4 py-3 border {{ $errors->has('angkatan') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:ring-indigo-500 focus:border-indigo-500"
                        required placeholder="contoh: Angkatan II">

                    @if ($errors->has('angkatan'))
                        <p class="text-red-500 text-xs mt-1">{{ $errors->first('angkatan') }}</p>
                    @endif
                </div>


                {{-- Kolom 4: Pelatihan --}}
                <div>
                    <label for="pelatihan_id" class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                        <i class="fas fa-chalkboard-teacher text-indigo-500 mr-2"></i> Pelatihan
                    </label>
                    <select id="pelatihan_id" name="pelatihan_id"
                        class="form-select w-full px-4 py-3 border {{ $errors->has('pelatihan_id') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:ring-indigo-500 focus:border-indigo-500"
                        required>
                        <option value="" disabled selected>Pilih Pelatihan</option>
                        @foreach ($pelatihan as $p)
                            <option value="{{ $p->id }}" {{ old('pelatihan_id') == $p->id ? 'selected' : '' }}>
                                {{ $p->nama_pelatihan }}
                            </option>
                        @endforeach
                    </select>

                    @if ($errors->has('pelatihan_id'))
                        <p class="text-red-500 text-xs mt-1">{{ $errors->first('pelatihan_id') }}</p>
                    @endif
                </div>

            </div>

            {{-- Tombol Submit di luar grid --}}
            <div class="mt-8">
                <button type="submit"
                    class="w-full bg-indigo-600 text-white font-bold py-3 px-4 rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-300">
                    Selanjutnya <i class="fas fa-arrow-right ml-2"></i>
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
