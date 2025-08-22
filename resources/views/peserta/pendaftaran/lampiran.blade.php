@extends('peserta.layout.main', ['currentStep' => 3])

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
                    {{-- Input File KTP --}}
                    <div>
                        <label for="fc_ktp" class="block text-sm font-semibold mb-2 text-slate-700">Unggah Fotocopy
                            KTP / KK</label>
                        <div class="relative">
                            <div class="file-input-wrapper border border-gray-300 rounded-lg">
                                <input type="file" id="fc_ktp" name="fc_ktp"
                                    class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                                    accept=".pdf,.jpg,.jpeg,.png" required onchange="handleFileSelect(this)">
                                <div class="flex items-center justify-between px-4 py-2.5">
                                    <span id="fc_ktp-status" class="text-sm text-gray-500 truncate">Tidak ada file
                                        dipilih</span>
                                    <div
                                        class="px-3 py-1 bg-slate-100 text-slate-700 text-sm font-semibold rounded-md pointer-events-none">
                                        Pilih File</div>
                                </div>
                            </div>
                            <div id="fc_ktpError"
                                class="error-popup absolute bottom-full mb-2 w-full p-2 bg-red-600 text-white text-sm rounded-md shadow-lg flex items-center">
                                <svg class="h-5 w-5 mr-2 shrink-0" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                        clip-rule="evenodd" />
                                </svg>
                                <span class="error-message-text"></span>
                            </div>
                        </div>
                        <p class="text-xs text-slate-500 mt-1">Dapat menggunakan Kartu Keluarga (KK) apabila belum memiliki KTP</p>
                        @error('fc_ktp')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Input File Ijazah --}}
                    <div>
                        <label for="fc_ijazah" class="block text-sm font-semibold mb-2 text-slate-700">Unggah Fotocopy
                            Ijazah</label>
                        <div class="relative">
                            <div class="file-input-wrapper border border-gray-300 rounded-lg">
                                <input type="file" id="fc_ijazah" name="fc_ijazah"
                                    class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                                    accept=".pdf,.jpg,.jpeg,.png" required onchange="handleFileSelect(this)">
                                <div class="flex items-center justify-between px-4 py-2.5">
                                    <span id="fc_ijazah-status" class="text-sm text-gray-500 truncate">Tidak ada file
                                        dipilih</span>
                                    <div
                                        class="px-3 py-1 bg-slate-100 text-slate-700 text-sm font-semibold rounded-md pointer-events-none">
                                        Pilih File</div>
                                </div>
                            </div>
                            <div id="fc_ijazahError"
                                class="error-popup absolute bottom-full mb-2 w-full p-2 bg-red-600 text-white text-sm rounded-md shadow-lg flex items-center">
                                <svg class="h-5 w-5 mr-2 shrink-0" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                        clip-rule="evenodd" />
                                </svg>
                                <span class="error-message-text"></span>
                            </div>
                        </div>
                        @error('fc_ijazah')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Baris 2: Surat Tugas & Surat Sehat --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Input File Surat Tugas --}}
                    <div>
                        <label for="fc_surat_tugas" class="block text-sm font-semibold mb-2 text-slate-700">Unggah Fotocopy
                            Surat Tugas</label>
                        <div class="relative">
                            <div class="file-input-wrapper border border-gray-300 rounded-lg">
                                <input type="file" id="fc_surat_tugas" name="fc_surat_tugas"
                                    class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                                    accept=".pdf,.jpg,.jpeg,.png" onchange="handleFileSelect(this)">
                                <div class="flex items-center justify-between px-4 py-2.5">
                                    <span id="fc_surat_tugas-status" class="text-sm text-gray-500 truncate">Tidak ada file
                                        dipilih</span>
                                    <div
                                        class="px-3 py-1 bg-slate-100 text-slate-700 text-sm font-semibold rounded-md pointer-events-none">
                                        Pilih File</div>
                                </div>
                            </div>
                            <div id="fc_surat_tugasError"
                                class="error-popup absolute bottom-full mb-2 w-full p-2 bg-red-600 text-white text-sm rounded-md shadow-lg flex items-center">
                                <svg class="h-5 w-5 mr-2 shrink-0" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                        clip-rule="evenodd" />
                                </svg>
                                <span class="error-message-text"></span>
                            </div>
                        </div>
                        <p class="text-xs text-slate-500 mt-1">Surat Tugas dapat dilampirkan menyusul.</p>
                        @error('fc_surat_tugas')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Input File Surat Sehat --}}
                    <div>
                        <label for="fc_surat_sehat" class="block text-sm font-semibold mb-2 text-slate-700">Unggah Surat
                            Sehat</label>
                        <div class="relative">
                            <div class="file-input-wrapper border border-gray-300 rounded-lg">
                                <input type="file" id="fc_surat_sehat" name="fc_surat_sehat"
                                    class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                                    accept=".pdf,.jpg,.jpeg,.png" required onchange="handleFileSelect(this)">
                                <div class="flex items-center justify-between px-4 py-2.5">
                                    <span id="fc_surat_sehat-status" class="text-sm text-gray-500 truncate">Tidak ada file
                                        dipilih</span>
                                    <div
                                        class="px-3 py-1 bg-slate-100 text-slate-700 text-sm font-semibold rounded-md pointer-events-none">
                                        Pilih File</div>
                                </div>
                            </div>
                            <div id="fc_surat_sehatError"
                                class="error-popup absolute bottom-full mb-2 w-full p-2 bg-red-600 text-white text-sm rounded-md shadow-lg flex items-center">
                                <svg class="h-5 w-5 mr-2 shrink-0" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                        clip-rule="evenodd" />
                                </svg>
                                <span class="error-message-text"></span>
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
                        <label for="pas_foto" class="block text-sm font-semibold mb-2 text-slate-700">Unggah Pas Foto
                            (3x4)</label>
                        <div class="relative">
                            <div class="file-input-wrapper border border-gray-300 rounded-lg">
                                <input type="file" id="pas_foto" name="pas_foto"
                                    class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                                    accept=".jpg,.jpeg,.png" required onchange="handleFileSelect(this)">
                                <div class="flex items-center justify-between px-4 py-2.5">
                                    <span id="pas_foto-status" class="text-sm text-gray-500 truncate">Tidak ada file
                                        dipilih</span>
                                    <div
                                        class="px-3 py-1 bg-slate-100 text-slate-700 text-sm font-semibold rounded-md pointer-events-none">
                                        Pilih File</div>
                                </div>
                            </div>
                            <div id="pas_fotoError"
                                class="error-popup absolute bottom-full mb-2 w-full p-2 bg-red-600 text-white text-sm rounded-md shadow-lg flex items-center">
                                <svg class="h-5 w-5 mr-2 shrink-0" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                        clip-rule="evenodd" />
                                </svg>
                                <span class="error-message-text"></span>
                            </div>
                        </div>
                        <p class="text-xs text-slate-500 mt-1">Latar belakang merah, format JPG/PNG.</p>
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
                const maxSize = 5 * 1024 * 1024; // 5MB

                // Convert mime types for validation
                const mimeTypeMap = {
                    '.pdf': 'application/pdf',
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
                    showError(inputElement, 'Ukuran file maksimal 5MB.');
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
    });
</script>
