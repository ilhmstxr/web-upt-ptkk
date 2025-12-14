<x-filament-panels::page>

    <!-- TABS & MAIN CONTENT AREA -->
    <div
        class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden min-h-[500px]"
        x-data="{ activeTab: 'kompetensi' }">
        <!-- Tabs Header -->
        <div class="border-b border-gray-200 dark:border-gray-700 px-6 overflow-x-auto">
            <nav class="-mb-px flex space-x-8" aria-label="Tabs">

                {{-- TAB: KOMPETENSI --}}
                <button
                    @click="activeTab = 'kompetensi'"
                    :class="{
                        'border-primary-600 text-primary-600 dark:text-primary-400 dark:border-primary-400': activeTab === 'kompetensi',
                        'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-600': activeTab !== 'kompetensi'
                    }"
                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors flex items-center">
                    <x-heroicon-o-academic-cap class="w-4 h-4 mr-2" />
                    Daftar Kompetensi
                </button>

                {{-- TAB: PESERTA --}}
                <button
                    @click="activeTab = 'peserta'"
                    :class="{
                        'border-primary-600 text-primary-600 dark:text-primary-400 dark:border-primary-400': activeTab === 'peserta',
                        'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-600': activeTab !== 'peserta'
                    }"
                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors flex items-center">
                    <x-heroicon-o-users class="w-4 h-4 mr-2" />
                    Data Peserta
                </button>

                {{-- TAB: INSTRUKTUR --}}
                <button
                    @click="activeTab = 'admin'"
                    :class="{
                        'border-primary-600 text-primary-600 dark:text-primary-400 dark:border-primary-400': activeTab === 'admin',
                        'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-600': activeTab !== 'admin'
                    }"
                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors flex items-center">
                    <x-heroicon-o-user-group class="w-4 h-4 mr-2" />
                    Instruktur
                </button>

                {{-- TAB: MATERI (BARU) --}}
                <button
                    @click="activeTab = 'materi'"
                    :class="{
                        'border-primary-600 text-primary-600 dark:text-primary-400 dark:border-primary-400': activeTab === 'materi',
                        'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-600': activeTab !== 'materi'
                    }"
                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors flex items-center">
                    <x-heroicon-o-book-open class="w-4 h-4 mr-2" />
                    Materi
                </button>

                {{-- TAB: ASRAMA --}}
                <button
                    @click="activeTab = 'asrama'"
                    :class="{
                        'border-primary-600 text-primary-600 dark:text-primary-400 dark:border-primary-400': activeTab === 'asrama',
                        'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-600': activeTab !== 'asrama'
                    }"
                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors flex items-center">
                    <x-heroicon-o-home-modern class="w-4 h-4 mr-2" />
                    Asrama
                </button>

                {{-- TAB: HASIL --}}
                <button
                    @click="activeTab = 'hasil'"
                    :class="{
                        'border-primary-600 text-primary-600 dark:text-primary-400 dark:border-primary-400': activeTab === 'hasil',
                        'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-600': activeTab !== 'hasil'
                    }"
                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors flex items-center">
                    <x-heroicon-o-chart-pie class="w-4 h-4 mr-2" />
                    Hasil Tes & Survey
                </button>

            </nav>
        </div>

        {{-- =========================================================
            CONTENT 1: DAFTAR KOMPETENSI
        ========================================================= --}}
        <div
            x-show="activeTab === 'kompetensi'"
            class="p-6 bg-gray-50/30 dark:bg-gray-900/30 min-h-[400px]">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($record->kompetensiPelatihan as $kompetensi)
                <a
                    href="{{ \App\Filament\Clusters\Pelatihan\Resources\PelatihanResource::getUrl('view-kompetensi', ['record' => $record, 'kompetensi_id' => $kompetensi->id]) }}"
                    class="group block bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-5 hover:border-blue-400 dark:hover:border-blue-500 hover:shadow-md transition-all relative overflow-hidden">
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
                                        @if($kompetensi->instrukturs->count() > 0)
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
            class="p-6 bg-white dark:bg-gray-800 min-h-[400px]">
            <div class="mt-4">
                @livewire(
                \App\Filament\Clusters\Pelatihan\Resources\PelatihanResource\Widgets\PesertaPelatihanTable::class,
                ['record' => $record]
                )
            </div>
        </div>

        {{-- =========================================================
            CONTENT 3: INSTRUKTUR (MULTI)
        ========================================================= --}}
        <div
            x-show="activeTab === 'admin'"
            class="p-6 bg-white dark:bg-gray-800 min-h-[400px]">
            <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
                <h3 class="text-lg font-bold text-gray-800 dark:text-white">
                    Daftar Instruktur & Mentor
                </h3>
                {{ $this->addInstructorAction }}
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($record->kompetensiPelatihan as $kompetensi)
                @foreach($kompetensi->instrukturs as $instruktur)
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
            CONTENT 4: MATERI (BARU)
        ========================================================= --}}
        <div
            x-show="activeTab === 'materi'"
            class="p-6 bg-white dark:bg-gray-800 min-h-[400px]">
            <div class="mt-4">
                @livewire(
                \App\Filament\Clusters\Pelatihan\Resources\PelatihanResource\Widgets\MateriPelatihanTable::class,
                ['record' => $record]
                )
            </div>
        </div>

        {{-- =========================================================
            CONTENT 5: ASRAMA
        ========================================================= --}}
        <div
            x-show="activeTab === 'asrama'"
            class="p-6 bg-gray-50/30 dark:bg-gray-900/30 min-h-[400px]">
            <div class="mt-4">
                @livewire(
                \App\Filament\Clusters\Pelatihan\Resources\PelatihanResource\Widgets\AsramaPelatihanTable::class,
                ['record' => $record]
                )
            </div>
        </div>

        {{-- =========================================================
            CONTENT 6: HASIL TES / SURVEY
            - UI lengkap versi 1
            - + chart livewire versi 2
        ========================================================= --}}
        <div
            x-show="activeTab === 'hasil'"
            class="p-6 bg-white dark:bg-gray-800 min-h-[400px]">
            @php
            $evalData = $this->getEvaluationData();
            @endphp

            <div class="flex justify-between items-center mb-6">
                <div>
                    <h3 class="text-lg font-bold text-gray-800 dark:text-white">
                        Laporan Hasil Evaluasi {{ $record->nama_pelatihan }}
                    </h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400">
                        Rekapitulasi nilai pretest, posttest, dan survey kepuasan.
                    </p>
                </div>
            </div>

            @if($evalData['hasData'])
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <!-- CHART 1: Rata-rata Pretest vs Posttest -->
                <div class="bg-blue-50/50 dark:bg-blue-900/10 rounded-2xl p-6 border border-blue-100 dark:border-blue-800">
                    <h4 class="text-sm font-bold text-blue-900 dark:text-blue-100 mb-6">Rata-rata Pretest vs Posttest</h4>
                    <div class="flex items-end justify-center gap-8 h-32 mb-4 px-8">
                        <!-- Pretest Bar -->
                        <div class="w-full flex flex-col justify-end items-center gap-2 group">
                            <span class="text-sm font-bold text-gray-600 dark:text-gray-300">{{ $evalData['avgPretest'] }}</span>
                            <div class="w-full bg-blue-300 dark:bg-blue-500 rounded-t-lg transition-all group-hover:bg-blue-400" style="height: {{ min(100, max(10, ($evalData['avgPretest']/100)*100)) }}%;"></div>
                            <span class="text-xs font-medium text-gray-500">Pretest</span>
                        </div>
                        <!-- Posttest Bar -->
                        <div class="w-full flex flex-col justify-end items-center gap-2 group">
                            <span class="text-sm font-bold text-blue-700 dark:text-blue-300">{{ $evalData['avgPosttest'] }}</span>
                            <div class="w-full bg-blue-600 dark:bg-blue-400 rounded-t-lg transition-all group-hover:bg-blue-500 shadow-lg shadow-blue-600/20" style="height: {{ min(100, max(10, ($evalData['avgPosttest']/100)*100)) }}%;"></div>
                            <span class="text-xs font-medium text-gray-500">Posttest</span>
                        </div>
                    </div>
                    <div class="bg-blue-100 dark:bg-blue-800/50 rounded-lg py-2 px-4 text-center">
                        <span class="text-xs font-bold text-blue-700 dark:text-blue-300">Kenaikan +{{ $evalData['improvement'] }}</span>
                    </div>
                </div>

                <!-- CHART 2: Tingkat Kepuasan (CSAT) -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 border border-gray-100 dark:border-gray-700 flex flex-col items-center justify-center text-center">
                    <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-4">Tingkat Kepuasan (CSAT)</h4>
                    <div class="relative w-32 h-32 flex items-center justify-center">
                        <div class="w-full h-full rounded-full border-8 border-gray-100 dark:border-gray-700"></div>
                        <div class="absolute w-full h-full rounded-full border-8 border-green-500 border-t-transparent border-l-transparent transform -rotate-45" style="clip-path: circle(50%);"></div>
                        <div class="absolute inset-0 flex items-center justify-center flex-col">
                            <span class="text-4xl font-black text-gray-800 dark:text-white">{{ $evalData['csat'] }}</span>
                        </div>
                    </div>
                    <div class="flex gap-1 text-yellow-400 mt-2 mb-1">
                        @for($i=1; $i
                        <=5; $i++)
                            <x-heroicon-s-star class="w-4 h-4 {{ $i <= round($evalData['csat']) ? 'text-yellow-400' : 'text-gray-300 dark:text-gray-600' }}" />
                        @endfor
                    </div>
                    <span class="text-xs text-gray-400">Dari {{ $evalData['respondents'] }} Responden</span>
                </div>
            </div>

            <!-- TABLE: Rincian per Kompetensi -->
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden mb-8">
                <div class="p-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50">
                    <h4 class="text-sm font-bold text-gray-900 dark:text-white">Rincian Nilai per Kompetensi</h4>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="text-xs text-gray-500 uppercase bg-gray-50 dark:bg-gray-700/50 dark:text-gray-400">
                            <tr>
                                <th class="px-6 py-3 font-medium">Nama Kompetensi</th>
                                <th class="px-6 py-3 font-medium text-center">Avg Pretest</th>
                                <th class="px-6 py-3 font-medium text-center">Avg Posttest</th>
                                <th class="px-6 py-3 font-medium text-center">Kepuasan</th>
                                <th class="px-6 py-3 font-medium text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                            @forelse($evalData['competencies'] as $comp)
                            <tr class="bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">{{ $comp['name'] }}</td>
                                <td class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">{{ $comp['pretest'] }}</td>
                                <td class="px-6 py-4 text-center font-bold text-blue-600 dark:text-blue-400">{{ $comp['posttest'] }}</td>
                                <td class="px-6 py-4 text-center text-orange-500 font-bold">{{ $comp['kepuasan'] }}</td>
                                <td class="px-6 py-4 text-center">
                                    <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ $comp['status_color'] }}-100 text-{{ $comp['status_color'] }}-800 dark:bg-{{ $comp['status_color'] }}-900/30 dark:text-{{ $comp['status_color'] }}-400 border border-{{ $comp['status_color'] }}-200 dark:border-{{ $comp['status_color'] }}-800">
                                        {{ $comp['status'] }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-gray-400">Belum ada data kompetensi.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- CHART LIVEWIRE (versi 2) --}}
            <div class="grid grid-cols-1 gap-6">
                @livewire(\App\Filament\Clusters\Pelatihan\Resources\PelatihanResource\Widgets\JawabanAkumulatifChart::class, ['record' => $record])
                @livewire(\App\Filament\Clusters\Pelatihan\Resources\PelatihanResource\Widgets\JawabanPerKategoriChart::class, ['record' => $record])
                @livewire(\App\Filament\Clusters\Pelatihan\Resources\PelatihanResource\Widgets\PiePerPertanyaanWidget::class, ['record' => $record])
            </div>
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