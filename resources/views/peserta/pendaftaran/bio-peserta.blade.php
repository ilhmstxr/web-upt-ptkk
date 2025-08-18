@extends('peserta.layout.main', ['currentStep' => 1])

@section('title', 'Biodata Peserta')



@section('content')
    <div class="bg-white rounded-xl shadow-sm p-6 sm:p-8 border border-slate-200">
        <form id="registrationForm" action="{{ route('pendaftaran.store') }}" method="POST" class="space-y-6" novalidate>
            @csrf

            <input type="hidden" name="current_step" value="{{ $currentStep }}">

            {{-- Nama dan NIK --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="nama" class="block text-sm font-semibold mb-2 text-slate-700">Nama</label>
                    {{-- Container relatif untuk pop-up --}}
                    <div class="relative">
                        <input type="text" id="nama" name="nama" placeholder="Masukkan Nama Lengkap"
                            value="{{ old('nama') }}"
                            class="w-full border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('nama') border-red-500 @enderror"
                            required />
                        {{-- Pop-up Error Kustom untuk Nama --}}
                        <div id="namaError"
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
                    @error('nama')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="nik" class="block text-sm font-semibold mb-2 text-slate-700">NIK (16 digit)</label>
                    <div class="relative">
                        <input type="text" id="nik" name="nik" maxlength="16"
                            placeholder="Masukkan 16 digit NIK" value="{{ old('nik') }}" pattern="\d{16}"
                            title="NIK harus terdiri dari 16 digit angka."
                            oninput="this.value = this.value.replace(/[^0-9]/g, '');"
                            class="w-full border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('nik') border-red-500 @enderror"
                            required />
                        {{-- Pop-up Error Kustom untuk NIK --}}
                        <div id="nikError"
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
                    @error('nik')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Email & no hp --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="no_hp" class="block text-sm font-semibold mb-2 text-slate-700">Nomor Handphone / Whatsapp</label>
                    <div class="relative">
                        <input type="tel" id="no_hp" name="no_hp" maxlength="15"
                            placeholder="Contoh: 081234567890" value="{{ old('no_hp') }}"
                            oninput="this.value = this.value.replace(/[^0-9]/g, '');"
                            class="w-full border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('no_hp') border-red-500 @enderror"
                            required />
                        <div id="no_hpError"
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
                    @error('no_hp')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="email" class="block text-sm font-semibold mb-2 text-slate-700">Email</label>
                    <div class="relative">
                        <input type="email" id="email" name="email" placeholder="Masukkan Email Aktif"
                            value="{{ old('email') }}"
                            class="w-full border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('email') border-red-500 @enderror"
                            required />
                        <div id="emailError"
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
                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Tempat, tanggal lahir --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="tempat_lahir" class="block text-sm font-semibold mb-2 text-slate-700">Tempat Lahir</label>
                    <div class="relative">
                        <input type="text" id="tempat_lahir" name="tempat_lahir" maxlength="15"
                            placeholder="Surabaya" value="{{ old('tempat_lahir') }}"
                            class="w-full border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('tempat_lahir') border-red-500 @enderror"
                            required />
                        <div id="tempat_lahirError"
                            class="error-popup absolute bottom-full mb-2 w-full p-2 bg-red-600 text-white text-sm rounded-md shadow-lg flex items-center">
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
                    <label for="tanggal_lahir"
                        class="block text-sm font-semibold mb-2 text-slate-700">Tanggal Lahir</label>
                    <div class="relative">
                        <input type="date" id="tanggal_lahir" name="tanggal_lahir"
                            placeholder="Masukkan tanggal_lahir Aktif" value="{{ old('tanggal_lahir') }}"
                            class="w-full border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('tanggal_lahir') border-red-500 @enderror"
                            required />
                        <div id="tanggal_lahirError"
                            class="error-popup absolute bottom-full mb-2 w-full p-2 bg-red-600 text-white text-sm rounded-md shadow-lg flex items-center">
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

            {{-- Jenis kelamin, agama --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="jenis_kelamin" class="block text-sm font-semibold mb-2 text-slate-700">Jenis Kelamin</label>
                    <div class="relative">
                        <select id="jenis_kelamin" name="jenis_kelamin"
                            class="w-full border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('jenis_kelamin') border-red-500 @enderror"
                            required>
                            <option value="">Pilih Jenis Kelamin</option>
                            <option value="Laki-laki" {{ old('jenis_kelamin') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki
                            </option>
                            <option value="Perempuan" {{ old('jenis_kelamin') == 'Perempuan' ? 'selected' : '' }}>Perempuan
                            </option>
                        </select>
                        <div id="jenis_kelaminError"
                            class="error-popup absolute bottom-full mb-2 w-full p-2 bg-red-600 text-white text-sm rounded-md shadow-lg flex items-center">
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
                    <label for="agama" class="block text-sm font-semibold mb-2 text-slate-700">Agama</label>
                    <div class="relative">
                        <select id="agama" name="agama"
                            class="w-full border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('agama') border-red-500 @enderror"
                            required>
                            <option value="">Pilih Agama</option>
                            <option value="Islam" {{ old('agama') == 'Islam' ? 'selected' : '' }}>Islam</option>
                            <option value="Kristen" {{ old('agama') == 'Kristen' ? 'selected' : '' }}>Kristen</option>
                            <option value="Katolik" {{ old('agama') == 'Katolik' ? 'selected' : '' }}>Katolik</option>
                            <option value="Hindu" {{ old('agama') == 'Hindu' ? 'selected' : '' }}>Hindu</option>
                            <option value="Buddha" {{ old('agama') == 'Buddha' ? 'selected' : '' }}>Buddha</option>
                            <option value="Konghucu" {{ old('agama') == 'Konghucu' ? 'selected' : '' }}>Konghucu</option>
                        </select>
                        <div id="agamaError"
                            class="error-popup absolute bottom-full mb-2 w-full p-2 bg-red-600 text-white text-sm rounded-md shadow-lg flex items-center">
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

            {{-- alamat tinggal --}}
            <div class="grid grid-cols-1  gap-6">
                <label for="alamat" class="block text-sm font-semibold mb-2 text-slate-700">Alamat Tempat
                    Tinggal</label>
                <div class="relative">
                    <textarea id="alamat" name="alamat" placeholder="Masukkan Alamat Tinggal" value="{{ old('email') }}"
                        class="w-full border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('alamat') border-red-500 @enderror"
                        required></textarea>
                    <div id="alamatError"
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
                @error('alamat')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>


            {{-- (Tambahkan div.relative dan div.error-popup untuk semua field yang required) --}}

            {{-- Tombol Submit --}}
            <div class="flex justify-end pt-4">
                <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-8 py-2.5 rounded-lg shadow-md transition-all duration-300 transform hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Selanjutnya
                </button>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const form = document.getElementById('registrationForm');

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
                form.querySelectorAll('input[required], select[required], textarea[required]').forEach(
                    element => {
                        hideError(element);
                    });
            };

            // Tambahkan event listener ke setiap input untuk menyembunyikan error saat diketik
            form.querySelectorAll('input[required], select[required], textarea[required]').forEach(element => {
                element.addEventListener('input', () => hideError(element));
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
                                message = 'Kolom nama tidak boleh kosong, ya.';
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
                                    message = 'Format email sepertinya salah.';
                                } else {
                                    message = 'Mohon isi alamat email Anda.';
                                }
                                break;
                            case 'pelatihan_id':
                                message = 'Anda harus memilih pelatihan terlebih dahulu.';
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
                }
            });
        });
    </script>

@endsection
