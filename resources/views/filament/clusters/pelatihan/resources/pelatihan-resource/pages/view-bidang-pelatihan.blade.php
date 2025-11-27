<x-filament-panels::page>
    <!-- 1. BREADCRUMBS -->
    <nav class="flex mb-6 text-sm font-medium text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-sm w-fit overflow-hidden">
        <a href="{{ \App\Filament\Clusters\Pelatihan\Resources\PelatihanResource::getUrl('index') }}" class="px-4 py-2 hover:bg-gray-50 dark:hover:bg-gray-700 border-r border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50 transition-colors">
            <x-heroicon-o-home class="w-4 h-4 text-gray-400 dark:text-gray-500" />
        </a>
        <a href="{{ \App\Filament\Clusters\Pelatihan\Resources\PelatihanResource::getUrl('index') }}" class="px-4 py-2 hover:bg-gray-50 dark:hover:bg-gray-700 hover:text-primary-600 dark:hover:text-primary-400 border-r border-gray-100 dark:border-gray-700 transition-colors">
            Manajemen Pelatihan
        </a>
        <a href="{{ \App\Filament\Clusters\Pelatihan\Resources\PelatihanResource::getUrl('view', ['record' => $record]) }}" class="px-4 py-2 hover:bg-gray-50 dark:hover:bg-gray-700 hover:text-primary-600 dark:hover:text-primary-400 border-r border-gray-100 dark:border-gray-700 transition-colors">
            {{ $record->nama_pelatihan }}
        </a>
        <span class="px-4 py-2 bg-white dark:bg-gray-800 text-primary-600 dark:text-primary-400 flex items-center gap-2 border-t-2 border-primary-600 dark:border-primary-400 -mt-[2px]">
            <x-heroicon-o-rectangle-stack class="w-4 h-4" />
            {{ $bidangPelatihan->bidang->nama_bidang ?? 'Detail Bidang' }}
        </span>
    </nav>

    <!-- 2. HEADER KELAS & INSTRUKTUR -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-8">
        <div class="flex flex-col lg:flex-row justify-between gap-6">
            <!-- Info Kiri -->
            <div class="flex gap-5 flex-1">
                <div class="w-20 h-20 rounded-xl bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center text-blue-600 dark:text-blue-400 text-3xl font-bold shrink-0 shadow-sm border border-blue-200 dark:border-blue-800">
                    {{ substr($bidangPelatihan->bidang->nama_bidang ?? 'BD', 0, 2) }}
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-1">{{ $bidangPelatihan->bidang->nama_bidang ?? 'Nama Bidang' }}</h1>
                    <p class="text-gray-500 dark:text-gray-400 text-sm mb-3 flex items-center gap-2">
                        <x-heroicon-o-clock class="w-4 h-4" /> {{ \Carbon\Carbon::parse($bidangPelatihan->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($bidangPelatihan->jam_selesai)->format('H:i') }} WIB • {{ $bidangPelatihan->lokasi ?? 'Ruang Kelas' }}
                    </p>
                    <div class="flex items-center gap-2">
                        <span class="bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 text-xs px-2.5 py-1 rounded-full font-semibold border border-green-200 dark:border-green-800 flex items-center gap-1">
                            <span class="w-1.5 h-1.5 bg-green-500 rounded-full animate-pulse"></span> Sedang Berlangsung
                        </span>
                        <span class="text-gray-300 dark:text-gray-600">|</span>
                        <a href="#" class="text-blue-600 dark:text-blue-400 text-sm font-medium hover:underline flex items-center gap-1">
                            <x-heroicon-o-video-camera class="w-4 h-4" /> Link Zoom Kelas
                        </a>
                    </div>
                </div>
            </div>

            <!-- Info Kanan (Instruktur Management) -->
            <div class="flex flex-col gap-3 min-w-[320px]">
                <!-- Instruktur Utama -->
                <div class="flex items-center gap-3 bg-gray-50 dark:bg-gray-700/50 p-3 rounded-lg border border-gray-100 dark:border-gray-700">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($bidangPelatihan->instruktur->nama_instruktur ?? 'Instruktur') }}&background=random"
                        class="w-10 h-10 rounded-full border-2 border-white dark:border-gray-600 shadow-sm">
                    <div class="flex-1">
                        <p class="text-[10px] text-gray-500 dark:text-gray-400 font-bold uppercase tracking-wider">Instruktur Utama</p>
                        <p class="text-sm font-bold text-gray-900 dark:text-white">{{ $bidangPelatihan->instruktur->nama_instruktur ?? 'Belum ditentukan' }}</p>
                    </div>
                    <div class="flex gap-1">
                        <button class="w-7 h-7 flex items-center justify-center bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-600 rounded text-gray-500 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 hover:border-blue-400 text-xs">
                            <x-heroicon-o-envelope class="w-4 h-4" />
                        </button>
                    </div>
                </div>

                <!-- Add Instructor Action -->
                <div class="flex gap-2">
                    <button class="flex-1 py-2 border border-dashed border-gray-300 dark:border-gray-600 rounded-lg text-xs font-medium text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700 hover:text-primary-600 dark:hover:text-primary-400 hover:border-primary-600 dark:hover:border-primary-400 transition-colors flex items-center justify-center gap-1">
                        <x-heroicon-o-plus class="w-4 h-4" /> Tambah Asisten
                    </button>
                    <button class="py-2 px-3 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg text-xs font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700" title="Kelola Pengajar">
                        <x-heroicon-o-cog-6-tooth class="w-4 h-4" />
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- 3. TABS NAVIGATION -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden min-h-[600px]" x-data="{ activeTab: 'evaluasi' }">
        <div class="border-b border-gray-200 dark:border-gray-700 px-6 overflow-x-auto">
            <nav class="-mb-px flex space-x-8">
                <button @click="activeTab = 'evaluasi'" :class="{ 'border-primary-600 text-primary-600 dark:text-primary-400 dark:border-primary-400': activeTab === 'evaluasi', 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300': activeTab !== 'evaluasi' }" class="py-4 px-1 border-b-2 font-medium text-sm transition-colors whitespace-nowrap flex items-center">
                    <x-heroicon-o-chart-pie class="w-4 h-4 mr-2" />Hasil Tes & Survey
                </button>
                <button @click="activeTab = 'peserta'" :class="{ 'border-primary-600 text-primary-600 dark:text-primary-400 dark:border-primary-400': activeTab === 'peserta', 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300': activeTab !== 'peserta' }" class="py-4 px-1 border-b-2 font-medium text-sm transition-colors whitespace-nowrap flex items-center">
                    <x-heroicon-o-users class="w-4 h-4 mr-2" />Data Peserta (32)
                </button>
                <button @click="activeTab = 'kurikulum'" :class="{ 'border-primary-600 text-primary-600 dark:text-primary-400 dark:border-primary-400': activeTab === 'kurikulum', 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300': activeTab !== 'kurikulum' }" class="py-4 px-1 border-b-2 font-medium text-sm transition-colors whitespace-nowrap flex items-center">
                    <x-heroicon-o-clipboard-document-list class="w-4 h-4 mr-2" />Kurikulum & Sesi
                </button>
            </nav>
        </div>

        <!-- TAB CONTENT: HASIL TES & SURVEY (STATS) -->
        <div x-show="activeTab === 'evaluasi'" class="p-6 bg-gray-50/50 dark:bg-gray-900/50">

            <div class="flex justify-between items-center mb-6">
                <h3 class="font-bold text-gray-800 dark:text-white text-lg">Statistik Evaluasi Pembelajaran</h3>
                <div class="flex gap-2">
                    <span class="text-xs text-gray-500 dark:text-gray-400 self-center mr-2">Terakhir diupdate: Hari ini, 10:00</span>
                    <button class="bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 px-3 py-1.5 rounded-lg text-xs font-medium hover:bg-gray-50 dark:hover:bg-gray-700 flex items-center">
                        <x-heroicon-o-printer class="w-4 h-4 mr-1" /> Cetak Laporan
                    </button>
                </div>
            </div>

            <!-- A. Statistik Overview Cards (Pretest, Posttest, Monev) -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">

                <!-- 1. PRETEST STATS -->
                <div class="bg-white dark:bg-gray-800 p-5 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm relative overflow-hidden group hover:shadow-md transition-shadow">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h3 class="font-bold text-gray-800 dark:text-white text-lg">Pretest</h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Evaluasi Awal</p>
                        </div>
                        <span class="bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 text-[10px] uppercase px-2 py-1 rounded font-bold tracking-wide">Selesai</span>
                    </div>

                    <div class="flex items-center gap-4">
                        <!-- Placeholder for Circular Chart -->
                        <div class="relative w-24 h-24 flex items-center justify-center border-4 border-blue-500 rounded-full">
                            <div class="flex flex-col items-center justify-center text-gray-700 dark:text-gray-200">
                                <span class="text-2xl font-bold">65.4</span>
                                <span class="text-[9px] text-gray-400 font-medium uppercase">Rata-rata</span>
                            </div>
                        </div>
                        <div class="flex-1 space-y-2">
                            <div class="flex justify-between items-center text-xs">
                                <span class="text-gray-500 dark:text-gray-400">Tertinggi</span>
                                <span class="font-bold text-green-600 dark:text-green-400 bg-green-50 dark:bg-green-900/20 px-1.5 rounded">88</span>
                            </div>
                            <div class="flex justify-between items-center text-xs">
                                <span class="text-gray-500 dark:text-gray-400">Terendah</span>
                                <span class="font-bold text-red-600 dark:text-red-400 bg-red-50 dark:bg-red-900/20 px-1.5 rounded">40</span>
                            </div>
                            <div class="flex justify-between items-center text-xs border-t border-dashed border-gray-200 dark:border-gray-700 pt-1">
                                <span class="text-gray-500 dark:text-gray-400">Partisipasi</span>
                                <span class="font-bold text-gray-800 dark:text-white">100%</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 2. POSTTEST STATS -->
                <div class="bg-white dark:bg-gray-800 p-5 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm relative overflow-hidden group hover:shadow-md transition-shadow">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h3 class="font-bold text-gray-800 dark:text-white text-lg">Posttest</h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Evaluasi Akhir</p>
                        </div>
                        <span class="bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 text-[10px] uppercase px-2 py-1 rounded font-bold tracking-wide">On Progress</span>
                    </div>

                    <div class="flex items-center gap-4">
                         <!-- Placeholder for Circular Chart -->
                         <div class="relative w-24 h-24 flex items-center justify-center border-4 border-green-500 rounded-full">
                            <div class="flex flex-col items-center justify-center text-gray-700 dark:text-gray-200">
                                <span class="text-2xl font-bold">82.1</span>
                                <span class="text-[9px] text-gray-400 font-medium uppercase">Rata-rata</span>
                            </div>
                        </div>
                        <div class="flex-1 space-y-2">
                            <div class="flex justify-between items-center text-xs">
                                <span class="text-gray-500 dark:text-gray-400">Lulus (>75)</span>
                                <span class="font-bold text-green-600 dark:text-green-400">28 Org</span>
                            </div>
                            <div class="flex justify-between items-center text-xs">
                                <span class="text-gray-500 dark:text-gray-400">Remedial</span>
                                <span class="font-bold text-orange-600 dark:text-orange-400">4 Org</span>
                            </div>
                            <div class="flex justify-between items-center text-xs border-t border-dashed border-gray-200 dark:border-gray-700 pt-1">
                                <span class="text-gray-500 dark:text-gray-400">Kenaikan</span>
                                <span class="font-bold text-blue-600 dark:text-blue-400 flex items-center gap-1"><x-heroicon-o-arrow-trending-up class="w-3 h-3" /> +16.7</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 3. MONEV STATS (SURVEY) -->
                <div class="bg-white dark:bg-gray-800 p-5 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm relative overflow-hidden group hover:shadow-md transition-shadow">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h3 class="font-bold text-gray-800 dark:text-white text-lg">Survey Monev</h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Kepuasan Peserta</p>
                        </div>
                        <span class="bg-purple-100 dark:bg-purple-900/30 text-purple-700 dark:text-purple-400 text-[10px] uppercase px-2 py-1 rounded font-bold tracking-wide">Aktif</span>
                    </div>

                    <div class="flex flex-col gap-3">
                        <div>
                            <div class="flex justify-between text-xs mb-1">
                                <span class="text-gray-500 dark:text-gray-400">Kepuasan Materi</span>
                                <span class="font-bold text-gray-800 dark:text-white">4.8 <span class="text-gray-400 font-normal">/ 5.0</span></span>
                            </div>
                            <div class="w-full bg-gray-100 dark:bg-gray-700 rounded-full h-1.5">
                                <div class="bg-yellow-500 h-1.5 rounded-full" style="width: 96%"></div>
                            </div>
                        </div>
                        <div>
                            <div class="flex justify-between text-xs mb-1">
                                <span class="text-gray-500 dark:text-gray-400">Kinerja Instruktur</span>
                                <span class="font-bold text-gray-800 dark:text-white">4.9 <span class="text-gray-400 font-normal">/ 5.0</span></span>
                            </div>
                            <div class="w-full bg-gray-100 dark:bg-gray-700 rounded-full h-1.5">
                                <div class="bg-blue-500 h-1.5 rounded-full" style="width: 98%"></div>
                            </div>
                        </div>
                        <div>
                            <div class="flex justify-between text-xs mb-1">
                                <span class="text-gray-500 dark:text-gray-400">Respon Survey</span>
                                <span class="font-bold text-purple-600 dark:text-purple-400">20/32</span>
                            </div>
                            <div class="w-full bg-gray-100 dark:bg-gray-700 rounded-full h-1.5">
                                <div class="bg-purple-400 h-1.5 rounded-full" style="width: 65%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- B. Action List (Detail Tugas & Survey) -->
            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50 flex justify-between items-center">
                    <h3 class="font-bold text-gray-800 dark:text-white">Daftar Tes & Kuesioner</h3>
                    <div class="flex gap-2">
                        <button class="text-sm bg-primary-600 text-white px-3 py-1.5 rounded hover:bg-primary-700 transition-colors flex items-center">
                            <x-heroicon-o-plus class="w-4 h-4 mr-1" /> Buat Kuesioner Baru
                        </button>
                    </div>
                </div>
                <div class="divide-y divide-gray-100 dark:divide-gray-700">

                    <!-- ITEM 1: PRETEST -->
                    <div class="p-5 flex flex-col md:flex-row items-center justify-between gap-4 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                        <div class="flex items-center gap-4 w-full md:w-1/3">
                            <div class="w-10 h-10 rounded-lg bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 flex items-center justify-center font-bold">1</div>
                            <div>
                                <h4 class="font-bold text-gray-900 dark:text-white">Pretest: Dasar SEO</h4>
                                <p class="text-xs text-gray-500 dark:text-gray-400">10 Oktober 2024 • 20 Soal Pilihan Ganda</p>
                            </div>
                        </div>
                        <div class="w-full md:w-1/3 flex flex-col items-center">
                            <span class="text-xs text-gray-500 dark:text-gray-400 mb-1">Submission</span>
                            <div class="w-full max-w-[150px] bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                <div class="bg-green-500 h-2 rounded-full" style="width: 100%"></div>
                            </div>
                            <span class="text-xs font-bold text-gray-700 dark:text-gray-300 mt-1">32/32 Selesai</span>
                        </div>
                        <div class="w-full md:w-auto flex gap-2">
                            <button class="px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg text-sm hover:text-blue-600 dark:hover:text-blue-400 hover:border-blue-500">Lihat Nilai</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- TAB CONTENT: PESERTA -->
        <div x-show="activeTab === 'peserta'" class="tab-content p-6 bg-white dark:bg-gray-800">
            <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
                <div>
                    <h3 class="font-bold text-lg text-gray-900 dark:text-white">Daftar Peserta Kelas</h3>
                    <p class="text-gray-500 dark:text-gray-400 text-sm">Kelola data siswa yang terdaftar khusus di kelas ini.</p>
                </div>
                <div class="flex gap-2 w-full md:w-auto">
                    <div class="relative">
                        <input type="text" placeholder="Cari nama..." class="pl-9 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm focus:ring-primary-600 focus:border-primary-600 w-full md:w-48 bg-white dark:bg-gray-700 dark:text-white dark:placeholder-gray-400">
                        <x-heroicon-o-magnifying-glass class="w-4 h-4 absolute left-3 top-2.5 text-gray-400 dark:text-gray-500" />
                    </div>
                    <button class="bg-green-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-green-700 flex items-center gap-2 shadow-sm">
                        <x-heroicon-o-user-plus class="w-4 h-4" /> Tambah Peserta
                    </button>
                </div>
            </div>

            <!-- Tabel Peserta Placeholder -->
            <div class="border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-900/50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Nama Peserta</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Pretest</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Posttest</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Absen</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                            <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900 dark:text-white flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center text-blue-600 dark:text-blue-400 font-bold text-xs">AD</div>
                                Andi Dermawan
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-gray-600 dark:text-gray-300">60</td>
                            <td class="px-6 py-4 whitespace-nowrap text-center font-bold text-green-600 dark:text-green-400">85</td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <span class="bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-400 text-xs px-2 py-0.5 rounded font-medium">100%</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                <button class="text-gray-400 hover:text-blue-600 dark:hover:text-blue-400"><x-heroicon-o-ellipsis-vertical class="w-5 h-5" /></button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- TAB CONTENT: KURIKULUM (Placeholder) -->
        <div x-show="activeTab === 'kurikulum'" class="tab-content p-6 bg-white dark:bg-gray-800">
            <div class="flex justify-between items-center mb-6">
                <h3 class="font-bold text-lg text-gray-900 dark:text-white">Jadwal Sesi Pembelajaran</h3>
                <button class="bg-primary-600 text-white px-3 py-1.5 rounded-lg text-sm hover:bg-primary-700 flex items-center"><x-heroicon-o-plus class="w-4 h-4 mr-2" />Tambah Sesi</button>
            </div>
            <div class="space-y-4">
                <!-- Sesi 1 -->
                <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4 flex gap-4 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                    <div class="flex flex-col items-center justify-center bg-gray-100 dark:bg-gray-700 rounded-lg w-16 h-16 shrink-0">
                        <span class="text-xs text-gray-500 dark:text-gray-400 font-bold">SESI</span>
                        <span class="text-xl font-bold text-gray-800 dark:text-white">01</span>
                    </div>
                    <div class="flex-1">
                        <h4 class="font-bold text-gray-900 dark:text-white">Pengenalan Fundamental SEO</h4>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 flex items-center"><x-heroicon-o-calendar class="w-4 h-4 mr-1" /> 10 Oktober 2024 • 09:00 - 12:00</p>
                        <div class="flex gap-3 mt-2">
                            <span class="text-xs bg-green-50 dark:bg-green-900/30 text-green-700 dark:text-green-400 px-2 py-0.5 rounded border border-green-100 dark:border-green-800 flex items-center"><x-heroicon-o-check class="w-3 h-3 mr-1" />Selesai</span>
                            <a href="#" class="text-xs text-blue-600 dark:text-blue-400 hover:underline flex items-center"><x-heroicon-o-document-text class="w-3 h-3 mr-1" />Materi.pdf</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</x-filament-panels::page>
