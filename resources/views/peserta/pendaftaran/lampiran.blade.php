@extends('peserta.pendaftaran.layout.main', ['currentStep' => 3])

@section('title', 'Lampiran Dokumen Pendaftaran')

@section('content')
    <div class="bg-white rounded-xl shadow-sm p-6 sm:p-8 border border-slate-200">

        <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-6" role="alert">
            <div class="flex items-center">
                <div class="py-1">
                    <svg class="h-6 w-6 text-blue-500 mr-3 shrink-0" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                            clip-rule="evenodd" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-blue-800">
                        <strong>Penting:</strong> Pastikan ukuran setiap file yang diunggah <strong>di bawah 2MB</strong>.
                    </p>
                </div>
            </div>
        </div>

        <form id="lampiranForm" action="{{ route('pendaftaran.store') }}" method="POST" enctype="multipart/form-data"
            novalidate>
            @csrf
            {{-- Input hidden untuk memberitahu controller ini adalah langkah ke-3 --}}
            <input type="hidden" name="current_step" value="3">


            <div class="space-y-6">
                {{-- Baris 1: KTP & Ijazah --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    {{-- =================================================== --}}
                    {{-- Input File KTP (Struktur Baru) --}}
                    {{-- =================================================== --}}
                    <div>
                        <label for="fc_ktp" class="block text-sm font-semibold mb-2 text-slate-700">Unggah Fotocopy
                            KTP/KK</label>

                        <div id="fc_ktp-component">
                            <input type="file" id="fc_ktp" name="fc_ktp" class="hidden" accept=".jpg,.jpeg,.png"
                                required>

                            <div id="fc_ktp-default">
                                <label for="fc_ktp"
                                    class="flex flex-col items-center justify-center w-full h-48 border-2 border-dashed border-gray-300 rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100">
                                    <svg class="w-10 h-10 mb-3 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 16a4 4 0 01-4-4V6a4 4 0 014-4h10a4 4 0 014 4v6a4 4 0 01-4 4H7z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M10 9v6m3-3H7"></path>
                                    </svg>
                                    <p class="mb-2 text-sm text-gray-500"><span class="font-semibold">Klik untuk
                                            unggah</span></p>
                                    <p class="text-xs text-gray-500">JPG atau PNG (MAX. 2MB)</p>
                                </label>
                            </div>

                            <div id="fc_ktp-image-preview"
                                class="hidden relative w-full h-48 border border-gray-200 rounded-lg p-2 bg-slate-50">
                                <img id="fc_ktp-preview-image" src="" alt="Pratinjau Gambar"
                                    class="w-full h-full object-contain">
                                <button type="button" id="fc_ktp-remove-btn-image"
                                    class="absolute top-2 right-2 bg-white bg-opacity-70 rounded-full p-1.5 text-red-500 hover:bg-opacity-100">&times;</button>
                            </div>

                            <div id="fc_ktp-file-preview"
                                class="hidden flex items-center justify-between w-full h-48 border border-gray-300 rounded-lg p-4 bg-slate-50">
                                <div class="flex items-center gap-4 truncate">
                                    <svg class="w-12 h-12 text-gray-500 shrink-0" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                        </path>
                                    </svg>
                                    <div class="truncate">
                                        <p id="fc_ktp-preview-filename" class="font-semibold text-slate-700 truncate"></p>
                                        <p id="fc_ktp-preview-filesize" class="text-sm text-slate-500"></p>
                                    </div>
                                </div>
                                <button type="button" id="fc_ktp-remove-btn-file"
                                    class="p-1.5 text-red-500 shrink-0">&times;</button>
                            </div>
                        </div>

                        <p class="text-xs text-slate-500 mt-1">Dapat menggunakan Kartu Keluarga (KK) apabila belum memiliki
                            KTP</p>
                        @error('fc_ktp')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- =================================================== --}}
                    {{-- Input File Ijazah (Struktur Baru) --}}
                    {{-- =================================================== --}}
                    <div>
                        <label for="fc_ijazah" class="block text-sm font-semibold mb-2 text-slate-700">Unggah Fotocopy
                            Ijazah</label>

                        <div id="fc_ijazah-component">
                            <input type="file" id="fc_ijazah" name="fc_ijazah" class="hidden"
                                accept=".jpg,.jpeg,.png" required>

                            <div id="fc_ijazah-default">
                                <label for="fc_ijazah"
                                    class="flex flex-col items-center justify-center w-full h-48 border-2 border-dashed border-gray-300 rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100">
                                    <svg class="w-10 h-10 mb-3 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 16a4 4 0 01-4-4V6a4 4 0 014-4h10a4 4 0 014 4v6a4 4 0 01-4 4H7z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M10 9v6m3-3H7"></path>
                                    </svg>
                                    <p class="mb-2 text-sm text-gray-500"><span class="font-semibold">Klik untuk
                                            unggah</span></p>
                                    <p class="text-xs text-gray-500"> JPG atau PNG (MAX. 2MB)</p>
                                </label>
                            </div>

                            <div id="fc_ijazah-image-preview"
                                class="hidden relative w-full h-48 border border-gray-200 rounded-lg p-2 bg-slate-50">
                                <img id="fc_ijazah-preview-image" src="" alt="Pratinjau Gambar"
                                    class="w-full h-full object-contain">
                                <button type="button" id="fc_ijazah-remove-btn-image"
                                    class="absolute top-2 right-2 bg-white bg-opacity-70 rounded-full p-1.5 text-red-500 hover:bg-opacity-100">&times;</button>
                            </div>

                            <div id="fc_ijazah-file-preview"
                                class="hidden flex items-center justify-between w-full h-48 border border-gray-300 rounded-lg p-4 bg-slate-50">
                                <div class="flex items-center gap-4 truncate">
                                    <svg class="w-12 h-12 text-gray-500 shrink-0" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                        </path>
                                    </svg>
                                    <div class="truncate">
                                        <p id="fc_ijazah-preview-filename" class="font-semibold text-slate-700 truncate">
                                        </p>
                                        <p id="fc_ijazah-preview-filesize" class="text-sm text-slate-500"></p>
                                    </div>
                                </div>
                                <button type="button" id="fc_ijazah-remove-btn-file"
                                    class="p-1.5 text-red-500 shrink-0">&times;</button>
                            </div>
                        </div>

                        @error('fc_ijazah')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Baris 2: Surat Tugas & Surat Sehat --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- =================================================== --}}
                    {{-- Input File Surat Tugas (Struktur Baru) --}}
                    {{-- =================================================== --}}
                    <div>
                        <label for="fc_surat_tugas" class="block text-sm font-semibold mb-2 text-slate-700">Unggah
                            Fotocopy Surat Tugas</label>

                        <div id="fc_surat_tugas-component">
                            <input type="file" id="fc_surat_tugas" name="fc_surat_tugas" class="hidden"
                                accept=".jpg,.jpeg,.png">

                            <div id="fc_surat_tugas-default">
                                <label for="fc_surat_tugas"
                                    class="flex flex-col items-center justify-center w-full h-48 border-2 border-dashed border-gray-300 rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100">
                                    <svg class="w-10 h-10 mb-3 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 16a4 4 0 01-4-4V6a4 4 0 014-4h10a4 4 0 014 4v6a4 4 0 01-4 4H7z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M10 9v6m3-3H7"></path>
                                    </svg>
                                    <p class="mb-2 text-sm text-gray-500"><span class="font-semibold">Klik untuk
                                            unggah</span></p>
                                    <p class="text-xs text-gray-500">JPG atau PNG (MAX. 2MB)</p>
                                </label>
                            </div>

                            <div id="fc_surat_tugas-image-preview"
                                class="hidden relative w-full h-48 border border-gray-200 rounded-lg p-2 bg-slate-50">
                                <img id="fc_surat_tugas-preview-image" src="" alt="Pratinjau Gambar"
                                    class="w-full h-full object-contain">
                                <button type="button" id="fc_surat_tugas-remove-btn-image"
                                    class="absolute top-2 right-2 bg-white bg-opacity-70 rounded-full p-1.5 text-red-500 hover:bg-opacity-100">&times;</button>
                            </div>

                            <div id="fc_surat_tugas-file-preview"
                                class="hidden flex items-center justify-between w-full h-48 border border-gray-300 rounded-lg p-4 bg-slate-50">
                                <div class="flex items-center gap-4 truncate">
                                    <svg class="w-12 h-12 text-gray-500 shrink-0" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                        </path>
                                    </svg>
                                    <div class="truncate">
                                        <p id="fc_surat_tugas-preview-filename"
                                            class="font-semibold text-slate-700 truncate"></p>
                                        <p id="fc_surat_tugas-preview-filesize" class="text-sm text-slate-500"></p>
                                    </div>
                                </div>
                                <button type="button" id="fc_surat_tugas-remove-btn-file"
                                    class="p-1.5 text-red-500 shrink-0">&times;</button>
                            </div>
                        </div>

                        <p class="text-xs text-slate-500 mt-1">Surat Tugas dapat dilampirkan menyusul.</p>
                        @error('fc_surat_tugas')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    TODO: if else kalau semisal jenis_program MTU, gapake surat sehat
                    DONE:  
                    {{-- =================================================== --}}
                    {{-- Input File Surat Sehat (Struktur Baru) --}}
                    {{-- =================================================== --}}
                    <div>
                        @if ()

                        @endif
                        <label for="fc_surat_sehat" class="block text-sm font-semibold mb-2 text-slate-700">Unggah Surat
                            Sehat</label>

                        <div id="fc_surat_sehat-component">
                            <input type="file" id="fc_surat_sehat" name="fc_surat_sehat" class="hidden"
                                accept=".jpg,.jpeg,.png" required>

                            <div id="fc_surat_sehat-default">
                                <label for="fc_surat_sehat"
                                    class="flex flex-col items-center justify-center w-full h-48 border-2 border-dashed border-gray-300 rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100">
                                    <svg class="w-10 h-10 mb-3 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 16a4 4 0 01-4-4V6a4 4 0 014-4h10a4 4 0 014 4v6a4 4 0 01-4 4H7z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M10 9v6m3-3H7"></path>
                                    </svg>
                                    <p class="mb-2 text-sm text-gray-500"><span class="font-semibold">Klik untuk
                                            unggah</span></p>
                                    <p class="text-xs text-gray-500"> JPG, atau PNG (MAX. 2MB)</p>
                                </label>
                            </div>

                            <div id="fc_surat_sehat-image-preview"
                                class="hidden relative w-full h-48 border border-gray-200 rounded-lg p-2 bg-slate-50">
                                <img id="fc_surat_sehat-preview-image" src="" alt="Pratinjau Gambar"
                                    class="w-full h-full object-contain">
                                <button type="button" id="fc_surat_sehat-remove-btn-image"
                                    class="absolute top-2 right-2 bg-white bg-opacity-70 rounded-full p-1.5 text-red-500 hover:bg-opacity-100">&times;</button>
                            </div>

                            <div id="fc_surat_sehat-file-preview"
                                class="hidden flex items-center justify-between w-full h-48 border border-gray-300 rounded-lg p-4 bg-slate-50">
                                <div class="flex items-center gap-4 truncate">
                                    <svg class="w-12 h-12 text-gray-500 shrink-0" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                        </path>
                                    </svg>
                                    <div class="truncate">
                                        <p id="fc_surat_sehat-preview-filename"
                                            class="font-semibold text-slate-700 truncate"></p>
                                        <p id="fc_surat_sehat-preview-filesize" class="text-sm text-slate-500"></p>
                                    </div>
                                </div>
                                <button type="button" id="fc_surat_sehat-remove-btn-file"
                                    class="p-1.5 text-red-500 shrink-0">&times;</button>
                            </div>
                        </div>

                        @error('fc_surat_sehat')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Baris 3: Nomor Surat & Pas Foto --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Input Nomor Surat Tugas --}}
                    <div>
                        <label for="no_surat_tugas" class="block text-sm font-semibold mb-2 text-slate-700">Nomor Surat
                            Tugas</label>
                        <div class="relative">
                            <input type="text" id="no_surat_tugas" name="no_surat_tugas"
                                placeholder="Masukkan Nomor Surat" value="{{ old('no_surat_tugas') }}"
                                class="w-full border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('no_surat_tugas') border-red-500 @enderror">
                            <div id="no_surat_tugasError"
                                class="error-popup absolute bottom-full mb-2 w-full p-2 bg-red-600 text-white text-sm rounded-md shadow-lg flex items-center">
                                <svg class="h-5 w-5 mr-2 shrink-0" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                        clip-rule="evenodd" />
                                </svg>
                                <span class="error-message-text"></span>
                            </div>
                        </div>
                        <p class="text-xs text-slate-500 mt-1">Nomor Surat Tugas dapat dilampirkan menyusul.</p>
                        @error('no_surat_tugas')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Input Pas Foto --}}
                    <div>
                        <label for="pas_foto" class="block text-sm font-semibold mb-2 text-slate-700">Pas Foto</label>

                        <div id="pas_foto-component">
                            <input type="file" id="pas_foto" name="pas_foto" class="hidden"
                                accept=".jpg,.jpeg,.png" required>

                            <div id="pas_foto-default">
                                <label for="pas_foto"
                                    class="flex flex-col items-center justify-center w-full h-48 border-2 border-dashed border-gray-300 rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100">
                                    <svg class="w-10 h-10 mb-3 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 16a4 4 0 01-4-4V6a4 4 0 014-4h10a4 4 0 014 4v6a4 4 0 01-4 4H7z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M10 9v6m3-3H7"></path>
                                    </svg>
                                    <p class="mb-2 text-sm text-gray-500"><span class="font-semibold">Klik untuk
                                            unggah</span></p>
                                    <p class="text-xs text-gray-500"> JPG atau PNG (MAX. 5MB)</p>
                                </label>
                            </div>

                            <div id="pas_foto-image-preview"
                                class="hidden relative w-full h-48 border border-gray-200 rounded-lg p-2 bg-slate-50">
                                <img id="pas_foto-preview-image" src="" alt="Pratinjau Gambar"
                                    class="w-full h-full object-contain">
                                <button type="button" id="pas_foto-remove-btn-image"
                                    class="absolute top-2 right-2 bg-white bg-opacity-70 rounded-full p-1.5 text-red-500 hover:bg-opacity-100">&times;</button>
                            </div>

                            <div id="pas_foto-file-preview"
                                class="hidden flex items-center justify-between w-full h-48 border border-gray-300 rounded-lg p-4 bg-slate-50">
                                <div class="flex items-center gap-4 truncate">
                                    <svg class="w-12 h-12 text-gray-500 shrink-0" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                        </path>
                                    </svg>
                                    <div class="truncate">
                                        <p id="pas_foto-preview-filename" class="font-semibold text-slate-700 truncate">
                                        </p>
                                        <p id="pas_foto-preview-filesize" class="text-sm text-slate-500"></p>
                                    </div>
                                </div>
                                <button type="button" id="pas_foto-remove-btn-file"
                                    class="p-1.5 text-red-500 shrink-0">&times;</button>
                            </div>
                        </div>

                        @error('pas_foto')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Tombol Navigasi --}}
            <div class="flex justify-between items-center pt-6">
                <a href="{{ route('pendaftaran.create', ['step' => 2]) }}"
                    class="text-sm font-semibold text-slate-600 hover:text-slate-800 transition-colors">
                    &larr; Kembali
                </a>
                <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-8 py-2.5 rounded-lg shadow-md transition-all duration-300 transform hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Kirim Pendaftaran
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

    .file-input-wrapper.border-red-500 {
        border-color: #ef4444;
        /* red-500 */
    }
</style>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const form = document.getElementById('lampiranForm');
        if (!form) return;

        const showError = (element, message) => {
            const errorPopup = document.getElementById(element.id + 'Error');
            if (errorPopup) {
                errorPopup.querySelector('.error-message-text').textContent = message;
                errorPopup.classList.add('visible');
                const wrapper = element.closest('.file-input-wrapper');
                if (wrapper) {
                    wrapper.classList.add('border-red-500');
                } else {
                    element.classList.add('border-red-500', 'focus:ring-red-500');
                }
            }
        };

        const hideError = (element) => {
            const errorPopup = document.getElementById(element.id + 'Error');
            if (errorPopup) {
                errorPopup.classList.remove('visible');
                const wrapper = element.closest('.file-input-wrapper');
                if (wrapper) {
                    wrapper.classList.remove('border-red-500');
                } else {
                    element.classList.remove('border-red-500', 'focus:ring-red-500');
                }
            }
        };

        window.handleFileSelect = function(inputElement) {
            const statusSpan = document.getElementById(inputElement.id + '-status');
            const file = inputElement.files[0];
            hideError(inputElement);

            if (file) {
                const allowedTypes = inputElement.accept.split(',').map(t => t.trim());
                const maxSize = 5 * 1024 * 1024; // 2MB

                // Convert mime types for validation
                const mimeTypeMap = {
                    '.jpg': 'image/jpeg',
                    '.jpeg': 'image/jpeg',
                    '.png': 'image/png'
                };
                const validMimeTypes = allowedTypes.map(type => mimeTypeMap[type]).filter(Boolean);

                if (validMimeTypes.length > 0 && !validMimeTypes.includes(file.type)) {
                    showError(inputElement, `Format file tidak valid. Gunakan: ${allowedTypes.join(', ')}`);
                    inputElement.value = '';
                    statusSpan.textContent = 'Tidak ada file dipilih';
                    return;
                }
                if (file.size > maxSize) {
                    showError(inputElement, 'Ukuran file maksimal 2MB.');
                    inputElement.value = '';
                    statusSpan.textContent = 'Tidak ada file dipilih';
                    return;
                }
                statusSpan.textContent = `✅ ${file.name}`;
                statusSpan.classList.add('text-green-600', 'font-medium');
            } else {
                statusSpan.textContent = 'Tidak ada file dipilih';
                statusSpan.classList.remove('text-green-600', 'font-medium');
            }
        }

        form.querySelectorAll('[required]').forEach(element => {
            element.addEventListener('input', () => hideError(element));
            element.addEventListener('change', () => hideError(element));
        });

        form.addEventListener('submit', function(event) {
            form.querySelectorAll('[required]').forEach(hideError);
            let firstErrorElement = null;

            for (const element of form.elements) {
                if (element.hasAttribute('required')) {
                    let isValid = element.type === 'file' ? element.files.length > 0 : element.validity
                        .valid;
                    if (!isValid) {
                        event.preventDefault();
                        let message = 'Wajib diisi';
                        switch (element.id) {
                            case 'fc_ktp':
                                message = 'File KTP wajib diunggah.';
                                break;
                            case 'fc_ijazah':
                                message = 'File Ijazah wajib diunggah.';
                                break;
                                // case 'fc_surat_tugas':
                                //     message = 'File Surat Tugas wajib diunggah.';
                                //     break;
                            case 'fc_surat_sehat':
                                message = 'File Surat Sehat wajib diunggah.';
                                break;
                            case 'pas_foto':
                                message = 'Pas Foto wajib diunggah.';
                                break;
                                // case 'no_surat_tugas':
                                //     message = 'Nomor surat tugas tidak boleh kosong.';
                                //     break;
                        }
                        showError(element, message);
                        if (!firstErrorElement) firstErrorElement = element;
                    }
                }
            }
            if (firstErrorElement) firstErrorElement.focus();
        });


        // Fungsi "template" untuk membuat setiap komponen upload bekerja.
        function setupFilePreview(inputId) {
            const fileInput = document.getElementById(inputId);
            if (!fileInput) return;

            // Ambil semua elemen yang berhubungan dengan komponen ini
            const defaultView = document.getElementById(`${inputId}-default`);
            const imagePreviewView = document.getElementById(`${inputId}-image-preview`);
            const filePreviewView = document.getElementById(`${inputId}-file-preview`);

            const previewImage = document.getElementById(`${inputId}-preview-image`);
            const previewFilename = document.getElementById(`${inputId}-preview-filename`);
            const previewFilesize = document.getElementById(`${inputId}-preview-filesize`);

            const removeBtnImage = document.getElementById(`${inputId}-remove-btn-image`);
            const removeBtnFile = document.getElementById(`${inputId}-remove-btn-file`);

            const resetComponent = () => {
                fileInput.value = ''; // Reset input file
                // Sembunyikan semua tampilan pratinjau dan tampilkan yang default
                imagePreviewView.classList.add('hidden');
                filePreviewView.classList.add('hidden');
                defaultView.classList.remove('hidden');
            };

            removeBtnImage.addEventListener('click', resetComponent);
            removeBtnFile.addEventListener('click', resetComponent);

            fileInput.addEventListener('change', function(event) {
                const file = event.target.files[0];
                if (!file) return;

                // Sembunyikan tampilan default
                defaultView.classList.add('hidden');

                // Cek apakah file adalah gambar
                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        previewImage.src = e.target.result;
                        imagePreviewView.classList.remove('hidden'); // Tampilkan pratinjau gambar
                    };
                    reader.readAsDataURL(file);
                } else {
                    // Jika bukan gambar (misal: PDF)
                    previewFilename.textContent = file.name;
                    previewFilesize.textContent = formatBytes(file.size);
                    filePreviewView.classList.remove('hidden'); // Tampilkan pratinjau file
                }
            });
        }

        // --- INISIALISASI ---
        // Panggil fungsi untuk setiap komponen upload yang ada di form Anda.
        setupFilePreview('fc_ktp');
        setupFilePreview('fc_ijazah');
        // Tambahkan lagi di sini jika ada input file lain

        // Tambahkan 2 baris ini untuk baris 2
        setupFilePreview('fc_surat_tugas');
        setupFilePreview('fc_surat_sehat');

        setupFilePreview('pas_foto');
    });
</script>
