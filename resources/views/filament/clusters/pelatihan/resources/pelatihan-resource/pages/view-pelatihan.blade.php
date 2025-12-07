<x-filament-panels::page>

    <!-- 4. TABS & MAIN CONTENT AREA -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden min-h-[500px]" x-data="{ activeTab: 'kompetensi' }">
        <!-- Tabs Header -->
        <div class="border-b border-gray-200 dark:border-gray-700 px-6 overflow-x-auto">
            <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                <button @click="activeTab = 'kompetensi'" :class="{ 'border-primary-600 text-primary-600 dark:text-primary-400 dark:border-primary-400': activeTab === 'kompetensi', 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-600': activeTab !== 'kompetensi' }" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors flex items-center">
                    <x-heroicon-o-academic-cap class="w-4 h-4 mr-2" />Daftar Kompetensi
                </button>
                <button @click="activeTab = 'peserta'" :class="{ 'border-primary-600 text-primary-600 dark:text-primary-400 dark:border-primary-400': activeTab === 'peserta', 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-600': activeTab !== 'peserta' }" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors flex items-center">
                    <x-heroicon-o-users class="w-4 h-4 mr-2" />Data Peserta
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

        <!-- CONTENT 1: DAFTAR KOMPETENSI -->
        <div x-show="activeTab === 'kompetensi'" class="p-6 bg-gray-50/30 dark:bg-gray-900/30 min-h-[400px]">
           
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($record->kompetensiPelatihan as $kompetensi)
                {{-- Pastikan route 'view-kompetensi' ada atau sesuaikan --}}
                <a href="{{ \App\Filament\Clusters\Pelatihan\Resources\PelatihanResource::getUrl('view-kompetensi', ['record' => $record, 'kompetensi_id' => $kompetensi->id]) }}" class="group block bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-5 hover:border-blue-400 dark:hover:border-blue-500 hover:shadow-md transition-all relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-16 h-16 bg-blue-50 dark:bg-blue-900/20 rounded-bl-full -mr-8 -mt-8 transition-all group-hover:bg-blue-100 dark:group-hover:bg-blue-900/30"></div>
                    <div class="flex justify-between items-start relative z-10">
                        <div class="flex gap-4">

                            <div>
                                <h4 class="text-lg font-bold text-gray-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">{{ $kompetensi->kompetensi->nama_kompetensi ?? 'Nama Kompetensi' }}</h4>
                                <div class="flex items-center gap-2 mt-1">
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Mentor: {{ !empty($kompetensi->nama_instruktur) ? $kompetensi->nama_instruktur : ($kompetensi->instruktur->nama ?? 'Belum ditentukan') }}</p>
                                </div>
                            </div>
                        </div>
                        <span class="bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 text-xs px-2.5 py-1 rounded-full font-medium border border-gray-200 dark:border-gray-600">{{ $kompetensi->pendaftaranPelatihan()->count() }} Peserta</span>
                    </div>
                    <div class="mt-5 pt-4 border-t border-gray-100 dark:border-gray-700 flex items-center justify-end text-sm">
                        <span class="text-primary-600 dark:text-primary-400 font-medium text-xs flex items-center group-hover:underline">Lihat Detail <x-heroicon-o-arrow-right class="w-3 h-3 ml-1" /></span>
                    </div>
                </a>
                @endforeach
            </div>
        </div>

        <!-- CONTENT 2: DATA PESERTA -->
        <div x-show="activeTab === 'peserta'" class="p-6 bg-white dark:bg-gray-800 min-h-[400px]">
            <div class="mt-4">
                 @livewire(\App\Filament\Clusters\Pelatihan\Resources\PelatihanResource\Widgets\PesertaPelatihanTable::class, ['record' => $record])
            </div>
        </div>



        <!-- CONTENT 4: INSTRUKTUR -->
        <div x-show="activeTab === 'admin'" class="p-6 bg-white dark:bg-gray-800 min-h-[400px]">
            <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
                <h3 class="text-lg font-bold text-gray-800 dark:text-white">Daftar Instruktur & Mentor</h3>
                {{ $this->addInstructorAction }}
            </div>
             <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($record->kompetensiPelatihan as $kompetensi)
                    @if($kompetensi->nama_instruktur || $kompetensi->instruktur)
                    <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-5 hover:shadow-md transition-shadow relative group">
                        <div class="flex items-center gap-4 mb-4">
                            <div class="w-14 h-14 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center text-xl font-bold text-gray-500 dark:text-gray-400">
                                {{ substr($kompetensi->nama_instruktur ?? $kompetensi->instruktur->nama ?? 'IN', 0, 2) }}
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-900 dark:text-white">{{ $kompetensi->nama_instruktur ?? $kompetensi->instruktur->nama ?? '-' }}</h4>
                                <p class="text-xs text-blue-600 dark:text-blue-400 font-medium bg-blue-50 dark:bg-blue-900/20 px-2 py-0.5 rounded-full w-fit mt-1">{{ $kompetensi->kompetensi->nama_kompetensi ?? 'Kompetensi' }}</p>
                            </div>
                        </div>
                        <div class="space-y-2 text-sm text-gray-600 dark:text-gray-400 mb-4">
                             @if($kompetensi->instruktur && $kompetensi->instruktur->user)
                                <div class="flex items-center gap-2"><x-heroicon-o-envelope class="w-4 h-4 text-gray-400" /> {{ $kompetensi->instruktur->user->email ?? '-' }}</div>
                             @endif
                        </div>
                    </div>
                    @endif
                @endforeach
            </div>
        </div>

        <!-- CONTENT 5: ASRAMA -->
        <div x-show="activeTab === 'asrama'" class="p-6 bg-gray-50/30 dark:bg-gray-900/30 min-h-[400px]">
            <div class="mt-4">
                 @livewire(\App\Filament\Clusters\Pelatihan\Resources\PelatihanResource\Widgets\AsramaPelatihanTable::class, ['record' => $record])
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
