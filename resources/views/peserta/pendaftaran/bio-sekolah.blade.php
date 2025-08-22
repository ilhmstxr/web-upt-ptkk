@extends('peserta.layout.main', ['currentStep' => 2])

@section('title', 'Biodata Sekolah')

@section('content')
    <div class="bg-white rounded-xl shadow-sm p-6 sm:p-8 border border-slate-200">
        <form id="biodataSekolahForm" action="{{ route('pendaftaran.store') }}" method="POST" class="space-y-6" novalidate>
            @csrf

            <input type="hidden" name="current_step" value="{{ $currentStep }}">
            {{-- Asal Lembaga --}}
            <div>
                <label for="asal_instansi" class="block text-sm font-semibold mb-2 text-slate-700">Asal Lembaga Instansi</label>
                <div class="relative">
                    <input type="text" id="asal_instansi" name="asal_instansi" placeholder="Masukkan Asal Lembaga"
                        value="{{ old('asal_instansi', $formData['asal_instansi'] ?? '') }}"
                        class="w-full border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('asal_instansi') border-red-500 @enderror"
                        required />
                    <div id="asal_instansiError"
                        class="error-popup absolute bottom-full mb-2 w-full p-2 bg-red-600 text-white text-sm rounded-md shadow-lg flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 flex-shrink-0" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                clip-rule="evenodd" />
                        </svg>
                        <span class="error-message-text"></span>
                    </div>
                </div>
                @error('asal_instansi')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Alamat Instansi --}}
            <div>
                <label for="alamat_instansi" class="block text-sm font-semibold mb-2 text-slate-700">Alamat Lembaga
                    Instansi</label>
                <div class="relative">
                    <input type="text" id="alamat_instansi" name="alamat_instansi"
                        placeholder="Masukkan Lembaga Instansi"
                        value="{{ old('alamat_instansi', $formData['alamat_instansi'] ?? '') }}"
                        class="w-full border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('alamat_instansi') border-red-500 @enderror"
                        required />
                    <div id="alamat_instansiError"
                        class="error-popup absolute bottom-full mb-2 w-full p-2 bg-red-600 text-white text-sm rounded-md shadow-lg flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 flex-shrink-0" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                clip-rule="evenodd" />
                        </svg>
                        <span class="error-message-text"></span>
                    </div>
                </div>
                @error('alamat_instansi')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Kompetensi dan Kelas --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="bidang_keahlian" class="block text-sm font-semibold mb-2 text-slate-700">Kompetensi/Bidang
                        Keahlian</label>
                    <div class="relative">
                        {{-- <input type="text" id="bidang_keahlian" name="bidang_keahlian"
                            placeholder="Masukkan Kompetensi/Bidang Keahlian"
                            value="{{ old('bidang_keahlian', $formData['bidang_keahlian'] ?? '') }}"
                            class="w-full border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('bidang_keahlian') border-red-500 @enderror"
                            required /> --}}
                        <select id="bidang_keahlian" name="bidang_keahlian"
                            class="w-full border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('bidang_keahlian') border-red-500 @enderror"
                            required>
                            <option value="">Pilih Kompetensi</option>
                            @foreach ($bidang as $b)
                                <option value="{{ $b->id }}" @if (old('bidang_keahlian', $formData['bidang_keahlian'] ?? '') == $b->id) selected @endif>
                                    {{ $b->nama_bidang }}</option>
                            @endforeach
                        </select>
                        <div id="bidang_keahlianError"
                            class="error-popup absolute bottom-full mb-2 w-full p-2 bg-red-600 text-white text-sm rounded-md shadow-lg flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 flex-shrink-0" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                    clip-rule="evenodd" />
                            </svg>
                            <span class="error-message-text"></span>
                        </div>
                    </div>
                    @error('bidang_keahlian')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="kelas" class="block text-sm font-semibold mb-2 text-slate-700">Kelas</label>
                    <div class="relative">
                        <select id="kelas" name="kelas"
                            class="w-full border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('kelas') border-red-500 @enderror"
                            required>
                            <option value="">Pilih Kelas</option>
                            <option value="X" @if (old('kelas', $formData['kelas'] ?? '') == 'X') selected @endif>Kelas X</option>
                            <option value="XI" @if (old('kelas', $formData['kelas'] ?? '') == 'XI') selected @endif>Kelas XI</option>
                            <option value="XII" @if (old('kelas', $formData['kelas'] ?? '') == 'XII') selected @endif>Kelas XII</option>
                        </select>
                        <div id="kelasError"
                            class="error-popup absolute bottom-full mb-2 w-full p-2 bg-red-600 text-white text-sm rounded-md shadow-lg flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 flex-shrink-0" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                    clip-rule="evenodd" />
                            </svg>
                            <span class="error-message-text"></span>
                        </div>
                    </div>
                    @error('kelas')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>


            <div class="grid grid-cols-1 gap-2">
                {{-- 1. Gunakan <fieldset> dan <legend> untuk aksesibilitas yang lebih baik --}}
                <fieldset>
                    <legend class="block text-sm font-semibold mb-3 text-slate-700">
                        Pelatihan yang ingin diikuti
                    </legend>

                    <div class="space-y-3">
                        {{-- Loop melalui setiap pelatihan dari database --}}
                        @foreach ($pelatihan as $p)
                            {{-- 2. Styling setiap opsi agar terlihat seperti kartu yang bisa diklik --}}
                            <label
                                class="flex items-center gap-3 border border-slate-300 rounded-lg p-3 cursor-pointer hover:bg-sky-50 hover:border-sky-400 transition-all duration-200 has-[:checked]:bg-sky-100 has-[:checked]:border-sky-500 has-[:checked]:ring-2 has-[:checked]:ring-sky-200">
                                <input type="radio" id="pelatihan_{{ $p->id }}" name="pelatihan_id"
                                    value="{{ $p->id }}"
                                    class="form-radio text-blue-600 focus:ring-blue-500 border-gray-300"
                                    {{-- 3. Gunakan directive @checked yang lebih bersih --}} @checked(old('pelatihan_id', $formData['pelatihan_id'] ?? '') == $p->id) required>
                                <span class="font-medium text-slate-800">{{ $p->nama_pelatihan }}</span>
                            </label>
                        @endforeach
                    </div>
                </fieldset>

                {{-- 4. Pesan error standar yang rapi sudah cukup --}}
                @error('pelatihan_id')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Cabang Dinas --}}
            <div>
                <label for="cabang_dinas_wilayah" class="block text-sm font-semibold mb-2 text-slate-700">Cabang Dinas
                    Wilayah</label>
                <div class="relative">
                    <select id="cabang_dinas_wilayah" name="cabang_dinas_wilayah"
                        class="w-full border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('cabang_dinas_wilayah') border-red-500 @enderror"
                        required>
                        <option value="">Pilih Dinas Wilayah</option>
                        @foreach ($cabangDinas as $cb)
                            {{-- <option value="surabaya" @if (old('cabang_dinas_wilayah') == 'surabaya') selected @endif>Cabang Dinas Wilayah
                            Surabaya</option> --}}
                            <option value="{{ $cb->id }}" @selected(old('cabang_dinas_wilayah', $formData['cabang_dinas_wilayah'] ?? '') == $cb->id)>
                                {{ $cb->nama }}
                            </option>
                        @endforeach
                        {{-- (Tambahkan opsi lain di sini) --}}
                    </select>
                    <div id="cabang_dinas_wilayahError"
                        class="error-popup absolute bottom-full mb-2 w-full p-2 bg-red-600 text-white text-sm rounded-md shadow-lg flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 flex-shrink-0" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                clip-rule="evenodd" />
                        </svg>
                        <span class="error-message-text"></span>
                    </div>
                </div>
                @error('cabang_dinas_wilayah')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Tombol Navigasi --}}
            <div class="flex justify-between items-center pt-4">
                <a href="{{ route('pendaftaran.create', ['step' => 1]) }}"
                    class="text-sm font-semibold text-slate-600 hover:text-slate-800 transition-colors">
                    &larr; Kembali
                </a>
                <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-8 py-2.5 rounded-lg shadow-md transition-all duration-300 transform hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Selanjutnya
                </button>
            </div>
        </form>
    </div>
@endsection

    <style>
        .error-popup {
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.3s ease, visibility 0.3s ease, transform 0.3s ease;
            transform: translateY(10px);
            z-index: 10;
        }

        .error-popup.visible {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }
    </style>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const form = document.getElementById('biodataSekolahForm');

            // Fungsi untuk menampilkan pop-up error
            const showError = (element, message) => {
                const errorPopup = document.getElementById(element.id + 'Error');
                if (errorPopup) {
                    errorPopup.querySelector('.error-message-text').textContent = message;
                    errorPopup.classList.add('visible');
                    element.classList.add('border-red-500', 'focus:ring-red-500');
                }
            };

            // Fungsi untuk menyembunyikan pop-up error
            const hideError = (element) => {
                const errorPopup = document.getElementById(element.id + 'Error');
                if (errorPopup) {
                    errorPopup.classList.remove('visible');
                    element.classList.remove('border-red-500', 'focus:ring-red-500');
                }
            };

            // Sembunyikan semua error saat form di-reset atau dimuat ulang
            const hideAllErrors = () => {
                form.querySelectorAll('input[required], select[required]').forEach(hideError);
            };

            // Tambahkan event listener untuk menyembunyikan error saat input/select diubah
            form.querySelectorAll('input[required], select[required]').forEach(element => {
                element.addEventListener('input', () => hideError(element));
                element.addEventListener('change', () => hideError(element));
            });

            form.addEventListener('submit', function(event) {
                hideAllErrors();
                let firstErrorElement = null;

                for (const element of form.elements) {
                    if (element.hasAttribute('required') && !element.validity.valid) {
                        event.preventDefault();

                        let message = 'Kolom ini wajib diisi.'; // Pesan default

                        // Pesan kustom berdasarkan ID elemen
                        switch (element.id) {
                            case 'asal_instansi':
                                message = 'Asal instansi sekolah wajib diisi.';
                                break;
                            case 'alamat_instansi':
                                message = 'Alamat instansi tidak boleh kosong.';
                                break;
                            case 'bidang_keahlian':
                                message = 'Silakan pilih kompetensi keahlian Anda.';
                                break;
                            case 'pelatihan_id':
                                message = 'Silakan pilih pelatihan yang ingin diikuti.';
                                break;
                            case 'kelas':
                                message = 'Anda harus memilih kelas.';
                                break;
                            case 'cabang_dinas_wilayah':
                                message = 'Mohon pilih cabang dinas wilayah.';
                                break;
                        }

                        showError(element, message);

                        if (!firstErrorElement) {
                            firstErrorElement = element;
                        }
                    }
                }

                if (firstErrorElement) {
                    firstErrorElement.focus();
                }
            });

            
        // Pilih semua radio button yang memiliki nama 'pelatihan_id'
        const radioButtons = document.querySelectorAll('input[type="radio"][name="pelatihan_id"]');
        
        // Variabel untuk melacak radio button yang terakhir dicentang
        let lastChecked = null;

        // Tambahkan event listener untuk setiap radio button
        radioButtons.forEach(radio => {
            radio.addEventListener('click', function() {
                // Periksa apakah radio yang diklik adalah yang sama dengan yang terakhir dicentang
                if (this === lastChecked) {
                    // Jika ya, batalkan centangnya
                    this.checked = false;
                    // Reset pelacak
                    lastChecked = null;
                } else {
                    // Jika ini adalah pilihan baru, update pelacak ke radio button ini
                    lastChecked = this;
                }
            });
        });
        });

    </script>
