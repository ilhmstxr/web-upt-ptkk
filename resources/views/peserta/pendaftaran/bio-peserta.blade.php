@extends('peserta.layout.main', ['currentStep' => 1])

@section('title', 'Biodata Peserta')

{{-- CSS untuk animasi dan tampilan pop-up error di samping label --}}
<style>
    .error-popup {
        opacity: 0;
        transform: scale(0.95) translateX(5px);
        transition: opacity 0.2s ease-out, transform 0.2s ease-out;
    }
    .error-popup:not(.hidden) {
        opacity: 1;
        transform: scale(1) translateX(0);
    }
</style>

@section('content')
<div class="bg-white rounded-xl shadow-sm p-6 sm:p-8 border border-slate-200">
    <form id="registrationForm" action="{{ route('pendaftaran.store') }}" method="POST" class="space-y-6" novalidate>
        @csrf

        {{-- Nama dan NIK --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-2">
            <div>
                <div class="flex items-center justify-between mb-2">
                    <label for="nama" class="block text-sm font-semibold text-slate-700">Nama</label>
                    {{-- Pop-up ini sekarang menampilkan error dari server-side & client-side --}}
                    <div id="namaError" class="error-popup @if(!$errors->has('nama')) hidden @endif p-1.5 bg-red-100 text-red-700 text-xs rounded-md shadow-sm flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.21 3.03-1.742 3.03H4.42c-1.532 0-2.492-1.696-1.742-3.03l5.58-9.92zM9 11a1 1 0 112 0v1a1 1 0 11-2 0v-1zm1-4a1 1 0 00-1 1v2a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" /></svg>
                        <span class="error-message-text">@error('nama'){{ $message }}@enderror</span>
                    </div>
                </div>
                <input type="text" id="nama" name="nama" placeholder="Masukkan Nama Lengkap" value="{{ old('nama') }}" class="w-full border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('nama') border-red-500 @enderror" required />
            </div>
            <div>
                <div class="flex items-center justify-between mb-2">
                    <label for="nik" class="block text-sm font-semibold text-slate-700">NIK (16 digit)</label>
                    <div id="nikError" class="error-popup @if(!$errors->has('nik')) hidden @endif p-1.5 bg-red-100 text-red-700 text-xs rounded-md shadow-sm flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.21 3.03-1.742 3.03H4.42c-1.532 0-2.492-1.696-1.742-3.03l5.58-9.92zM9 11a1 1 0 112 0v1a1 1 0 11-2 0v-1zm1-4a1 1 0 00-1 1v2a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" /></svg>
                        <span class="error-message-text">@error('nik'){{ $message }}@enderror</span>
                    </div>
                </div>
                <input type="text" id="nik" name="nik" maxlength="16" placeholder="Masukkan 16 digit NIK" value="{{ old('nik') }}" pattern="\d{16}" title="NIK harus terdiri dari 16 digit angka." oninput="this.value = this.value.replace(/[^0-9]/g, '');" class="w-full border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('nik') border-red-500 @enderror" required />
            </div>
        </div>

        {{-- Tempat dan Tanggal Lahir --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-2">
            <div>
                <div class="flex items-center justify-between mb-2">
                    <label for="tempat_lahir" class="block text-sm font-semibold text-slate-700">Tempat Lahir</label>
                    <div id="tempat_lahirError" class="error-popup @if(!$errors->has('tempat_lahir')) hidden @endif p-1.5 bg-red-100 text-red-700 text-xs rounded-md shadow-sm flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.21 3.03-1.742 3.03H4.42c-1.532 0-2.492-1.696-1.742-3.03l5.58-9.92zM9 11a1 1 0 112 0v1a1 1 0 11-2 0v-1zm1-4a1 1 0 00-1 1v2a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" /></svg>
                        <span class="error-message-text">@error('tempat_lahir'){{ $message }}@enderror</span>
                    </div>
                </div>
                <input type="text" id="tempat_lahir" name="tempat_lahir" placeholder="Masukkan Tempat Lahir" value="{{ old('tempat_lahir') }}" class="w-full border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('tempat_lahir') border-red-500 @enderror" required />
            </div>
            <div>
                <div class="flex items-center justify-between mb-2">
                    <label for="tanggal_lahir" class="block text-sm font-semibold text-slate-700">Tanggal Lahir</label>
                    <div id="tanggal_lahirError" class="error-popup @if(!$errors->has('tanggal_lahir')) hidden @endif p-1.5 bg-red-100 text-red-700 text-xs rounded-md shadow-sm flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.21 3.03-1.742 3.03H4.42c-1.532 0-2.492-1.696-1.742-3.03l5.58-9.92zM9 11a1 1 0 112 0v1a1 1 0 11-2 0v-1zm1-4a1 1 0 00-1 1v2a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" /></svg>
                        <span class="error-message-text">@error('tanggal_lahir'){{ $message }}@enderror</span>
                    </div>
                </div>
                <input type="date" id="tanggal_lahir" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}" class="w-full border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('tanggal_lahir') border-red-500 @enderror" required />
            </div>
        </div>
        
        {{-- Jenis Kelamin dan Agama --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-2">
            <div>
                <div class="flex items-center justify-between mb-2">
                    <label for="jenis_kelamin" class="block text-sm font-semibold text-slate-700">Jenis Kelamin</label>
                    <div id="jenis_kelaminError" class="error-popup @if(!$errors->has('jenis_kelamin')) hidden @endif p-1.5 bg-red-100 text-red-700 text-xs rounded-md shadow-sm flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.21 3.03-1.742 3.03H4.42c-1.532 0-2.492-1.696-1.742-3.03l5.58-9.92zM9 11a1 1 0 112 0v1a1 1 0 11-2 0v-1zm1-4a1 1 0 00-1 1v2a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" /></svg>
                        <span class="error-message-text">@error('jenis_kelamin'){{ $message }}@enderror</span>
                    </div>
                </div>
                <select id="jenis_kelamin" name="jenis_kelamin" class="w-full border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('jenis_kelamin') border-red-500 @enderror" required>
                    <option value="">Pilih Jenis Kelamin</option>
                    <option value="Laki-laki" {{ old('jenis_kelamin') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                    <option value="Perempuan" {{ old('jenis_kelamin') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                </select>
            </div>
            <div>
                <div class="flex items-center justify-between mb-2">
                    <label for="agama" class="block text-sm font-semibold text-slate-700">Agama</label>
                    <div id="agamaError" class="error-popup @if(!$errors->has('agama')) hidden @endif p-1.5 bg-red-100 text-red-700 text-xs rounded-md shadow-sm flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.21 3.03-1.742 3.03H4.42c-1.532 0-2.492-1.696-1.742-3.03l5.58-9.92zM9 11a1 1 0 112 0v1a1 1 0 11-2 0v-1zm1-4a1 1 0 00-1 1v2a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" /></svg>
                        <span class="error-message-text">@error('agama'){{ $message }}@enderror</span>
                    </div>
                </div>
                <select id="agama" name="agama" class="w-full border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('agama') border-red-500 @enderror" required>
                    <option value="">Pilih Agama</option>
                    <option value="Islam" {{ old('agama') == 'Islam' ? 'selected' : '' }}>Islam</option>
                    <option value="Kristen" {{ old('agama') == 'Kristen' ? 'selected' : '' }}>Kristen</option>
                    <option value="Katolik" {{ old('agama') == 'Katolik' ? 'selected' : '' }}>Katolik</option>
                    <option value="Hindu" {{ old('agama') == 'Hindu' ? 'selected' : '' }}>Hindu</option>
                    <option value="Buddha" {{ old('agama') == 'Buddha' ? 'selected' : '' }}>Buddha</option>
                    <option value="Konghucu" {{ old('agama') == 'Konghucu' ? 'selected' : '' }}>Konghucu</option>
                </select>
            </div>
        </div>

        {{-- Alamat --}}
        <div>
            <div class="flex items-center justify-between mb-2">
                <label for="alamat" class="block text-sm font-semibold text-slate-700">Alamat Tempat Tinggal</label>
                <div id="alamatError" class="error-popup @if(!$errors->has('alamat')) hidden @endif p-1.5 bg-red-100 text-red-700 text-xs rounded-md shadow-sm flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.21 3.03-1.742 3.03H4.42c-1.532 0-2.492-1.696-1.742-3.03l5.58-9.92zM9 11a1 1 0 112 0v1a1 1 0 11-2 0v-1zm1-4a1 1 0 00-1 1v2a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" /></svg>
                    <span class="error-message-text">@error('alamat'){{ $message }}@enderror</span>
                </div>
            </div>
            <textarea id="alamat" name="alamat" placeholder="Masukkan alamat lengkap sesuai KTP" rows="4" class="w-full border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('alamat') border-red-500 @enderror" required>{{ old('alamat') }}</textarea>
        </div>
        
        {{-- Nomor Handphone dan Email --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-2">
            <div>
                <div class="flex items-center justify-between mb-2">
                    <label for="no_hp" class="block text-sm font-semibold text-slate-700">Nomor Handphone</label>
                    <div id="no_hpError" class="error-popup @if(!$errors->has('no_hp')) hidden @endif p-1.5 bg-red-100 text-red-700 text-xs rounded-md shadow-sm flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.21 3.03-1.742 3.03H4.42c-1.532 0-2.492-1.696-1.742-3.03l5.58-9.92zM9 11a1 1 0 112 0v1a1 1 0 11-2 0v-1zm1-4a1 1 0 00-1 1v2a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" /></svg>
                        <span class="error-message-text">@error('no_hp'){{ $message }}@enderror</span>
                    </div>
                </div>
                <input type="tel" id="no_hp" name="no_hp" maxlength="15" placeholder="Contoh: 081234567890" value="{{ old('no_hp') }}" oninput="this.value = this.value.replace(/[^0-9]/g, '');" class="w-full border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('no_hp') border-red-500 @enderror" required />
            </div>
            <div>
                <div class="flex items-center justify-between mb-2">
                    <label for="email" class="block text-sm font-semibold text-slate-700">Email</label>
                    <div id="emailError" class="error-popup @if(!$errors->has('email')) hidden @endif p-1.5 bg-red-100 text-red-700 text-xs rounded-md shadow-sm flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.21 3.03-1.742 3.03H4.42c-1.532 0-2.492-1.696-1.742-3.03l5.58-9.92zM9 11a1 1 0 112 0v1a1 1 0 11-2 0v-1zm1-4a1 1 0 00-1 1v2a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" /></svg>
                        <span class="error-message-text">@error('email'){{ $message }}@enderror</span>
                    </div>
                </div>
                <input type="email" id="email" name="email" placeholder="Masukkan Email Aktif" value="{{ old('email') }}" class="w-full border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('email') border-red-500 @enderror" required />
            </div>
        </div>

        {{-- Tombol Submit --}}
        <div class="flex justify-end pt-4">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-8 py-2.5 rounded-lg shadow-md transition-all duration-300 transform hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                Selanjutnya
            </button>
        </div>
    </form>
</div>
@endsection

<script>
document.addEventListener("DOMContentLoaded", function() {
    const form = document.getElementById('registrationForm');
    if (!form) return;

    // Fungsi untuk menampilkan pop-up error
    const showError = (element, message) => {
        const errorPopup = document.getElementById(element.id + 'Error');
        if (errorPopup) {
            errorPopup.querySelector('.error-message-text').textContent = message;
            errorPopup.classList.remove('hidden');
            element.classList.add('border-red-500', 'focus:ring-red-500');
        }
    };

    // Fungsi untuk menyembunyikan pop-up error
    const hideError = (element) => {
        const errorPopup = document.getElementById(element.id + 'Error');
        if (errorPopup) {
            errorPopup.classList.add('hidden');
            element.classList.remove('border-red-500', 'focus:ring-red-500');
        }
    };
    
    const hideAllErrors = () => {
        form.querySelectorAll('input[required], select[required], textarea[required]').forEach(hideError);
    };

    form.querySelectorAll('input[required], select[required], textarea[required]').forEach(element => {
        element.addEventListener('input', () => hideError(element));
    });

    form.addEventListener('submit', function(event) {
        hideAllErrors();
        let firstErrorElement = null;

        for (const element of form.elements) {
            if (element.hasAttribute('required') && !element.validity.valid) {
                event.preventDefault();
                let message = 'Wajib diisi';

                switch (element.id) {
                    case 'nama': message = 'Nama lengkap wajib diisi.'; break;
                    case 'nik':
                        message = element.value.length > 0 ? 'NIK harus 16 digit.' : 'NIK wajib diisi.';
                        break;
                    case 'tempat_lahir': message = 'Tempat lahir wajib diisi.'; break;
                    case 'tanggal_lahir': message = 'Tanggal lahir wajib diisi.'; break;
                    case 'jenis_kelamin': message = 'Pilih jenis kelamin.'; break;
                    case 'agama': message = 'Pilih agama.'; break;
                    case 'alamat': message = 'Alamat wajib diisi.'; break;
                    case 'no_hp': message = 'No. handphone wajib diisi.'; break;
                    case 'email':
                        message = element.validity.typeMismatch ? 'Format email salah.' : 'Email wajib diisi.';
                        break;
                }
                
                showError(element, message);
                if (!firstErrorElement) firstErrorElement = element;
            }
        }

        if (firstErrorElement) firstErrorElement.focus();
    });
});
</script>
