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
                        value="{{ old('asal_instansi') }}"
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
                <label for="alamat_instansi" class="block text-sm font-semibold mb-2 text-slate-700">Alamat Instansi</label>
                <div class="relative">
                    <input type="text" id="alamat_instansi" name="alamat_instansi" placeholder="Masukkan Alamat Instansi"
                        value="{{ old('alamat_instansi') }}"
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
                        <input type="text" id="bidang_keahlian" name="bidang_keahlian"
                            placeholder="Masukkan Asal Lembaga" value="{{ old('bidang_keahlian') }}"
                            class="w-full border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('bidang_keahlian') border-red-500 @enderror"
                            required />
                        {{-- <option value="teknik-informatika" @if (old('bidang_keahlian') == 'teknik-informatika') selected @endif>Teknik Informatika</option> --}}
                        {{-- (Tambahkan opsi lain di sini) --}}
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
                            <option value="X" @if (old('kelas') == 'X') selected @endif>Kelas X</option>
                            <option value="XI" @if (old('kelas') == 'XI') selected @endif>Kelas XI</option>
                            <option value="XII" @if (old('kelas') == 'XII') selected @endif>Kelas XII</option>
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


            {{-- pelatihan yang ingin diikuti --}}
            <div class="grid grid-cols-1  gap-6">
                <label for="pelatihan_id" class="block text-sm font-semibold mb-2 text-slate-700">Pelatihan yang ingin
                    diikuti</label>
                <div class="relative">
                    <select id="pelatihan_id" name="pelatihan_id"
                        class="w-full border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('pelatihan_id') border-red-500 @enderror"
                        required>
                        <option value="">Pilih Pelatihan</option>
                        @foreach ($bidang as $b)
                            <option value="{{ $b->id }}" @if (old('pelatihan_id') == $b->id) selected @endif>
                                {{ $b->bidang->nama_bidang }}</option>
                        @endforeach
                        {{-- <option value="teknik-informatika" @if (old('pelatihan_id') == 'teknik-informatika') selected @endif>Teknik Informatika</option> --}}
                        {{-- (Tambahkan opsi lain di sini) --}}
                    </select>
                    <div id="pelatihan_idError"
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
                @error('pelatihan_id')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
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
                            <option value="{{ $cb->id }}" @if (old('cabang_dinas_wilayah') == '{{ $cb->id }}') selected @endif>
                                {{ $cb->nama }}</option>
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
                {{-- <a href="{{ route('pendaftaran.create') }}" class="text-sm font-semibold text-slate-600 hover:text-slate-800 transition-colors">
                &larr; Kembali
            </a> --}}
                <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-8 py-2.5 rounded-lg shadow-md transition-all duration-300 transform hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Selanjutnya
                </button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
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
        });
    </script>
