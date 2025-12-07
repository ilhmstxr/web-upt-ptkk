<x-filament-panels::page>
    <!-- Custom Header Replaced by Native Filament Header -->

    <!-- INSTRUCTOR LIST SECTION -->
    <div class="mb-6">
         <h3 class="font-bold text-gray-800 dark:text-white mb-3">Daftar Instruktur</h3>
         <div class="flex flex-wrap gap-4">
            @foreach($this->instructors as $inst)
            <div class="flex items-center gap-3 bg-white dark:bg-gray-800 p-3 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm min-w-[200px]">
                <img src="https://ui-avatars.com/api/?name={{ urlencode($inst->instruktur->nama_instruktur ?? 'Instruktur') }}&background=random"
                    class="w-10 h-10 rounded-full border-2 border-white dark:border-gray-600 shadow-sm">
                <div>
                    <p class="text-[10px] text-gray-500 dark:text-gray-400 font-bold uppercase tracking-wider">Instruktur / Asisten</p>
                    <p class="text-sm font-bold text-gray-900 dark:text-white">{{ $inst->instruktur->nama_instruktur ?? 'Belum ditentukan' }}</p>
                </div>
            </div>
            @endforeach
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
                                <span class="text-2xl font-bold">{{ $this->statistik['pretest']['avg'] }}</span>
                                <span class="text-[9px] text-gray-400 font-medium uppercase">Rata-rata</span>
                            </div>
                        </div>
                        <div class="flex-1 space-y-2">
                            <div class="flex justify-between items-center text-xs">
                                <span class="text-gray-500 dark:text-gray-400">Tertinggi</span>
                                <span class="font-bold text-green-600 dark:text-green-400 bg-green-50 dark:bg-green-900/20 px-1.5 rounded">{{ $this->statistik['pretest']['max'] }}</span>
                            </div>
                            <div class="flex justify-between items-center text-xs">
                                <span class="text-gray-500 dark:text-gray-400">Terendah</span>
                                <span class="font-bold text-red-600 dark:text-red-400 bg-red-50 dark:bg-red-900/20 px-1.5 rounded">{{ $this->statistik['pretest']['min'] }}</span>
                            </div>
                            <div class="flex justify-between items-center text-xs border-t border-dashed border-gray-200 dark:border-gray-700 pt-1">
                                <span class="text-gray-500 dark:text-gray-400">Partisipasi</span>
                                <span class="font-bold text-gray-800 dark:text-white">{{ $this->statistik['pretest']['count'] }}/{{ $this->statistik['monev']['total_peserta'] }}</span>
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
                                <span class="text-2xl font-bold">{{ $this->statistik['posttest']['avg'] }}</span>
                                <span class="text-[9px] text-gray-400 font-medium uppercase">Rata-rata</span>
                            </div>
                        </div>
                        <div class="flex-1 space-y-2">
                            <div class="flex justify-between items-center text-xs">
                                <span class="text-gray-500 dark:text-gray-400">Lulus (>75)</span>
                                <span class="font-bold text-green-600 dark:text-green-400">{{ $this->statistik['posttest']['lulus'] }} Org</span>
                            </div>
                            <div class="flex justify-between items-center text-xs">
                                <span class="text-gray-500 dark:text-gray-400">Remedial</span>
                                <span class="font-bold text-orange-600 dark:text-orange-400">{{ $this->statistik['posttest']['remedial'] }} Org</span>
                            </div>
                          
                             <div class="flex justify-between items-center text-xs border-t border-dashed border-gray-200 dark:border-gray-700 pt-1">
                                <span class="text-gray-500 dark:text-gray-400">Partisipasi</span>
                                <span class="font-bold text-gray-800 dark:text-white">{{ $this->statistik['posttest']['count'] ?? 0 }}/{{ $this->statistik['monev']['total_peserta'] }}</span>
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
                                <span class="font-bold text-gray-800 dark:text-white">{{ $this->statistik['monev']['avg'] }} <span class="text-gray-400 font-normal">/ 5.0</span></span>
                            </div>
                            <div class="w-full bg-gray-100 dark:bg-gray-700 rounded-full h-1.5">
                                <div class="bg-yellow-500 h-1.5 rounded-full" style="width: {{ ($this->statistik['monev']['avg'] / 5) * 100 }}%"></div>
                            </div>
                        </div>
                        <div>
                            <div class="flex justify-between text-xs mb-1">
                                <span class="text-gray-500 dark:text-gray-400">Partisipasi</span>
                                <span class="font-bold text-purple-600 dark:text-purple-400">{{ $this->statistik['monev']['responden'] }}/{{ $this->statistik['monev']['total_peserta'] }}</span>
                            </div>
                            <div class="w-full bg-gray-100 dark:bg-gray-700 rounded-full h-1.5">
                                <div class="bg-purple-400 h-1.5 rounded-full" style="width: {{ $this->statistik['monev']['percentage'] }}%"></div>
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
                        <a href="{{ \App\Filament\Clusters\Evaluasi\Resources\TesResource::getUrl('create', ['pelatihan_id' => $record->id, 'kompetensi_id' => $kompetensiPelatihan->kompetensi_id]) }}" class="text-sm bg-primary-600 text-white px-3 py-1.5 rounded hover:bg-primary-700 transition-colors flex items-center">
                            <x-heroicon-o-plus class="w-4 h-4 mr-1" /> Buat Kuesioner Baru
                        </a>
                    </div>
                </div>
                <div class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse($this->tes as $tes)
                    <div class="p-5 flex flex-col md:flex-row items-center justify-between gap-4 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                        <div class="flex items-center gap-4 w-full md:w-1/3">
                            <div class="w-10 h-10 rounded-lg bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 flex items-center justify-center font-bold">{{ $loop->iteration }}</div>
                            <div>
                                <h4 class="font-bold text-gray-900 dark:text-white">{{ $tes->judul }}</h4>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $tes->created_at->format('d M Y') }} • {{ $tes->pertanyaan_count ?? 0 }} Soal • {{ ucfirst($tes->tipe) }}</p>
                            </div>
                        </div>
                        <div class="w-full md:w-1/3 flex flex-col items-center">
                            <span class="text-xs text-gray-500 dark:text-gray-400 mb-1">Durasi</span>
                            <span class="text-xs font-bold text-gray-700 dark:text-gray-300 mt-1">{{ $tes->durasi_menit }} Menit</span>
                        </div>
                        <div class="w-full md:w-auto flex gap-2">
                            <a href="{{ \App\Filament\Clusters\Evaluasi\Resources\TesResource::getUrl('edit', ['record' => $tes->id]) }}" class="px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg text-sm hover:text-blue-600 dark:hover:text-blue-400 hover:border-blue-500">Edit</a>
                        </div>
                    </div>
                    @empty
                    <div class="p-5 text-center text-gray-500 dark:text-gray-400">
                        Belum ada tes atau kuesioner yang dibuat.
                    </div>
                    @endforelse
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
                    {{ $this->addParticipantAction }}
                </div>
            </div>

            <!-- Tabel Peserta -->
            <div class="border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-900/50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Nama Peserta</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Pretest</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Posttest</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                        @forelse($this->peserta as $peserta)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                            <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900 dark:text-white flex items-center gap-3">

                                {{ $peserta->nama }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-gray-600 dark:text-gray-300">-</td>
                            <td class="px-6 py-4 whitespace-nowrap text-center font-bold text-green-600 dark:text-green-400">-</td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <span class="bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-400 text-xs px-2 py-0.5 rounded font-medium">Terdaftar</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                <button class="text-gray-400 hover:text-blue-600 dark:hover:text-blue-400"><x-heroicon-o-ellipsis-vertical class="w-5 h-5" /></button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">Belum ada peserta di kelas ini.</td>
                        </tr>
                        @endforelse
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
