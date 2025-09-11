<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>

    <div class="bg-white rounded-xl shadow-sm p-6 sm:p-10 border border-slate-200">
        <div class="max-w-lg mx-auto text-center">
            {{-- Ilustrasi Sukses --}}
            <div class="mb-6">
                <svg class="w-24 h-24 mx-auto text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>

            {{-- Judul Utama --}}
            <h1 class="text-2xl sm:text-3xl font-bold text-slate-800 mb-3">
                Pendaftaran Berhasil Terkirim!
            </h1>

            {{-- Deskripsi dan Langkah Selanjutnya --}}
            <p class="text-slate-600 text-sm sm:text-base leading-relaxed mb-8">
                Terima kasih telah melakukan pendaftaran pelatihan di UPT PTKK Dinas Pendidikan Jawa Timur. Data Anda
                telah kami terima dan akan segera kami proses untuk verifikasi.
            </p>

            <div class="bg-blue-50 border-l-4 border-blue-500 text-blue-800 p-4 rounded-r-lg text-left text-sm">
                <h4 class="font-bold mb-1">Apa langkah selanjutnya?</h4>
                <p>
                    Mohon untuk memeriksa email Anda secara berkala. Informasi lebih lanjut mengenai jadwal pelatihan
                    dan tautan undangan grup WhatsApp akan kami kirimkan ke alamat email yang telah Anda daftarkan.
                </p>
            </div>

            <p>Nomor Registrasi: <strong>{{ $pendaftaran->nomor_registrasi }}</strong></p>
            <p>Nama Peserta: {{ $pendaftaran->peserta->nama }}</p>
            <p>Pelatihan: {{ $pendaftaran->pelatihan->nama_pelatihan }}</p>
            {{-- <p>Bidang: {{ $pendaftaran->bidang->nama }}</p> --}}

            {{-- Tombol Kembali ke Beranda --}}
            <div class="mt-10">
                <a href="{{ url('/') }}"
                    class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-semibold px-8 py-3 rounded-lg shadow-md transition-all duration-300 transform hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Kembali ke Beranda
                </a>
            </div>
        </div>
    </div>
    <script src="https://cdn.tailwindcss.com"></script>
</body>

</html>
