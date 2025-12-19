@extends('peserta.pendaftaran.layout.main', [
    'currentStep' => 1,
    'allowedStep' => 3,
    'steps' => [1 => 'Biodata Diri', 2 => 'Biodata Sekolah', 3 => 'Lampiran'],
])

@section('title', 'Biodata Peserta')

@section('content')
<div class="bg-white rounded-xl shadow-lg p-4 sm:p-6 md:p-8 border border-blue-200 max-w-5xl mx-auto">
    <div class="mb-6 sm:mb-8 text-center">
        <h2 class="text-xl sm:text-2xl font-bold text-blue-900 mb-1 sm:mb-2">Biodata Peserta</h2>
        <p class="text-blue-600 text-sm sm:text-base">Lengkapi data pribadi Anda dengan benar</p>
    </div>

    <form id="registrationForm"
          action="{{ route('pendaftaran.store') }}"
          method="POST"
          class="space-y-6 sm:space-y-8"
          novalidate>
        @csrf
        <input type="hidden" name="current_step" value="{{ $currentStep }}">

        {{-- Nama dan NIK --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
            <div class="flex flex-col">
                <label for="nama" class="text-sm font-semibold mb-1 sm:mb-2 text-blue-900">Nama Lengkap</label>
                <input type="text" id="nama" name="nama" placeholder="Masukkan Nama Lengkap (tulis gelar jika ada, cth: S.Kom, S.Pd)"
                       value="{{ old('nama', $formData['nama'] ?? '') }}"
                       class="w-full bg-white border-2 border-blue-200 rounded-lg px-3 sm:px-4 py-2.5 sm:py-3 text-blue-900 placeholder-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-blue-500 transition-all duration-200 shadow-sm @error('nama') border-red-400 @enderror"
                       required />
                @error('nama')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="flex flex-col">
                <label for="nik" class="text-sm font-semibold mb-1 sm:mb-2 text-blue-900">NIK (16 digit)</label>
                <input type="text" id="nik" name="nik" maxlength="16"
                       placeholder="Masukkan 16 digit NIK"
                       value="{{ old('nik', $formData['nik'] ?? '') }}"
                       oninput="this.value=this.value.replace(/[^0-9]/g,'');"
                       class="w-full bg-white border-2 border-blue-200 rounded-lg px-3 sm:px-4 py-2.5 sm:py-3 text-blue-900 placeholder-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-blue-500 transition-all duration-200 shadow-sm @error('nik') border-red-400 @enderror"
                       required />
                @error('nik')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- No HP & Email --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
            <div class="flex flex-col">
                <label for="no_hp" class="text-sm font-semibold mb-1 sm:mb-2 text-blue-900">Nomor Handphone / WhatsApp</label>
                <input type="tel" id="no_hp" name="no_hp" maxlength="15"
                       placeholder="Contoh: 081234567890"
                       value="{{ old('no_hp', $formData['no_hp'] ?? '') }}"
                       oninput="this.value=this.value.replace(/[^0-9]/g,'');"
                       class="w-full bg-white border-2 border-blue-200 rounded-lg px-3 sm:px-4 py-2.5 sm:py-3 text-blue-900 placeholder-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-blue-500 transition-all duration-200 shadow-sm @error('no_hp') border-red-400 @enderror"
                       required />
                @error('no_hp')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="flex flex-col">
                <label for="email" class="text-sm font-semibold mb-1 sm:mb-2 text-blue-900">Email Aktif</label>
                <input type="email" id="email" name="email" placeholder="Masukkan Email Aktif"
                       value="{{ old('email', $formData['email'] ?? '') }}"
                       class="w-full bg-white border-2 border-blue-200 rounded-lg px-3 sm:px-4 py-2.5 sm:py-3 text-blue-900 placeholder-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-blue-500 transition-all duration-200 shadow-sm @error('email') border-red-400 @enderror"
                       required />
                @error('email')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>


            {{-- Tempat & Tanggal Lahir --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="tempat_lahir" class="block text-sm font-semibold mb-2 text-blue-900">Tempat Lahir</label>
                    <div class="relative">
                        <input type="text" id="tempat_lahir" name="tempat_lahir" maxlength="50"
                            placeholder="Contoh: Surabaya" value="{{ old('tempat_lahir',$formData['tempat_lahir'] ?? '') }}"
                            class="w-full bg-white border-2 border-blue-200 rounded-lg px-4 py-3 text-blue-900 placeholder-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-blue-500 transition-all duration-200 shadow-sm @error('tempat_lahir') border-red-400 @enderror"
                            required />
                        <div id="tempat_lahirError"
                            class="error-popup absolute bottom-full mb-2 w-full p-3 bg-red-500 text-white text-sm rounded-lg shadow-lg flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 flex-shrink-0"
                                viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                    clip-rule="evenodd" />
                            </svg>
                            <span class="error-message-text"></span>
                        </div>
                    </div>
                    @error('tempat_lahir')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="tanggal_lahir" class="block text-sm font-semibold mb-2 text-blue-900">Tanggal Lahir</label>
                    <div class="relative">
                        <input type="date" id="tanggal_lahir" name="tanggal_lahir"
                            value="{{ old('tanggal_lahir', $formData['tanggal_lahir'] ?? '') }}"
                            class="w-full bg-white border-2 border-blue-200 rounded-lg px-4 py-3 text-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-blue-500 transition-all duration-200 shadow-sm @error('tanggal_lahir') border-red-400 @enderror"
                            required />
                        <div id="tanggal_lahirError"
                            class="error-popup absolute bottom-full mb-2 w-full p-3 bg-red-500 text-white text-sm rounded-lg shadow-lg flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 flex-shrink-0"
                                viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                    clip-rule="evenodd" />
                            </svg>
                            <span class="error-message-text"></span>
                        </div>
                    </div>
                    @error('tanggal_lahir')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Jenis Kelamin & Agama --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="jenis_kelamin" class="block text-sm font-semibold mb-2 text-blue-900">Jenis Kelamin</label>
                    <div class="relative">
                        <select id="jenis_kelamin" name="jenis_kelamin"
                            class="w-full bg-white border-2 border-blue-200 rounded-lg px-4 py-3 text-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-blue-500 transition-all duration-200 shadow-sm @error('jenis_kelamin') border-red-400 @enderror"
                            required>
                            <option value="" class="text-blue-400">Pilih Jenis Kelamin</option>
                            <option value="Laki-laki" {{ old('jenis_kelamin', $formData['jenis_kelamin'] ?? '') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="Perempuan" {{ old('jenis_kelamin', $formData['jenis_kelamin'] ?? '') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                        <div id="jenis_kelaminError"
                            class="error-popup absolute bottom-full mb-2 w-full p-3 bg-red-500 text-white text-sm rounded-lg shadow-lg flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 flex-shrink-0"
                                viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                    clip-rule="evenodd" />
                            </svg>
                            <span class="error-message-text"></span>
                        </div>
                    </div>
                    @error('jenis_kelamin')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="agama" class="block text-sm font-semibold mb-2 text-blue-900">Agama</label>
                    <div class="relative">
                        <select id="agama" name="agama"
                            class="w-full bg-white border-2 border-blue-200 rounded-lg px-4 py-3 text-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-blue-500 transition-all duration-200 shadow-sm @error('agama') border-red-400 @enderror"
                            required>
                            <option value="" class="text-blue-400">Pilih Agama</option>
                            <option value="Islam" {{ old('agama', $formData['agama'] ?? '') == 'Islam' ? 'selected' : '' }}>Islam</option>
                            <option value="Kristen" {{ old('agama', $formData['agama'] ?? '') == 'Kristen' ? 'selected' : '' }}>Kristen</option>
                            <option value="Katolik" {{ old('agama', $formData['agama'] ?? '') == 'Katolik' ? 'selected' : '' }}>Katolik</option>
                            <option value="Hindu" {{ old('agama', $formData['agama'] ?? '') == 'Hindu' ? 'selected' : '' }}>Hindu</option>
                            <option value="Buddha" {{ old('agama', $formData['agama'] ?? '') == 'Buddha' ? 'selected' : '' }}>Buddha</option>
                            <option value="Konghucu" {{ old('agama', $formData['agama'] ?? '') == 'Konghucu' ? 'selected' : '' }}>Konghucu</option>
                        </select>
                        <div id="agamaError"
                            class="error-popup absolute bottom-full mb-2 w-full p-3 bg-red-500 text-white text-sm rounded-lg shadow-lg flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 flex-shrink-0"
                                viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                    clip-rule="evenodd" />
                            </svg>
                            <span class="error-message-text"></span>
                        </div>
                    </div>
                    @error('agama')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Alamat Tinggal --}}
            <div>
                <label for="alamat" class="block text-sm font-semibold mb-2 text-blue-900">Alamat Tempat Tinggal</label>
                <div class="relative">
                    <textarea id="alamat" name="alamat" rows="4" placeholder="Masukkan alamat lengkap tempat tinggal Anda"
                        class="w-full bg-white border-2 border-blue-200 rounded-lg px-4 py-3 text-blue-900 placeholder-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-blue-500 transition-all duration-200 shadow-sm resize-none @error('alamat') border-red-400 @enderror"
                        required>{{ old('alamat', $formData['alamat'] ?? '') }}</textarea>
                    <div id="alamatError"
                        class="error-popup absolute bottom-full mb-2 w-full p-3 bg-red-500 text-white text-sm rounded-lg shadow-lg flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 flex-shrink-0" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                clip-rule="evenodd" />
                        </svg>
                        <span class="error-message-text"></span>
                    </div>
                </div>
                @error('alamat')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Submit Button --}}
            <div class="flex justify-end pt-6 border-t border-blue-100">
                <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-8 py-3 rounded-lg shadow-md transition-all duration-300 transform hover:-translate-y-1 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 hover:shadow-lg">
                    Selanjutnya
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </button>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const form = document.getElementById('registrationForm');

            // Function to convert text to uppercase in real-time, excluding email and password fields
            function applyUppercaseToFields() {
                // Get all input and textarea elements, but exclude email, password, date, number, tel, and hidden inputs
                const textInputs = [
                    ...form.querySelectorAll('input[type="text"]'),
                    ...form.querySelectorAll('textarea')
                ].filter(input => {
                    // Don't apply to email, password, date, number, tel, or hidden inputs
                    return input.type !== 'email' &&
                           input.type !== 'password' &&
                           input.type !== 'date' &&
                           input.type !== 'number' &&
                           input.type !== 'tel' &&
                           input.type !== 'hidden';
                });

                textInputs.forEach(input => {
                    // Convert to uppercase as user types
                    input.addEventListener('input', function() {
                        const start = this.selectionStart;
                        const end = this.selectionEnd;
                        this.value = this.value.toUpperCase();
                        // Maintain cursor position
                        this.setSelectionRange(start, end);
                    });

                    // Convert to uppercase when pasting content
                    input.addEventListener('paste', function(e) {
                        setTimeout(() => {
                            const start = this.selectionStart;
                            const end = this.selectionEnd;
                            this.value = this.value.toUpperCase();
                            this.setSelectionRange(start, end);
                        }, 10);
                    });
                });
            }

            // Apply uppercase to text fields immediately
            applyUppercaseToFields();

            // Fungsi untuk menampilkan pop-up error
            const showError = (element, message) => {
                const errorPopup = document.getElementById(element.id + 'Error');
                if (errorPopup) {
                    errorPopup.querySelector('.error-message-text').textContent = message;
                    errorPopup.classList.add('visible');
                    element.classList.add('border-red-400', 'focus:ring-red-400');
                }
            };

            // Fungsi untuk menyembunyikan pop-up error
            const hideError = (element) => {
                const errorPopup = document.getElementById(element.id + 'Error');
                if (errorPopup) {
                    errorPopup.classList.remove('visible');
                    element.classList.remove('border-red-400', 'focus:ring-red-400');
                }
            };

            // Sembunyikan semua error saat form di-reset atau dimuat ulang
            const hideAllErrors = () => {
                form.querySelectorAll('input[required], select[required], textarea[required]').forEach(
                    element => {
                        hideError(element);
                    });
            };

            // Tambahkan event listener ke setiap input untuk menyembunyikan error saat diketik
            form.querySelectorAll('input[required], select[required], textarea[required]').forEach(element => {
                element.addEventListener('input', () => hideError(element));
                element.addEventListener('change', () => hideError(element));
            });

            form.addEventListener('submit', function(event) {
                // Sembunyikan error lama sebelum validasi baru
                hideAllErrors();
                let firstErrorElement = null;

                // Loop ke semua elemen yang wajib diisi
                for (const element of form.elements) {
                    if (element.hasAttribute('required') && !element.validity.valid) {
                        event.preventDefault(); // Mencegah form dikirim

                        let message = 'Kolom ini wajib diisi.'; // Pesan default

                        // Pesan kustom berdasarkan ID elemen
                        switch (element.id) {
                            case 'nama':
                                message = 'Kolom nama tidak boleh kosong.';
                                break;
                            case 'nik':
                                if (element.value.length > 0 && element.value.length < 16) {
                                    message = 'NIK harus 16 digit.';
                                } else {
                                    message = 'NIK wajib diisi dengan 16 digit angka.';
                                }
                                break;
                            case 'email':
                                if (element.validity.typeMismatch) {
                                    message = 'Format email tidak valid.';
                                } else {
                                    message = 'Mohon isi alamat email Anda.';
                                }
                                break;
                            case 'no_hp':
                                message = 'Nomor handphone wajib diisi.';
                                break;
                            case 'tempat_lahir':
                                message = 'Tempat lahir wajib diisi.';
                                break;
                            case 'tanggal_lahir':
                                message = 'Tanggal lahir wajib diisi.';
                                break;
                            case 'jenis_kelamin':
                                message = 'Pilih jenis kelamin Anda.';
                                break;
                            case 'agama':
                                message = 'Pilih agama Anda.';
                                break;
                            case 'alamat':
                                message = 'Alamat tinggal wajib diisi.';
                                break;
                        }

                        showError(element, message);

                        // Simpan elemen pertama yang error untuk difokuskan nanti
                        if (!firstErrorElement) {
                            firstErrorElement = element;
                        }
                    }
                }

                // Jika ada error, fokus ke elemen pertama
                if (firstErrorElement) {
                    firstErrorElement.focus();
                    firstErrorElement.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            });
        });
    </script>

    <style>
        /* Error popup animation */
        .error-popup {
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.3s ease, transform 0.3s ease;
            transform: translateY(10px);
            z-index: 10;
        }

        .error-popup.visible {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        /* Custom select styling */
        select option {
            color: #1e3a8a;
        }

        /* Focus states */
        input:focus, select:focus, textarea:focus {
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }
    </style>

@endsection
