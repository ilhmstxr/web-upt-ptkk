<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pelatihan - {{ $pelatihan->nama_pelatihan }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#2563EB',
                        secondary: '#475569',
                    }
                }
            }
        }
    </script>
    <style>
        /* Smooth transition for tab content */
        .tab-content {
            display: none;
            animation: fadeIn 0.3s ease-in-out;
        }
        .tab-content.active {
            display: block;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        /* Custom scrollbar for tables */
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f1f1f1; 
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #cbd5e1; 
            border-radius: 10px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #94a3b8; 
        }
    </style>
</head>
<body class="bg-gray-50 text-slate-800 font-sans min-h-screen pb-10">

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <!-- 1. NAVIGATION / BREADCRUMBS -->
        <nav class="flex mb-6 text-sm font-medium text-gray-500 bg-white border border-gray-200 rounded-lg shadow-sm w-fit overflow-hidden">
            <a href="{{ route('landing') }}" class="px-4 py-2 hover:bg-gray-50 flex items-center gap-2 border-r border-gray-100 bg-gray-50 transition-colors">
                <i class="fa-solid fa-house text-gray-400"></i>
            </a>
            <a href="#" class="px-4 py-2 hover:bg-gray-50 hover:text-primary flex items-center gap-2 border-r border-gray-100 transition-colors">
                Manajemen Pelatihan
            </a>
            <span class="px-4 py-2 bg-white text-primary flex items-center gap-2 border-t-2 border-primary -mt-[2px]">
                <i class="fa-regular fa-folder-open"></i>
                {{ $pelatihan->nama_pelatihan }}
            </span>
        </nav>

        <!-- 2. HERO SECTION -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <h1 class="text-3xl font-bold text-gray-900">{{ $pelatihan->nama_pelatihan }}</h1>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 border border-green-200">
                        <span class="w-1.5 h-1.5 bg-green-500 rounded-full mr-1.5 animate-pulse"></span>
                        {{ ucfirst($pelatihan->status) }}
                    </span>
                </div>
                <div class="flex flex-wrap items-center gap-4 md:gap-6 text-sm text-gray-500">
                    <span class="flex items-center gap-2"><i class="fa-regular fa-calendar text-gray-400"></i> {{ \Carbon\Carbon::parse($pelatihan->tanggal_mulai)->format('d M') }} - {{ \Carbon\Carbon::parse($pelatihan->tanggal_selesai)->format('d M Y') }}</span>
                    <span class="hidden md:inline text-gray-300">|</span>
                    <span class="flex items-center gap-2"><i class="fa-solid fa-location-dot text-gray-400"></i> {{ $pelatihan->lokasi ?? 'UPT PTKK' }}</span>
                    <span class="hidden md:inline text-gray-300">|</span>
                    <span class="flex items-center gap-2"><i class="fa-solid fa-user-tie text-gray-400"></i> PIC: Admin</span>
                </div>
            </div>
            <div class="flex gap-3 w-full md:w-auto">
                <button class="flex-1 md:flex-none justify-center bg-white border border-gray-300 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-50 shadow-sm transition-all flex items-center gap-2">
                    <i class="fa-solid fa-file-arrow-down text-gray-500"></i> Export
                </button>
                {{-- <button class="flex-1 md:flex-none justify-center bg-primary text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-700 shadow-sm transition-all flex items-center gap-2 shadow-blue-200">
                    <i class="fa-solid fa-pen-to-square"></i> Edit
                </button> --}}
            </div>
        </div>

        <!-- 3. STATS OVERVIEW -->
        <div class="grid grid-cols-1 md:grid-cols-12 gap-6 mb-8">
            
            <!-- Progress Card -->
            <div class="md:col-span-5 bg-white p-6 rounded-xl shadow-sm border border-gray-200 relative overflow-hidden group hover:shadow-md transition-shadow">
                <div class="absolute top-0 right-0 p-3 opacity-10 group-hover:opacity-20 transition-opacity">
                    <i class="fa-solid fa-clock text-6xl text-primary"></i>
                </div>
                <div class="relative z-10">
                    <h3 class="text-gray-500 text-xs uppercase tracking-wide font-semibold mb-2">Timeline Progress</h3>
                    @php
                        $start = \Carbon\Carbon::parse($pelatihan->tanggal_mulai);
                        $end = \Carbon\Carbon::parse($pelatihan->tanggal_selesai);
                        $now = now();
                        $totalDays = $start->diffInDays($end) ?: 1;
                        $daysPassed = $start->diffInDays($now);
                        if ($now->isBefore($start)) $daysPassed = 0;
                        if ($now->isAfter($end)) $daysPassed = $totalDays;
                        $percentage = min(100, max(0, ($daysPassed / $totalDays) * 100));
                    @endphp
                    <div class="flex items-end gap-2 mb-4">
                        <span class="text-3xl font-bold text-gray-900">Hari ke-{{ $daysPassed }}</span>
                        <span class="text-sm text-gray-500 mb-1">/ {{ $totalDays }} Hari</span>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-3 mb-2 overflow-hidden">
                        <div class="bg-primary h-3 rounded-full transition-all duration-1000 relative" style="width: {{ $percentage }}%">
                             <div class="absolute top-0 left-0 bottom-0 right-0 bg-white opacity-20 w-full animate-pulse"></div>
                        </div>
                    </div>
                    <div class="flex justify-between text-xs text-gray-400 font-medium">
                        <span>Mulai: {{ $start->format('d M') }}</span>
                        <span>Target: {{ $end->format('d M') }}</span>
                    </div>
                </div>
            </div>

            <!-- Total Peserta -->
            <div class="md:col-span-3 bg-white p-6 rounded-xl shadow-sm border border-gray-200 group hover:shadow-md transition-shadow">
                <div class="flex items-center gap-4">
                    <div class="p-3 bg-indigo-50 text-indigo-600 rounded-lg group-hover:scale-110 transition-transform">
                        <i class="fa-solid fa-users text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-gray-500 text-xs uppercase tracking-wide font-semibold">Total Peserta</h3>
                        <p class="text-2xl font-bold text-gray-900 mt-1">{{ $pelatihan->pendaftaranPelatihan->count() }}</p>
                    </div>
                </div>
                <div class="mt-4 pt-3 border-t border-gray-50 flex items-center gap-2 text-xs">
                    <span class="text-gray-400">dari target {{ $pelatihan->jumlah_peserta ?? '-' }}</span>
                </div>
            </div>

            <!-- Total Kompetensi -->
            <div class="md:col-span-2 bg-white p-6 rounded-xl shadow-sm border border-gray-200 group hover:shadow-md transition-shadow">
                <div class="flex flex-col h-full justify-between">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-orange-50 text-orange-600 rounded-lg group-hover:scale-110 transition-transform">
                            <i class="fa-solid fa-layer-group"></i>
                        </div>
                        <h3 class="text-gray-500 text-xs uppercase tracking-wide font-semibold">Kompetensi</h3>
                    </div>
                    <div>
                        <p class="text-3xl font-bold text-gray-900">{{ $pelatihan->kompetensiPelatihan->count() }}</p>
                        <p class="text-xs text-gray-400 mt-1">Kelas Aktif</p>
                    </div>
                </div>
            </div>

            <!-- Absensi -->
            <div class="md:col-span-2 bg-white p-6 rounded-xl shadow-sm border border-gray-200 group hover:shadow-md transition-shadow">
                <div class="flex flex-col h-full justify-between">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-teal-50 text-teal-600 rounded-lg group-hover:scale-110 transition-transform">
                            <i class="fa-solid fa-clipboard-check"></i>
                        </div>
                        <h3 class="text-gray-500 text-xs uppercase tracking-wide font-semibold">Absensi</h3>
                    </div>
                    <div>
                        <p class="text-3xl font-bold text-gray-900">-</p>
                        <p class="text-xs text-gray-400 mt-1">Rata-rata</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- 4. TABS & MAIN CONTENT AREA -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden min-h-[500px]">
            <!-- Tabs Header -->
            <div class="border-b border-gray-200 px-6 overflow-x-auto">
                <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                    <button onclick="switchTab('kompetensi')" id="tab-kompetensi" class="tab-btn active border-primary text-primary whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                        <i class="fa-solid fa-layer-group mr-2"></i>Daftar Kompetensi ({{ $pelatihan->kompetensiPelatihan->count() }})
                    </button>
                    <button onclick="switchTab('peserta')" id="tab-peserta" class="tab-btn border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                        <i class="fa-solid fa-users mr-2"></i>Data Peserta
                    </button>
                    {{-- <button onclick="switchTab('jadwal')" id="tab-jadwal" class="tab-btn border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                        <i class="fa-regular fa-calendar-days mr-2"></i>Jadwal & Absensi
                    </button> --}}
                    {{-- <button onclick="switchTab('admin')" id="tab-admin" class="tab-btn border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                         <i class="fa-solid fa-user-shield mr-2"></i>Instruktur
                    </button> --}}
                    {{-- <button onclick="switchTab('asrama')" id="tab-asrama" class="tab-btn border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                        <i class="fa-solid fa-bed mr-2"></i>Asrama
                   </button> --}}
                   <!-- NEW TAB: HASIL TES / SURVEY -->
                   {{-- <button onclick="switchTab('hasil')" id="tab-hasil" class="tab-btn border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                        <i class="fa-solid fa-chart-pie mr-2"></i>Hasil Tes & Survey
                   </button> --}}
                </nav>
            </div>

            <!-- CONTENT 1: DAFTAR KOMPETENSI -->
            <div id="content-kompetensi" class="tab-content active p-6 bg-gray-50/30 min-h-[400px]">
                <div class="flex justify-between items-center mb-6">
                     <h3 class="text-lg font-bold text-gray-800">Kelas yang tersedia</h3>
                     {{-- <div class="relative w-full max-w-xs">
                        <input type="text" placeholder="Cari kompetensi..." class="w-full pl-9 pr-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500 shadow-sm">
                        <i class="fa-solid fa-search absolute left-3 top-2.5 text-gray-400 text-sm"></i>
                     </div> --}}
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($pelatihan->kompetensiPelatihan as $kompetensi)
                    <!-- Card -->
                    <a href="#" class="group block bg-white border border-gray-200 rounded-xl p-5 hover:border-blue-400 hover:shadow-md transition-all relative overflow-hidden">
                        <div class="absolute top-0 right-0 w-16 h-16 bg-blue-50 rounded-bl-full -mr-8 -mt-8 transition-all group-hover:bg-blue-100"></div>
                        <div class="flex justify-between items-start relative z-10">
                            <div class="flex gap-4">
                                <div class="h-14 w-14 rounded-xl bg-blue-100 flex items-center justify-center text-blue-600 text-xl font-bold border border-blue-200 shadow-sm">
                                    {{ substr($kompetensi->kompetensi->nama_kompetensi ?? 'KK', 0, 2) }}
                                </div>
                                <div>
                                    <h4 class="text-lg font-bold text-gray-900 group-hover:text-blue-600 transition-colors">{{ $kompetensi->kompetensi->nama_kompetensi ?? 'Nama Kompetensi' }}</h4>
                                    <div class="flex items-center gap-2 mt-1">
                                        {{-- <div class="flex -space-x-2"><img class="w-6 h-6 rounded-full border-2 border-white" src="https://ui-avatars.com/api/?name=Budi+Santoso&background=random" alt="Mentor"></div>
                                        <p class="text-xs text-gray-500">Mentor: Budi Santoso</p> --}}
                                    </div>
                                </div>
                            </div>
                            {{-- <span class="bg-gray-100 text-gray-600 text-xs px-2.5 py-1 rounded-full font-medium border border-gray-200">{{ $kompetensi->kuota ?? 0 }} Kuota</span> --}}
                        </div>
                        <div class="mt-5 pt-4 border-t border-gray-100 flex items-center justify-between text-sm">
                            <div class="flex gap-4 text-gray-500 text-xs">
                                {{-- <span class="flex items-center gap-1"><i class="fa-regular fa-clock"></i> 12 Sesi</span>
                                <span class="flex items-center gap-1"><i class="fa-solid fa-list-check"></i> 3 Tugas</span> --}}
                            </div>
                            <span class="text-primary font-medium text-xs flex items-center group-hover:underline">Lihat Detail <i class="fa-solid fa-arrow-right ml-1 transition-transform group-hover:translate-x-1"></i></span>
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>

            <!-- CONTENT 2: DATA PESERTA -->
            <div id="content-peserta" class="tab-content p-6 bg-white min-h-[400px]">
                <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
                    <h3 class="text-lg font-bold text-gray-800">Data Peserta Terdaftar</h3>
                    <div class="flex gap-2 w-full md:w-auto">
                        {{-- <button class="px-3 py-2 border border-gray-300 rounded-lg text-xs font-medium text-gray-600 hover:bg-gray-50"><i class="fa-solid fa-filter mr-1"></i> Filter</button>
                        <button class="px-3 py-2 bg-green-600 text-white rounded-lg text-xs font-medium hover:bg-green-700"><i class="fa-solid fa-plus mr-1"></i> Tambah Peserta</button> --}}
                    </div>
                </div>
                
                <div class="overflow-x-auto custom-scrollbar border border-gray-200 rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Peserta</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Instansi</th>
                                {{-- <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kehadiran</th> --}}
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                {{-- <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th> --}}
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200 text-sm">
                            @foreach($pelatihan->pendaftaranPelatihan as $pendaftaran)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">
                                    <div class="flex items-center">
                                        <div class="h-8 w-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center mr-3 text-xs font-bold">
                                            {{ substr($pendaftaran->peserta->nama ?? 'XX', 0, 2) }}
                                        </div>
                                        {{ $pendaftaran->peserta->nama ?? '-' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-500">
                                    {{ $pendaftaran->peserta->instansi->asal_instansi ?? '-' }}
                                </td>
                                {{-- <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="w-full bg-gray-200 rounded-full h-1.5 w-24 mb-1">
                                        <div class="bg-green-500 h-1.5 rounded-full" style="width: 90%"></div>
                                    </div>
                                    <span class="text-xs text-gray-500">90% Hadir</span>
                                </td> --}}
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        {{ $pendaftaran->status ?? 'Terdaftar' }}
                                    </span>
                                </td>
                                {{-- <td class="px-6 py-4 whitespace-nowrap text-right text-gray-500">
                                    <a href="#" class="text-blue-600 hover:text-blue-900 text-xs font-bold">Detail</a>
                                </td> --}}
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- CONTENT 3: JADWAL -->
            <div id="content-jadwal" class="tab-content p-6 bg-white min-h-[400px]">
                <h3 class="text-lg font-bold text-gray-800 mb-6">Jadwal Pelatihan</h3>
                <div class="text-gray-500 text-center py-10">Belum ada jadwal.</div>
            </div>

            <!-- CONTENT 4: INSTRUKTUR -->
             <div id="content-admin" class="tab-content p-6 bg-white min-h-[400px]">
                <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
                    <h3 class="text-lg font-bold text-gray-800">Daftar Instruktur & Mentor</h3>
                </div>
                <div class="text-gray-500 text-center py-10">Belum ada data instruktur.</div>
            </div>

            <!-- CONTENT 5: ASRAMA (NEW OPTIONAL FEATURE) -->
            <div id="content-asrama" class="tab-content p-6 bg-gray-50/30 min-h-[400px]">
                <div class="text-gray-500 text-center py-10">Fitur Asrama belum aktif.</div>
            </div>

            <!-- CONTENT 6: HASIL TES / SURVEY -->
            <div id="content-hasil" class="tab-content p-6 bg-white min-h-[400px]">
                <div class="text-gray-500 text-center py-10">Belum ada hasil tes.</div>
            </div>

        </div>
        
    </div>

    <script>
        function switchTab(tabId) {
            // Hide all tab contents
            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.remove('active');
            });
            
            // Deactivate all tab buttons
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.classList.remove('active', 'border-primary', 'text-primary');
                btn.classList.add('border-transparent', 'text-gray-500');
            });
            
            // Show selected tab content
            document.getElementById('content-' + tabId).classList.add('active');
            
            // Activate selected tab button
            const activeBtn = document.getElementById('tab-' + tabId);
            activeBtn.classList.add('active', 'border-primary', 'text-primary');
            activeBtn.classList.remove('border-transparent', 'text-gray-500');
        }
    </script>
</body>
</html>
