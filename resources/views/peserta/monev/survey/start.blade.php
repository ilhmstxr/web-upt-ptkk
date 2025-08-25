@extends('peserta.monev.layout.main')

@section('title', 'Pendaftaran MONEV Kegiatan Vokasi')

@section('content')
    <div class="text-center mb-8">
        <img src="/images/logo-kementrian pendidikan.png" alt="Logo Kementerian Pendidikan"
            class="mx-auto h-16 sm:h-20 md:h-24 mb-4" onerror="this.style.display='none'">
        <h1 class="text-base sm:text-lg md:text-xl font-bold text-indigo-800 mb-2 leading-relaxed text-center">
            MONEV KEGIATAN PENGEMBANGAN DAN PENINGKATAN KOMPETENSI VOKASI
        </h1>
        <p class="text-indigo-600 text-sm sm:text-base">Silakan masuk dengan Nama dan Email yang terdaftar untuk memulai
            penilaian</p>
    </div>

    <div class="bg-white rounded-xl shadow-lg overflow-hidden p-6 sm:p-8 max-w-xl mx-auto">
        <form action="{{ route('survey.store') }}" method="POST" class="space-y-6">
            @csrf

            <div>
                <label for="nama" class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                    <i class="fas fa-user text-indigo-500 mr-2"></i> Nama Lengkap
                </label>
                <input type="text" id="nama" name="nama" value="{{ old('nama') }}"
                    class="form-input w-full px-4 py-2 sm:py-3 border {{ $errors->has('nama') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:ring-indigo-500 focus:border-indigo-500"
                    required placeholder="Masukkan nama lengkap sesuai registrasi">
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
        document.addEventListener('DOMContentLoaded', function() {
            const nameInput = document.getElementById('nama');
            const emailInput = document.getElementById('email');
            const statusIcon = document.getElementById('status-icon');
            const statusMessage = document.getElementById('status-message');
            const checkUrl = "{{ route('survey.checkCredentials') }}";
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            let debounceTimeout;

            const performCheck = () => {
                const name = nameInput.value.trim();
                const email = emailInput.value.trim();

                if (!name || !email || !email.includes('@')) {
                    statusIcon.innerHTML = '';
                    statusMessage.textContent = '';
                    return;
                }

                statusIcon.innerHTML = '<i class="fas fa-spinner fa-spin text-gray-400"></i>';
                statusMessage.textContent = '';

                fetch(checkUrl, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify({
                            nama: name,
                            email: email
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.exists) {
                            statusIcon.innerHTML = '<i class="fas fa-check-circle text-green-500"></i>';
                            statusMessage.textContent = 'Data peserta ditemukan. Silakan lanjut.';
                            statusMessage.className = 'text-xs mt-1 text-green-600';
                        } else {
                            statusIcon.innerHTML = '<i class="fas fa-times-circle text-red-500"></i>';
                            statusMessage.textContent = 'Kombinasi nama dan email tidak ditemukan.';
                            statusMessage.className = 'text-xs mt-1 text-red-600';
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        statusIcon.innerHTML =
                            '<i class="fas fa-exclamation-triangle text-yellow-500"></i>';
                        statusMessage.textContent = 'Gagal terhubung ke server.';
                        statusMessage.className = 'text-xs mt-1 text-yellow-600';
                    });
            };

            const debounceCheck = () => {
                clearTimeout(debounceTimeout);
                debounceTimeout = setTimeout(performCheck, 500);
            };

            nameInput.addEventListener('input', debounceCheck);
            emailInput.addEventListener('input', debounceCheck);
        });
    </script>
@endpush
