<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pelatihan - {{ $pelatihan->judul ?? 'Tidak Ditemukan' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap');
        body { font-family: 'Inter', sans-serif; background-color: #f3f4f6; }
        .fade-in { animation: fadeIn 1s ease-out; }
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
        .btn-primary { background-color: #5c76c1; transition: all 150ms cubic-bezier(0.4, 0, 0.2, 1); }
        .btn-primary:hover { background-color: #4a62a9; transform: scale(1.05); }
    </style>
</head>
<body class="bg-gray-100 min-h-screen">

    <header class="bg-white shadow-sm p-4 sticky top-0 z-50">
        <div class="container mx-auto flex items-center justify-between">
            <a href="/" class="flex items-center space-x-4">
                <img src="{{ asset('images/logo-upt-ptkk.png') }}" alt="Logo UPT PTKK" class="w-10 h-10 rounded-full">
                <h1 class="text-xl font-bold text-gray-800">UPT PTKK</h1>
            </a>
            <nav>
                <a href="/pendaftaran" class="px-4 py-2 text-white font-semibold rounded-lg shadow-md btn-primary">Daftar Sekarang</a>
            </nav>
        </div>
    </header>

    <main class="container mx-auto p-4 md:p-8">
        @if($pelatihan)
            <div class="bg-white rounded-xl shadow-lg overflow-hidden fade-in">
                <img src="{{ asset('storage/'.$pelatihan->gambar) }}" 
                     alt="Banner {{ $pelatihan->judul }}" 
                     class="w-full h-64 object-cover">
                
                <div class="p-8">
                    <h1 class="text-4xl font-bold text-gray-800">{{ $pelatihan->judul }}</h1>
                    <p class="mt-4 text-gray-600 leading-relaxed">{{ $pelatihan->deskripsi }}</p>
                    
                    <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <h3 class="text-xl font-bold text-gray-800">Detail Pelatihan</h3>
                            <ul class="mt-4 text-gray-700 space-y-2">
                                <li class="flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-[#5c76c1]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    <span>Tanggal: {{ \Carbon\Carbon::parse($pelatihan->tanggal_mulai)->format('d M Y') }} - {{ \Carbon\Carbon::parse($pelatihan->tanggal_selesai)->format('d M Y') }}</span>
                                </li>
                                <li class="flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-[#5c76c1]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    <span>Durasi & Waktu: {{ $pelatihan->durasi }}, {{ $pelatihan->waktu }}</span>
                                </li>
                                <li class="flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-[#5c76c1]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    <span>Lokasi: UPT PTKK Dinas Pendidikan Jawa Timur</span>
                                </li>
                                <li class="flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-[#5c76c1]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292m0 2.416a4 4 0 010 5.292m-6 4H3a2 2 0 01-2-2v-2a2 2 0 012-2h3m0 0a6 6 0 0112 0m-3-2a3 3 0 00-3-3m0 0a3 3 0 00-3-3m0 6v-3m0 0a3 3 0 00-3-3m0 0a3 3 0 00-3-3m0 6v-3m0 0a3 3 0 00-3-3m0 0a3 3 0 00-3-3"/></svg>
                                    <span>Target Peserta: {{ $pelatihan->target_peserta }}</span>
                                </li>
                                <li class="flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-[#5c76c1]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    <span>Tujuan: {{ $pelatihan->tujuan }}</span>
                                </li>
                            </ul>
                        </div>
                        
                        <div>
                            <h3 class="text-xl font-bold text-gray-800">Materi Pelatihan</h3>
                            <ul class="mt-4 text-gray-700 space-y-2 list-disc list-inside">
                                {{-- Jika materi disimpan sebagai array/JSON, gunakan foreach --}}
                                {{-- @foreach($pelatihan->materi as $materi)
                                    <li>{{ $materi }}</li>
                                @endforeach --}}
                                {{-- Jika materi disimpan sebagai teks biasa, tampilkan langsung --}}
                                <li>{{ $pelatihan->materi }}</li>
                            </ul>
                        </div>
                    </div>

                    {{-- Galeri Kegiatan --}}
                    @if($pelatihan->galeri && count($pelatihan->galeri) > 0)
                    <div class="mt-8">
                        <h3 class="text-xl font-bold text-gray-800">Galeri Kegiatan</h3>
                        <div class="mt-4 grid grid-cols-2 md:grid-cols-4 gap-4">
                            @foreach($pelatihan->galeri as $gambarGaleri)
                                <img src="{{ asset('storage/' . $gambarGaleri) }}" alt="Galeri" class="w-full h-32 object-cover rounded-lg shadow-md">
                            @endforeach
                        </div>
                    </div>
                    @endif
                    
                    <div class="mt-12 text-center">
                        <a href="/pendaftaran" class="px-12 py-4 text-white font-semibold text-lg rounded-lg shadow-md btn-primary">
                            Daftar Pelatihan
                        </a>
                    </div>
                </div>
            </div>
        @else
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