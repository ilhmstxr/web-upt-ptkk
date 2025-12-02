<x-filament-panels::page>
    <!-- 1. NAVIGATION / BREADCRUMBS -->
    <nav class="flex mb-6 text-sm font-medium text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-sm w-fit overflow-hidden">
        <a href="{{ \App\Filament\Clusters\Pelatihan\Resources\PelatihanResource::getUrl('index') }}" class="px-4 py-2 hover:bg-gray-50 dark:hover:bg-gray-700 flex items-center gap-2 border-r border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50 transition-colors">
            <x-heroicon-o-home class="w-4 h-4 text-gray-400 dark:text-gray-500" />
        </a>
        <a href="{{ \App\Filament\Clusters\Pelatihan\Resources\PelatihanResource::getUrl('index') }}" class="px-4 py-2 hover:bg-gray-50 dark:hover:bg-gray-700 hover:text-primary-600 dark:hover:text-primary-400 flex items-center gap-2 border-r border-gray-100 dark:border-gray-700 transition-colors">
            Manajemen Pelatihan
        </a>
        <span class="px-4 py-2 bg-white dark:bg-gray-800 text-primary-600 dark:text-primary-400 flex items-center gap-2 border-t-2 border-primary-600 dark:border-primary-400 -mt-[2px]">
            <x-heroicon-o-folder-open class="w-4 h-4" />
            {{ $record->nama_pelatihan }}
        </span>
    </nav>

    <!-- 2. HERO SECTION -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <div class="flex items-center gap-3 mb-2">
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">{{ $record->nama_pelatihan }}</h1>
                @php
                    $statusColor = match($record->status) {
                        'Sedang Berjalan' => 'bg-green-100 text-green-800 border-green-200 dark:bg-green-900/30 dark:text-green-400 dark:border-green-800',
                        'Pendaftaran Buka' => 'bg-blue-100 text-blue-800 border-blue-200 dark:bg-blue-900/30 dark:text-blue-400 dark:border-blue-800',
                        'Selesai' => 'bg-gray-100 text-gray-800 border-gray-200 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600',
                        default => 'bg-gray-100 text-gray-800 border-gray-200 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600'
                    };
                    $dotColor = match($record->status) {
                        'Sedang Berjalan' => 'bg-green-500',
                        'Pendaftaran Buka' => 'bg-blue-500',
                        'Selesai' => 'bg-gray-500',
                        default => 'bg-gray-500'
                    };
                @endphp
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColor }} border">
                    <span class="w-1.5 h-1.5 {{ $dotColor }} rounded-full mr-1.5 animate-pulse"></span>
                    {{ $record->status }}
                </span>
            </div>
            <div class="flex flex-wrap items-center gap-4 md:gap-6 text-sm text-gray-500 dark:text-gray-400">
                <span class="flex items-center gap-2"><x-heroicon-o-calendar class="w-4 h-4 text-gray-400 dark:text-gray-500" /> {{ \Carbon\Carbon::parse($record->tanggal_mulai)->format('d M') }} - {{ \Carbon\Carbon::parse($record->tanggal_selesai)->format('d M Y') }}</span>
                <span class="hidden md:inline text-gray-300 dark:text-gray-600">|</span>
                <span class="flex items-center gap-2"><x-heroicon-o-map-pin class="w-4 h-4 text-gray-400 dark:text-gray-500" /> {{ $record->lokasi ?? 'Hybrid (Zoom & Offline)' }}</span>
                <span class="hidden md:inline text-gray-300 dark:text-gray-600">|</span>
                <span class="flex items-center gap-2"><x-heroicon-o-user class="w-4 h-4 text-gray-400 dark:text-gray-500" /> PIC: {{ $record->pic ?? 'Admin' }}</span>
            </div>
        </div>
        <div class="flex gap-3 w-full md:w-auto">
            <button class="flex-1 md:flex-none justify-center bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-50 dark:hover:bg-gray-700 shadow-sm transition-all flex items-center gap-2">
                <x-heroicon-o-arrow-down-tray class="w-4 h-4 text-gray-500 dark:text-gray-400" /> Export
            </button>
            <a href="{{ \App\Filament\Clusters\Pelatihan\Resources\PelatihanResource::getUrl('edit', ['record' => $record]) }}" class="flex-1 md:flex-none justify-center bg-primary-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-primary-700 shadow-sm transition-all flex items-center gap-2 shadow-blue-200 dark:shadow-none">
                <x-heroicon-o-pencil-square class="w-4 h-4" /> Edit
            </a>
        </div>
    </div>

    <!-- 3. STATS OVERVIEW -->
    <div class="grid grid-cols-1 md:grid-cols-12 gap-6 mb-8">
        
        <!-- Progress Card -->
        <div class="md:col-span-5 bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 relative overflow-hidden group hover:shadow-md transition-shadow">
            <div class="absolute top-0 right-0 p-3 opacity-10 group-hover:opacity-20 transition-opacity">
                <x-heroicon-o-clock class="w-16 h-16 text-primary-600 dark:text-primary-400" />
            </div>
            <div class="relative z-10">
                <h3 class="text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wide font-semibold mb-2">Timeline Progress</h3>
                <div class="flex items-end gap-2 mb-4">
                    <span class="text-3xl font-bold text-gray-900 dark:text-white">Hari ke-20</span>
                    <span class="text-sm text-gray-500 dark:text-gray-400 mb-1">/ 60 Hari</span>
                </div>
                <div class="w-full bg-gray-100 dark:bg-gray-700 rounded-full h-3 mb-2 overflow-hidden">
                    <div class="bg-primary-600 dark:bg-primary-500 h-3 rounded-full transition-all duration-1000 relative" style="width: 33%">
                            <div class="absolute top-0 left-0 bottom-0 right-0 bg-white opacity-20 w-full animate-pulse"></div>
                    </div>
                </div>
                <div class="flex justify-between text-xs text-gray-400 dark:text-gray-500 font-medium">
                    <span>Mulai: {{ \Carbon\Carbon::parse($record->tanggal_mulai)->format('d M') }}</span>
                    <span>Target: {{ \Carbon\Carbon::parse($record->tanggal_selesai)->format('d M') }}</span>
                </div>
            </div>
        </div>

        <!-- Total Peserta -->
        <div class="md:col-span-3 bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 group hover:shadow-md transition-shadow">
            <div class="flex items-center gap-4">
                <div class="p-3 bg-indigo-50 dark:bg-indigo-900/20 text-indigo-600 dark:text-indigo-400 rounded-lg group-hover:scale-110 transition-transform">
                    <x-heroicon-o-users class="w-6 h-6" />
                </div>
                <div>
                    <h3 class="text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wide font-semibold">Total Peserta</h3>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ $record->kuota_peserta }}</p>
                </div>
            </div>
            <div class="mt-4 pt-3 border-t border-gray-50 dark:border-gray-700 flex items-center gap-2 text-xs">
                <span class="text-green-600 dark:text-green-400 bg-green-50 dark:bg-green-900/20 px-1.5 py-0.5 rounded font-medium flex items-center gap-1">
                    <x-heroicon-o-arrow-trending-up class="w-3 h-3" /> +12%
                </span>
                <span class="text-gray-400 dark:text-gray-500">dari target awal</span>
            </div>
        </div>

        <!-- Total Bidang -->
        <div class="md:col-span-2 bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 group hover:shadow-md transition-shadow">
            <div class="flex flex-col h-full justify-between">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-orange-50 dark:bg-orange-900/20 text-orange-600 dark:text-orange-400 rounded-lg group-hover:scale-110 transition-transform">
                        <x-heroicon-o-rectangle-stack class="w-5 h-5" />
                    </div>
                    <h3 class="text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wide font-semibold">Bidang</h3>
                </div>
                <div>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $record->bidang_pelatihan_count ?? 4 }}</p>
                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Kelas Aktif</p>
                </div>
            </div>
        </div>

        <!-- Absensi -->
        <div class="md:col-span-2 bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 group hover:shadow-md transition-shadow">
            <div class="flex flex-col h-full justify-between">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-teal-50 dark:bg-teal-900/20 text-teal-600 dark:text-teal-400 rounded-lg group-hover:scale-110 transition-transform">
                        <x-heroicon-o-clipboard-document-check class="w-5 h-5" />
                    </div>
                    <h3 class="text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wide font-semibold">Absensi</h3>
                </div>
                <div>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white">92%</p>
                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Rata-rata</p>
                </div>
            </div>
        </div>
    </div>

    <!-- 4. TABS & MAIN CONTENT AREA -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden min-h-[500px]" x-data="{ activeTab: 'bidang' }">
        <!-- Tabs Header -->
        <div class="border-b border-gray-200 dark:border-gray-700 px-6 overflow-x-auto">
            <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                <button @click="activeTab = 'bidang'" :class="{ 'border-primary-600 text-primary-600 dark:text-primary-400 dark:border-primary-400': activeTab === 'bidang', 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-600': activeTab !== 'bidang' }" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors flex items-center">
                    <x-heroicon-o-rectangle-stack class="w-4 h-4 mr-2" />Daftar Bidang
                </button>
                <button @click="activeTab = 'peserta'" :class="{ 'border-primary-600 text-primary-600 dark:text-primary-400 dark:border-primary-400': activeTab === 'peserta', 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-600': activeTab !== 'peserta' }" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors flex items-center">
                    <x-heroicon-o-users class="w-4 h-4 mr-2" />Data Peserta
                </button>
                <button @click="activeTab = 'jadwal'" :class="{ 'border-primary-600 text-primary-600 dark:text-primary-400 dark:border-primary-400': activeTab === 'jadwal', 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-600': activeTab !== 'jadwal' }" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors flex items-center">
                    <x-heroicon-o-calendar-days class="w-4 h-4 mr-2" />Jadwal & Absensi
                </button>
                <button @click="activeTab = 'admin'" :class="{ 'border-primary-600 text-primary-600 dark:text-primary-400 dark:border-primary-400': activeTab === 'admin', 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-600': activeTab !== 'admin' }" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors flex items-center">
                        <x-heroicon-o-user-group class="w-4 h-4 mr-2" />Instruktur
                </button>
                <button @click="activeTab = 'asrama'" :class="{ 'border-primary-600 text-primary-600 dark:text-primary-400 dark:border-primary-400': activeTab === 'asrama', 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-600': activeTab !== 'asrama' }" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors flex items-center">
                    <x-heroicon-o-home-modern class="w-4 h-4 mr-2" />Asrama
                </button>
                <button @click="activeTab = 'hasil'" :class="{ 'border-primary-600 text-primary-600 dark:text-primary-400 dark:border-primary-400': activeTab === 'hasil', 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-600': activeTab !== 'hasil' }" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors flex items-center">
                    <x-heroicon-o-chart-pie class="w-4 h-4 mr-2" />Hasil Tes & Survey
                </button>
            </nav>
        </div>

        <!-- CONTENT 1: DAFTAR BIDANG -->
        <div x-show="activeTab === 'bidang'" class="p-6 bg-gray-50/30 dark:bg-gray-900/30 min-h-[400px]">
            <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-bold text-gray-800 dark:text-white">Kelas yang tersedia</h3>
                    <div class="relative w-full max-w-xs">
                    <input type="text" placeholder="Cari bidang..." class="w-full pl-9 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500 shadow-sm bg-white dark:bg-gray-700 dark:text-white dark:placeholder-gray-400">
                    <x-heroicon-o-magnifying-glass class="w-4 h-4 absolute left-3 top-2.5 text-gray-400 dark:text-gray-500" />
                    </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($record->bidangPelatihan as $bidang)
                <a href="{{ \App\Filament\Clusters\Pelatihan\Resources\PelatihanResource::getUrl('view-bidang', ['record' => $record, 'bidang_id' => $bidang->id]) }}" class="group block bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-5 hover:border-blue-400 dark:hover:border-blue-500 hover:shadow-md transition-all relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-16 h-16 bg-blue-50 dark:bg-blue-900/20 rounded-bl-full -mr-8 -mt-8 transition-all group-hover:bg-blue-100 dark:group-hover:bg-blue-900/30"></div>
                    <div class="flex justify-between items-start relative z-10">
                        <div class="flex gap-4">
                            <div class="h-14 w-14 rounded-xl bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center text-blue-600 dark:text-blue-400 text-xl font-bold border border-blue-200 dark:border-blue-800 shadow-sm">
                                {{ substr($bidang->bidang->nama_bidang ?? 'BD', 0, 2) }}
                            </div>
                            <div>
                                <h4 class="text-lg font-bold text-gray-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">{{ $bidang->bidang->nama_bidang ?? 'Nama Bidang' }}</h4>
                                <div class="flex items-center gap-2 mt-1">
                                    <div class="flex -space-x-2"><div class="w-6 h-6 rounded-full border-2 border-white dark:border-gray-800 bg-gray-200 dark:bg-gray-700"></div></div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Mentor: {{ $bidang->instruktur->nama_instruktur ?? 'Belum ditentukan' }}</p>
                                </div>
                            </div>
                        </div>
                        <span class="bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 text-xs px-2.5 py-1 rounded-full font-medium border border-gray-200 dark:border-gray-600">{{ $bidang->pendaftaranPelatihan()->count() }} Peserta</span>
                    </div>
                    <div class="mt-5 pt-4 border-t border-gray-100 dark:border-gray-700 flex items-center justify-between text-sm">
                        <div class="flex gap-4 text-gray-500 dark:text-gray-400 text-xs">
                            <span class="flex items-center gap-1"><x-heroicon-o-clock class="w-3 h-3" /> 12 Sesi</span>
                            <span class="flex items-center gap-1"><x-heroicon-o-clipboard-document-list class="w-3 h-3" /> 3 Tugas</span>
                        </div>
                        <span class="text-primary-600 dark:text-primary-400 font-medium text-xs flex items-center group-hover:underline">Lihat Detail <x-heroicon-o-arrow-right class="w-3 h-3 ml-1" /></span>
                    </div>
                </a>
                @endforeach
            </div>
        </div>

        <!-- CONTENT 2: DATA PESERTA -->
        <div x-show="activeTab === 'peserta'" class="p-6 bg-white dark:bg-gray-800 min-h-[400px]">
             <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
                <h3 class="text-lg font-bold text-gray-800 dark:text-white">Data Peserta Terdaftar</h3>
                <div class="flex gap-2 w-full md:w-auto">
                    <button class="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-xs font-medium text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700"><x-heroicon-o-funnel class="w-3 h-3 inline mr-1" /> Filter</button>
                    <button class="px-3 py-2 bg-green-600 text-white rounded-lg text-xs font-medium hover:bg-green-700"><x-heroicon-o-plus class="w-3 h-3 inline mr-1" /> Tambah Peserta</button>
                </div>
            </div>
            <div class="text-center text-gray-500 dark:text-gray-400 py-10">
                <p>Data peserta akan ditampilkan di sini.</p>
            </div>
        </div>

        <!-- CONTENT 3: JADWAL -->
        <div x-show="activeTab === 'jadwal'" class="p-6 bg-white dark:bg-gray-800 min-h-[400px]">
             <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-6">Jadwal Pelatihan Minggu Ini</h3>
             <div class="text-center text-gray-500 dark:text-gray-400 py-10">
                <p>Jadwal pelatihan akan ditampilkan di sini.</p>
            </div>
        </div>

        <!-- CONTENT 4: INSTRUKTUR -->
        <div x-show="activeTab === 'admin'" class="p-6 bg-white dark:bg-gray-800 min-h-[400px]">
            <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
                <h3 class="text-lg font-bold text-gray-800 dark:text-white">Daftar Instruktur & Mentor</h3>
                <button class="bg-primary-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-primary-700 shadow-sm transition-all flex items-center gap-2">
                    <x-heroicon-o-plus class="w-4 h-4" /> Tambah Instruktur
                </button>
            </div>
             <div class="text-center text-gray-500 dark:text-gray-400 py-10">
                <p>Data instruktur akan ditampilkan di sini.</p>
            </div>
        </div>

        <!-- CONTENT 5: ASRAMA -->
        <div x-show="activeTab === 'asrama'" class="p-6 bg-gray-50/30 dark:bg-gray-900/30 min-h-[400px]">
             <div class="flex flex-col md:flex-row justify-between items-end mb-6 gap-4">
                <div>
                        <h3 class="text-lg font-bold text-gray-800 dark:text-white">Manajemen Kamar Asrama</h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Kelola penempatan peserta di Gedung A (Pria) dan Gedung B (Wanita).</p>
                </div>
            </div>
             <div class="text-center text-gray-500 dark:text-gray-400 py-10">
                <p>Data asrama akan ditampilkan di sini.</p>
            </div>
        </div>

        <!-- CONTENT 6: HASIL TES / SURVEY -->
        <div x-show="activeTab === 'hasil'" class="p-6 bg-white dark:bg-gray-800 min-h-[400px]">
             <div class="flex justify-between items-center mb-6">
                <div>
                        <h3 class="text-lg font-bold text-gray-800 dark:text-white">Laporan Hasil Evaluasi Batch 5</h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Rekapitulasi nilai pretest, posttest, dan survey kepuasan.</p>
                </div>
            </div>
             <div class="text-center text-gray-500 dark:text-gray-400 py-10">
                <div class="grid grid-cols-1 gap-6">
                    @livewire(\App\Filament\Clusters\Pelatihan\Resources\PelatihanResource\Widgets\JawabanAkumulatifChart::class, ['record' => $record])
                    @livewire(\App\Filament\Clusters\Pelatihan\Resources\PelatihanResource\Widgets\JawabanPerKategoriChart::class, ['record' => $record])
                    @livewire(\App\Filament\Clusters\Pelatihan\Resources\PelatihanResource\Widgets\PiePerPertanyaanWidget::class, ['record' => $record])
                </div>
            </div>
        </div>

    </div>
</x-filament-panels::page>
