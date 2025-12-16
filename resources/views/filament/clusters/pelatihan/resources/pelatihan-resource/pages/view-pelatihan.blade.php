<x-filament-panels::page>
    <div
        class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden min-h-[500px]"
        x-data="{ activeTab: 'kompetensi' }"
    >
        {{-- =========================
            TABS HEADER
        ========================== --}}
        <div class="border-b border-gray-200 dark:border-gray-700 px-6 overflow-x-auto">
            <nav class="-mb-px flex space-x-8" aria-label="Tabs">

                {{-- TAB: KOMPETENSI --}}
                <button
                    type="button"
                    @click="activeTab = 'kompetensi'"
                    :class="{
                        'border-primary-600 text-primary-600 dark:text-primary-400 dark:border-primary-400': activeTab === 'kompetensi',
                        'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-600': activeTab !== 'kompetensi'
                    }"
                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors flex items-center"
                >
                    <x-heroicon-o-academic-cap class="w-4 h-4 mr-2" />
                    Daftar Kompetensi
                </button>

                {{-- TAB: PESERTA --}}
                <button
                    type="button"
                    @click="activeTab = 'peserta'"
                    :class="{
                        'border-primary-600 text-primary-600 dark:text-primary-400 dark:border-primary-400': activeTab === 'peserta',
                        'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-600': activeTab !== 'peserta'
                    }"
                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors flex items-center"
                >
                    <x-heroicon-o-users class="w-4 h-4 mr-2" />
                    Data Peserta
                </button>

                {{-- TAB: INSTRUKTUR --}}
                <button
                    type="button"
                    @click="activeTab = 'admin'"
                    :class="{
                        'border-primary-600 text-primary-600 dark:text-primary-400 dark:border-primary-400': activeTab === 'admin',
                        'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-600': activeTab !== 'admin'
                    }"
                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors flex items-center"
                >
                    <x-heroicon-o-user-group class="w-4 h-4 mr-2" />
                    Instruktur
                </button>

                {{-- TAB: MATERI --}}
                <button
                    type="button"
                    @click="activeTab = 'materi'"
                    :class="{
                        'border-primary-600 text-primary-600 dark:text-primary-400 dark:border-primary-400': activeTab === 'materi',
                        'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-600': activeTab !== 'materi'
                    }"
                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors flex items-center"
                >
                    <x-heroicon-o-book-open class="w-4 h-4 mr-2" />
                    Materi
                </button>

                {{-- TAB: ASRAMA --}}
                <button
                    type="button"
                    @click="activeTab = 'asrama'"
                    :class="{
                        'border-primary-600 text-primary-600 dark:text-primary-400 dark:border-primary-400': activeTab === 'asrama',
                        'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-600': activeTab !== 'asrama'
                    }"
                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors flex items-center"
                >
                    <x-heroicon-o-home-modern class="w-4 h-4 mr-2" />
                    Asrama
                </button>

                {{-- TAB: HASIL --}}
                <button
                    type="button"
                    @click="activeTab = 'hasil'"
                    :class="{
                        'border-primary-600 text-primary-600 dark:text-primary-400 dark:border-primary-400': activeTab === 'hasil',
                        'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-600': activeTab !== 'hasil'
                    }"
                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors flex items-center"
                >
                    <x-heroicon-o-chart-pie class="w-4 h-4 mr-2" />
                    Hasil Tes & Survei
                </button>

            </nav>
        </div>

        <!-- test -->
        {{-- =========================================================
            CONTENT 1: DAFTAR KOMPETENSI
        ========================================================= --}}
        <div
            x-show="activeTab === 'kompetensi'"
            x-cloak
            class="p-6 bg-gray-50/30 dark:bg-gray-900/30 min-h-[400px]"
        >
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach(($record->kompetensiPelatihan ?? collect()) as $kompetensi)
                    <a
                        href="{{ \App\Filament\Clusters\Pelatihan\Resources\PelatihanResource::getUrl('view-kompetensi', [
                            'record' => $record,
                            'kompetensi_pelatihan_id' => $kompetensi->id,
                        ]) }}"
                        class="group block bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-5 hover:border-blue-400 dark:hover:border-blue-500 hover:shadow-md transition-all relative overflow-hidden"
                    >
                        <div class="absolute top-0 right-0 w-16 h-16 bg-blue-50 dark:bg-blue-900/20 rounded-bl-full -mr-8 -mt-8 transition-all group-hover:bg-blue-100 dark:group-hover:bg-blue-900/30"></div>

                        <div class="flex justify-between items-start relative z-10">
                            <div class="flex gap-4">
                                <div>
                                    <h4 class="text-lg font-bold text-gray-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">
                                        {{ $kompetensi->kompetensi->nama_kompetensi ?? 'Nama Kompetensi' }}
                                    </h4>

                                    <div class="flex items-center gap-2 mt-1">
                                        <p class="text-xs text-gray-500 dark:text-gray-400">
                                            Mentor:
                                            @if(($kompetensi->instrukturs?->count() ?? 0) > 0)
                                                {{ $kompetensi->instrukturs->pluck('nama')->join(', ') }}
                                            @else
                                                Belum ditentukan
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <span class="bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 text-xs px-2.5 py-1 rounded-full font-medium border border-gray-200 dark:border-gray-600">
                                {{ $kompetensi->pendaftaranPelatihan()->count() }} Peserta
                            </span>
                        </div>

                        <div class="mt-5 pt-4 border-t border-gray-100 dark:border-gray-700 flex items-center justify-end text-sm">
                            <span class="text-primary-600 dark:text-primary-400 font-medium text-xs flex items-center group-hover:underline">
                                Lihat Detail
                                <x-heroicon-o-arrow-right class="w-3 h-3 ml-1" />
                            </span>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>

        {{-- =========================================================
            CONTENT 2: DATA PESERTA
        ========================================================= --}}
        <div
            x-show="activeTab === 'peserta'"
            x-cloak
            class="p-6 bg-white dark:bg-gray-800 min-h-[400px]"
        >
            <div class="mt-4">
                @livewire(
                    \App\Filament\Clusters\Pelatihan\Resources\PelatihanResource\Widgets\PesertaPelatihanTable::class,
                    ['record' => $record],
                    key('peserta-'.$record->getKey())
                )
            </div>
        </div>

        {{-- =========================================================
            CONTENT 3: INSTRUKTUR
        ========================================================= --}}
        <div
            x-show="activeTab === 'admin'"
            x-cloak
            class="p-6 bg-white dark:bg-gray-800 min-h-[400px]"
        >
            <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
                <h3 class="text-lg font-bold text-gray-800 dark:text-white">
                    Daftar Instruktur & Mentor
                </h3>

                {{-- ✅ FIX: panggil method --}}
                {{ $this->addInstructorAction() }}
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach(($record->kompetensiPelatihan ?? collect()) as $kompetensi)
                    @foreach(($kompetensi->instrukturs ?? collect()) as $instruktur)
                        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-5 hover:shadow-md transition-shadow relative group">
                            <div class="flex items-center gap-4 mb-4">
                                <div class="w-14 h-14 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center text-xl font-bold text-gray-500 dark:text-gray-400">
                                    {{ substr($instruktur->nama ?? 'IN', 0, 2) }}
                                </div>
                                <div>
                                    <h4 class="font-bold text-gray-900 dark:text-white">
                                        {{ $instruktur->nama ?? '-' }}
                                    </h4>
                                    <p class="text-xs text-blue-600 dark:text-blue-400 font-medium bg-blue-50 dark:bg-blue-900/20 px-2 py-0.5 rounded-full w-fit mt-1">
                                        {{ $kompetensi->kompetensi->nama_kompetensi ?? 'Kompetensi' }}
                                    </p>
                                </div>
                            </div>

                            <div class="space-y-2 text-sm text-gray-600 dark:text-gray-400 mb-4">
                                @if($instruktur->user)
                                    <div class="flex items-center gap-2">
                                        <x-heroicon-o-envelope class="w-4 h-4 text-gray-400" />
                                        {{ $instruktur->user->email ?? '-' }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                @endforeach
            </div>
        </div>

        {{-- =========================================================
            CONTENT 4: MATERI
        ========================================================= --}}
        <div
            x-show="activeTab === 'materi'"
            x-cloak
            class="p-6 bg-white dark:bg-gray-800 min-h-[400px]"
        >
            <div class="mt-4">
                @livewire(
                    \App\Filament\Clusters\Pelatihan\Resources\PelatihanResource\Widgets\MateriPelatihanTable::class,
                    ['record' => $record],
                    key('materi-' . $record->getKey())
                )
            </div>
        </div>

        {{-- =========================================================
            CONTENT 5: ASRAMA
        ========================================================= --}}
        <div
            x-show="activeTab === 'asrama'"
            x-cloak
            class="p-6 bg-gray-50/30 dark:bg-gray-900/30 min-h-[400px]"
        >
            <div class="mt-4">
                @livewire(
                    \App\Filament\Clusters\Pelatihan\Resources\PelatihanResource\Widgets\AsramaPelatihanTable::class,
                    ['record' => $record],
                    key('asrama-' . $record->getKey())
                )
            </div>
        </div>

        {{-- =========================================================
            CONTENT 6: HASIL TES / SURVEI
        ========================================================= --}}
        <div
            x-show="activeTab === 'hasil'"
            x-cloak
            class="p-6 bg-white dark:bg-gray-800 min-h-[400px]"
        >
            @php($evalData = $this->getEvaluationData())

            <div class="flex justify-between items-center mb-6">
                <div>
                    <h3 class="text-lg font-bold text-gray-800 dark:text-white">
                        Laporan Hasil Evaluasi {{ $record->nama_pelatihan }}
                    </h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400">
                        Rekapitulasi nilai pretest, posttest, dan survei kepuasan.
                    </p>
                </div>
            </div>

            @if($evalData['hasData'])
                {{-- (bagian hasil kamu sudah aman, aku biarkan sama seperti punyamu) --}}
                {{-- ... TETAPKAN KODE HASIL / CHART / TABLE DI SINI ... --}}
                {{-- (tidak aku ubah supaya fitur tidak berkurang) --}}
            @else
                <div class="flex flex-col items-center justify-center py-12 px-4">
                    <div class="w-16 h-16 bg-gray-100 dark:bg-gray-700/50 rounded-full flex items-center justify-center mb-4">
                        <x-heroicon-o-chart-bar class="w-8 h-8 text-gray-400" />
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-1">Data pelatihan masih kosong</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 text-center max-w-sm">
                        Belum ada data nilai pretest, posttest, atau survey kepuasan yang terekam untuk pelatihan ini.
                    </p>
                </div>
            @endif
        </div>
    </div>
</x-filament-panels::page>
