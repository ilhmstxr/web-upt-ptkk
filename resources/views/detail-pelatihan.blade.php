<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pelatihan - {{ $kompetensi }}</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo-upt-ptkk.jpg') }} " class="w-4">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Mengimpor font Inter dari Google Fonts */
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap');

        body {
            font-family: 'Inter', sans-serif;
            background-color: #f3f4f6;
            /* Warna latar belakang abu-abu muda */
        }

        .fade-in {
            animation: fadeIn 1s ease-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        /* Style untuk tombol pendaftaran utama */
        .btn-primary {
            background-color: #5c76c1;
            transition-property: all;
            transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
            transition-duration: 150ms;
        }

        .btn-primary:hover {
            background-color: #4a62a9;
            transform: scale(1.05);
        }
    </style>
</head>

<body class="bg-gray-100 min-h-screen">

    <!-- Header dengan navigasi sederhana -->
    <header class="bg-white shadow-sm p-4 sticky top-0 z-50">
        <div class="container mx-auto flex items-center justify-between">
            <!-- Logo dan nama UPT PTKK -->
            <a href="/" class="flex items-center space-x-4">
                <img src="{{ asset('images/logo-upt-ptkk.jpg') }}" alt="Logo" class="w-8">
                <h1 class="text-xl font-bold text-gray-800">UPT PTKK</h1>
            </a>
            <!-- Tombol untuk mendaftar -->
            <nav>
                <a href="/pendaftaran" class="px-4 py-2 text-white font-semibold rounded-lg shadow-md btn-primary">Daftar Sekarang</a>
            </nav>
        </div>
    </header>

    <!-- Konten Utama Halaman -->
    <main class="container mx-auto p-4 md:p-8">
        @php
        // Array data mock untuk berbagai jenis pelatihan
        $pelatihan = [
        'tata-boga' => [
        'nama' => 'Tata Boga',
        'deskripsi' => 'Pelatihan ini akan membekali Anda dengan keterampilan dasar hingga mahir dalam seni kuliner. Anda akan belajar teknik memasak, manajemen dapur, dan standar kebersihan makanan.',
        'tanggal' => '25 - 30 Agustus 2025',
        'gambar' => 'https://placehold.co/1200x400/5c76c1/ffffff?text=Banner+Tata+Boga',
        'persyaratan' => [
        'Pria/Wanita',
        'Usia 18-35 tahun',
        'Pendidikan minimal SMA/SMK',
        'Fotocopy Ijazah terakhir',
        'Surat Keterangan Sehat',
        'Surat Tugas dari sekolah (jika pendaftar dari sekolah)',
        ],
        ],
        'tata-busana' => [
        'nama' => 'Tata Busana',
        'deskripsi' => 'Pelatihan ini berfokus pada desain dan produksi pakaian. Anda akan menguasai teknik menjahit, membuat pola, dan memahami tren mode terbaru.',
        'tanggal' => '25 - 30 Agustus 2025',
        'gambar' => 'https://placehold.co/1200x400/5c76c1/ffffff?text=Banner+Tata+Busana',
        'persyaratan' => [
        'Pria/Wanita',
        'Usia 18-35 tahun',
        'Pendidikan minimal SMA/SMK',
        'Fotocopy Ijazah terakhir',
        'Surat Keterangan Sehat',
        'Surat Tugas dari sekolah (jika pendaftar dari sekolah)',
        ],
        ],
        'tata-kecantikan' => [
        'nama' => 'Tata Kecantikan',
        'deskripsi' => 'Anda akan mempelajari berbagai teknik kecantikan, mulai dari perawatan kulit, rias wajah, hingga tata rambut. Pelatihan ini menggabungkan teori dan praktik untuk mempersiapkan Anda menjadi profesional di bidang kecantikan.',
        'tanggal' => '25 - 30 Agustus 2025',
        'gambar' => 'https://placehold.co/1200x400/5c76c1/ffffff?text=Banner+Tata+Kecantikan',
        'persyaratan' => [
        'Wanita',
        'Usia 18-35 tahun',
        'Pendidikan minimal SMA/SMK',
        'Fotocopy Ijazah terakhir',
        'Surat Keterangan Sehat',
        'Surat Tugas dari sekolah (jika pendaftar dari sekolah)',
        ],
        ],
        'teknik-pendingin-dan-tata-udara' => [
        'nama' => 'Teknik Pendingin dan Tata Udara',
        'deskripsi' => 'Pelatihan ini mencakup instalasi, pemeliharaan, dan perbaikan sistem pendingin (AC) dan tata udara. Cocok untuk Anda yang ingin berkarir di bidang teknis.',
        'tanggal' => '25 - 30 Agustus 2025',
        'gambar' => 'https://placehold.co/1200x400/5c76c1/ffffff?text=Banner+Teknik+Pendingin',
        'persyaratan' => [
        'Pria',
        'Usia 18-35 tahun',
        'Pendidikan minimal SMA/SMK',
        'Fotocopy Ijazah terakhir',
        'Surat Keterangan Sehat',
        'Surat Tugas dari sekolah (jika pendaftar dari sekolah)',
        ],
        ],
        ];
        // Mengambil data berdasarkan variabel $kompetensi yang diterima dari URL
        // Jika $kompetensi tidak ditemukan, $data akan menjadi null
        $data = $pelatihan[$kompetensi] ?? null;
        @endphp

        <!-- Logika kondisional: jika data pelatihan ditemukan, tampilkan isinya -->
        @if($data)
        <div class="bg-white rounded-xl shadow-lg overflow-hidden fade-in">
            <!-- Gambar Banner -->
            <img src="{{ $data['gambar'] }}" alt="Banner {{ $data['nama'] }}" class="w-full h-64 object-cover">

            <div class="p-8">
                <!-- Judul dan Deskripsi Pelatihan -->
                <h1 class="text-4xl font-bold text-gray-800">{{ $data['nama'] }}</h1>
                <p class="mt-4 text-gray-600 leading-relaxed">{{ $data['deskripsi'] }}</p>

                <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Detail Pelatihan -->
                    <div>
                        <h3 class="text-xl font-bold text-gray-800">Detail Pelatihan</h3>
                        <ul class="mt-4 text-gray-700 space-y-2">
                            <li class="flex items-center">
                                <svg class="w-5 h-5 mr-2 text-[#5c76c1]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <span>Tanggal: {{ $data['tanggal'] }}</span>
                            </li>
                            <li class="flex items-center">
                                <svg class="w-5 h-5 mr-2 text-[#5c76c1]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span>Durasi: 6 Hari (08.00 - 15.00)</span>
                            </li>
                            <li class="flex items-center">
                                <svg class="w-5 h-5 mr-2 text-[#5c76c1]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <span>Lokasi: UPT PTKK Dinas Pendidikan Jawa Timur</span>
                            </li>
                        </ul>
                    </div>

                    <!-- Persyaratan Pendaftaran -->
                    <div>
                        <h3 class="text-xl font-bold text-gray-800">Persyaratan Pendaftaran</h3>
                        <ul class="mt-4 text-gray-700 space-y-2 list-disc list-inside">
                            @foreach($data['persyaratan'] as $syarat)
                            <li>{{ $syarat }}</li>
                            @endforeach
                            <li>Pas Foto 3x4 formal background merah</li>
                        </ul>
                    </div>
                </div>

                <!-- Tombol Pendaftaran Utama -->
                <div class="mt-12 text-center">
                    <a href="{{route('pendaftaran.create')}}" class="px-12 py-4 bg-[#5c76c1] text-white font-semibold text-lg rounded-lg shadow-md hover:bg-blue-600 transition-transform transform hover:scale-105">
                        Daftar Pelatihan
                    </a>
                </div>
            </div>
        </div>
        @else
        <!-- Tampilan jika data pelatihan tidak ditemukan -->
        <div class="bg-white rounded-xl shadow-lg p-12 text-center fade-in">
            <h1 class="text-4xl font-bold text-gray-800">Pelatihan Tidak Ditemukan</h1>
            <p class="mt-4 text-gray-600">Maaf, pelatihan yang Anda cari tidak tersedia.</p>
            <a href="/" class="mt-6 inline-block px-6 py-3 text-white font-semibold rounded-lg btn-primary">
                Kembali ke Beranda
            </a>
        </div>
        @endif
    </main>
</body>

</html>
