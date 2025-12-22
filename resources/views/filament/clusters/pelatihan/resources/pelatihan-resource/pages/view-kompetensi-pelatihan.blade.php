<x-filament-panels::page>
    <div x-data="{ activeTab: 'hasil' }" class="space-y-6">
        <!-- Navigation Tabs -->
        <div class="border-b border-gray-200 dark:border-gray-700">
            <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                <button
                    @click="activeTab = 'hasil'"
                    :class="{ 'border-primary-500 text-primary-600 dark:text-primary-400': activeTab === 'hasil', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300': activeTab !== 'hasil' }"
                    class="group inline-flex items-center py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                    <x-heroicon-o-chart-bar class="w-5 h-5 mr-2" x-bind:class="{ 'text-primary-500': activeTab === 'hasil', 'text-gray-400 group-hover:text-gray-500': activeTab !== 'hasil' }" />
                    Hasil Tes & Survei
                </button>

                <button
                    @click="activeTab = 'peserta'"
                    :class="{ 'border-primary-500 text-primary-600 dark:text-primary-400': activeTab === 'peserta', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300': activeTab !== 'peserta' }"
                    class="group inline-flex items-center py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                    <x-heroicon-o-users class="w-5 h-5 mr-2" x-bind:class="{ 'text-primary-500': activeTab === 'peserta', 'text-gray-400 group-hover:text-gray-500': activeTab !== 'peserta' }" />
                    Peserta Kelas
                </button>

                <button
                    @click="activeTab = 'nilai'"
                    :class="{ 'border-primary-500 text-primary-600 dark:text-primary-400': activeTab === 'nilai', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300': activeTab !== 'nilai' }"
                    class="group inline-flex items-center py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                    <x-heroicon-o-academic-cap class="w-5 h-5 mr-2" x-bind:class="{ 'text-primary-500': activeTab === 'nilai', 'text-gray-400 group-hover:text-gray-500': activeTab !== 'nilai' }" />
                    Daftar Nilai
                </button>

                <button
                    @click="activeTab = 'kurikulum'"
                    :class="{ 'border-primary-500 text-primary-600 dark:text-primary-400': activeTab === 'kurikulum', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300': activeTab !== 'kurikulum' }"
                    class="group inline-flex items-center py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                    <x-heroicon-o-book-open class="w-5 h-5 mr-2" x-bind:class="{ 'text-primary-500': activeTab === 'kurikulum', 'text-gray-400 group-hover:text-gray-500': activeTab !== 'kurikulum' }" />
                    Jadwal & Kurikulum
                </button>
            </nav>
        </div>

        <!-- TAB CONTENT: HASIL TES & SURVEI -->
        <div x-show="activeTab === 'hasil'" class="space-y-6">
            @php
            $isSelesai = $this->status === 'Selesai';
            @endphp

            <!-- A. Statistik Utama (Cards) -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- 1. PRETEST STATS -->
                <div class="bg-white dark:bg-gray-900 p-6 rounded-xl shadow-sm ring-1 ring-gray-950/5 dark:ring-white/10 relative overflow-hidden group hover:shadow-md transition-shadow">
                    <div class="flex justify-between items-start mb-6 border-b border-gray-100 dark:border-gray-800 pb-4">
                        <div>
                            <h3 class="font-bold text-gray-900 dark:text-white text-lg tracking-tight">Pre-Test</h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Nilai Awal Peserta</p>
                        </div>
                        @if($isSelesai)
                        <span class="bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400 text-[10px] uppercase px-2.5 py-1 rounded-full font-bold tracking-wide border border-gray-200 dark:border-gray-700">
                            Selesai
                        </span>
                        @else
                        <span class="bg-blue-50 dark:bg-blue-900/20 text-blue-700 dark:text-blue-400 text-[10px] uppercase px-2.5 py-1 rounded-full font-bold tracking-wide border border-blue-100 dark:border-blue-900/50">
                            Selesai
                        </span>
                        @endif
                    </div>

                    <div class="flex gap-5 items-center">
                        <div class="relative w-20 h-20 shrink-0">
                            <div class="absolute inset-0 rounded-full border-[6px] {{ $isSelesai ? 'border-gray-100 dark:border-gray-800' : 'border-blue-50 dark:border-blue-900/20' }}"></div>
                            <div class="absolute inset-0 flex flex-col items-center justify-center rounded-full">
                                <span class="text-3xl font-bold {{ $isSelesai ? 'text-gray-500 dark:text-gray-400' : 'text-blue-600 dark:text-blue-500' }}">
                                    {{ $statistik['pretest']['avg'] ?? 0 }}
                                </span>
                                <span class="text-[10px] text-gray-400 font-semibold uppercase tracking-wider mt-0.5">Rata-rata</span>
                            </div>
                        </div>

                        <div class="flex-1 space-y-3">
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-gray-500 dark:text-gray-400">Tertinggi</span>
                                <span class="font-bold text-gray-900 dark:text-white font-mono">
                                    {{ $statistik['pretest']['max'] ?? 0 }}
                                </span>
                            </div>

                            <div class="flex justify-between items-center text-sm">
                                <span class="text-gray-500 dark:text-gray-400">Terendah</span>
                                <span class="font-bold text-gray-900 dark:text-white font-mono">
                                    {{ $statistik['pretest']['min'] ?? 0 }}
                                </span>
                            </div>

                            <div class="flex justify-between items-center text-sm border-t border-dashed border-gray-200 dark:border-gray-700 pt-2">
                                <span class="text-gray-500 dark:text-gray-400">Partisipasi</span>
                                <span class="font-bold text-gray-900 dark:text-white">
                                    {{ $statistik['pretest']['count'] ?? 0 }} Org
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 2. POSTTEST STATS -->
                <div class="bg-white dark:bg-gray-900 p-6 rounded-xl shadow-sm ring-1 ring-gray-950/5 dark:ring-white/10 relative overflow-hidden group hover:shadow-md transition-shadow">
                    <div class="flex justify-between items-start mb-6 border-b border-gray-100 dark:border-gray-800 pb-4">
                        <div>
                            <h3 class="font-bold text-gray-900 dark:text-white text-lg tracking-tight">Post-Test</h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Nilai Akhir & Kelulusan</p>
                        </div>
                        @if($isSelesai)
                        <span class="bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400 text-[10px] uppercase px-2.5 py-1 rounded-full font-bold tracking-wide border border-gray-200 dark:border-gray-700">
                            Final
                        </span>
                        @else
                        <span class="bg-green-50 dark:bg-green-900/20 text-green-700 dark:text-green-400 text-[10px] uppercase px-2.5 py-1 rounded-full font-bold tracking-wide border border-green-100 dark:border-green-900/50">
                            Final
                        </span>
                        @endif
                    </div>

                    <div class="flex gap-5 items-center">
                        <div class="relative w-20 h-20 shrink-0">
                            <div class="absolute inset-0 rounded-full border-[6px] {{ $isSelesai ? 'border-gray-100 dark:border-gray-800' : 'border-green-50 dark:border-green-900/20' }}"></div>
                            <div class="absolute inset-0 flex flex-col items-center justify-center rounded-full">
                                <span class="text-3xl font-bold {{ $isSelesai ? 'text-gray-500 dark:text-gray-400' : 'text-green-600 dark:text-green-500' }}">
                                    {{ $statistik['posttest']['avg'] ?? 0 }}
                                </span>
                                <span class="text-[10px] text-gray-400 font-semibold uppercase tracking-wider mt-0.5">Rata-rata</span>
                            </div>
                        </div>

                        <div class="flex-1 space-y-3">
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-gray-500 dark:text-gray-400">Lulus (>75)</span>
                                <span class="font-bold text-green-600 dark:text-green-400 font-mono">
                                    {{ $statistik['posttest']['lulus'] ?? 0 }} Org
                                </span>
                            </div>

                            <div class="flex justify-between items-center text-sm">
                                <span class="text-gray-500 dark:text-gray-400">Remedial</span>
                                <span class="font-bold text-orange-600 dark:text-orange-400 font-mono">
                                    {{ $statistik['posttest']['remedial'] ?? 0 }} Org
                                </span>
                            </div>

                            <div class="flex justify-between items-center text-sm border-t border-dashed border-gray-200 dark:border-gray-700 pt-2">
                                <span class="text-gray-500 dark:text-gray-400">Partisipasi</span>
                                <span class="font-bold text-gray-900 dark:text-white">
                                    {{ $statistik['posttest']['count'] ?? 0 }}/{{ $statistik['monev']['total_peserta'] ?? 0 }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 3. MONEV STATS (SURVEI) -->
                <div class="bg-white dark:bg-gray-900 p-6 rounded-xl shadow-sm ring-1 ring-gray-950/5 dark:ring-white/10 relative overflow-hidden group hover:shadow-md transition-shadow">
                    <div class="flex justify-between items-start mb-6 border-b border-gray-100 dark:border-gray-800 pb-4">
                        <div>
                            <h3 class="font-bold text-gray-900 dark:text-white text-lg tracking-tight">Survei Monev</h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Analisis Kepuasan</p>
                        </div>
                        @if($isSelesai)
                        <span class="bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400 text-[10px] uppercase px-2.5 py-1 rounded-full font-bold tracking-wide border border-gray-200 dark:border-gray-700">
                            Selesai
                        </span>
                        @else
                        <span class="bg-green-50 dark:bg-green-900/20 text-green-700 dark:text-green-400 text-[10px] uppercase px-2.5 py-1 rounded-full font-bold tracking-wide border border-green-100 dark:border-green-900/50">
                            Aktif
                        </span>
                        @endif
                    </div>

                    <div class="flex gap-5 items-center">
                        <div class="relative w-20 h-20 shrink-0 flex items-center justify-center {{ $isSelesai ? 'bg-gray-50 dark:bg-gray-800 border-gray-200' : 'bg-gray-50 dark:bg-gray-800 border-gray-100 dark:border-gray-700' }} rounded-full border">
                            <x-heroicon-o-star class="w-10 h-10 {{ $isSelesai ? 'text-gray-400' : 'text-yellow-400' }}" />
                        </div>

                        <div class="flex-1 space-y-4">
                            <div>
                                <div class="flex justify-between items-center text-sm mb-2">
                                    <span class="text-gray-500 dark:text-gray-400 font-medium">Total Responden</span>
                                    <span class="font-bold text-gray-900 dark:text-white">{{ $this->statistik['monev']['responden'] }} Org</span>
                                </div>

                                <div class="w-full bg-gray-100 dark:bg-gray-800 rounded-full h-2.5 overflow-hidden">
                                    <div class="{{ $isSelesai ? 'bg-gray-400' : 'bg-green-500' }} h-full rounded-full transition-all duration-500 ease-out" style="width:{{ $this->statistik['monev']['percentage'] }}%"></div>
                                </div>
                                <div class="flex justify-end mt-1.5">
                                    <span class="text-xs font-medium {{ $isSelesai ? 'text-gray-500 bg-gray-100' : 'text-green-600 dark:text-green-400 bg-green-50 dark:bg-green-900/20' }} px-1.5 py-0.5 rounded">
                                        {{ number_format($this->statistik['monev']['percentage'], 0) }}% Partisipasi
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- B. Action List (Detail Tugas & Survei) -->
            <div class="bg-white dark:bg-gray-900 ring-1 ring-gray-950/5 dark:ring-white/10 rounded-xl overflow-hidden shadow-sm">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-white/10 bg-gray-50 dark:bg-white/5 flex justify-between items-center">
                    <h3 class="font-bold text-gray-800 dark:text-white">Daftar Tes & Kuesioner</h3>
                    <div class="flex gap-2">
                        @if(!$isSelesai)
                        <a href="{{ \App\Filament\Clusters\Evaluasi\Resources\TesResource::getUrl('create', ['pelatihan_id' => $record->id, 'kompetensi_id' => $kompetensiPelatihan->kompetensi_id]) }}" class="text-sm bg-primary-600 text-white px-3 py-1.5 rounded hover:bg-primary-700 transition-colors flex items-center shadow-sm">
                            <x-heroicon-o-plus class="w-4 h-4 mr-1.5" /> Buat Kuesioner Baru
                        </a>
                        @endif
                    </div>
                </div>
                <div class="divide-y divide-gray-100 dark:divide-white/10">
                    @forelse($this->tes as $tes)
                    <div class="p-5 flex flex-col md:flex-row items-center justify-between gap-4 hover:bg-gray-50 dark:hover:bg-white/5 transition-colors group">
                        <div class="flex items-center gap-4 w-full md:w-5/12">
                            <div class="w-10 h-10 rounded-lg bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-gray-500 dark:text-gray-400 flex items-center justify-center font-bold shadow-sm group-hover:border-primary-200 dark:group-hover:border-primary-900 transition-colors">{{ $loop->iteration }}</div>
                            <div>
                                <h4 class="font-bold text-gray-900 dark:text-white text-sm group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors">{{ $tes->judul }}</h4>
                                <div class="flex items-center gap-2 mt-1">
                                    <span class="text-[10px] uppercase font-semibold tracking-wider px-1.5 py-0.5 rounded {{ $tes->tipe == 'pre-test' ? 'bg-blue-50 text-blue-600 dark:bg-blue-900/30' : ($tes->tipe == 'post-test' ? 'bg-green-50 text-green-600 dark:bg-green-900/30' : 'bg-purple-50 text-purple-600 dark:bg-purple-900/30') }}">
                                        {{ ucfirst($tes->tipe) }}
                                    </span>
                                    <span class="text-xs text-gray-400">•</span>
                                    <span class="text-xs text-gray-500 dark:text-gray-400">{{ $tes->pertanyaan_count ?? 0 }} Soal</span>
                                </div>
                            </div>
                        </div>

                        <div class="w-full md:w-3/12 flex flex-col items-start md:items-center">
                            <div class="flex items-center gap-1.5 text-xs text-gray-500 dark:text-gray-400">
                                <x-heroicon-o-clock class="w-3.5 h-3.5" />
                                <span>Durasi</span>
                            </div>
                            <span class="text-sm font-semibold text-gray-900 dark:text-white mt-1">{{ $tes->durasi_menit }} Menit</span>
                        </div>

                        <div class="w-full md:w-3/12 flex flex-col items-start md:items-center">
                            <div class="flex items-center gap-1.5 text-xs text-gray-500 dark:text-gray-400">
                                <x-heroicon-o-calendar-days class="w-3.5 h-3.5" />
                                <span>Dibuat</span>
                            </div>
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300 mt-1">{{ $tes->created_at->format('d M Y') }}</span>
                        </div>

                        <div class="w-full md:w-1/12 flex justify-end">
                            <a href="{{ \App\Filament\Clusters\Evaluasi\Resources\TesResource::getUrl('edit', ['record' => $tes->id]) }}" class="p-2 text-gray-400 hover:text-primary-600 hover:bg-primary-50 dark:hover:bg-primary-900/20 rounded-lg transition-colors" title="Edit Tes">
                                <x-heroicon-o-pencil-square class="w-5 h-5" />
                            </a>
                        </div>
                    </div>
                    @empty
                    <div class="p-8 text-center">
                        <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-gray-100 dark:bg-gray-800 mb-3">
                            <x-heroicon-o-clipboard-document-list class="w-6 h-6 text-gray-400" />
                        </div>
                        <h3 class="text-sm font-medium text-gray-900 dark:text-white">Belum ada tes</h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 max-w-xs mx-auto">Silakan buat kuesioner baru untuk memulai evaluasi kompetensi ini.</p>
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

            <!-- Livewire Table Peserta -->
            <div class="mt-4">
                @livewire(
                \App\Filament\Clusters\Pelatihan\Resources\PelatihanResource\Widgets\PesertaPelatihanTable::class,
                [
                'record' => $record,
                'kompetensiPelatihanId' => $kompetensiPelatihan->id
                ]
                )
            </div>
        </div>

        <!-- TAB CONTENT: DAFTAR NILAI (NEW) -->
        <div x-show="activeTab === 'nilai'" class="tab-content p-6 bg-white dark:bg-gray-800">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h3 class="font-bold text-lg text-gray-900 dark:text-white">Daftar Nilai Peserta</h3>
                    <p class="text-gray-500 dark:text-gray-400 text-sm">Rekapitulasi nilai Pretest, Posttest, dan Praktek per peserta.</p>
                </div>
            </div>

            <div class="mt-4">
                @livewire(
                \App\Filament\Clusters\Pelatihan\Resources\PelatihanResource\Widgets\NilaiPesertaPelatihanTable::class,
                [
                'record' => $record,
                'kompetensiPelatihanId' => $kompetensiPelatihan->id
                ]
                )
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
