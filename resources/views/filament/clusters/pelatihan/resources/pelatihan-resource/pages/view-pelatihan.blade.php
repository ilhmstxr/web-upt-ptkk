<x-filament-panels::page>
    <div
        class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden min-h-[500px]"
        x-data="{ activeTab: 'kompetensi' }">
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
                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors flex items-center">
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
                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors flex items-center">
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
                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors flex items-center">
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
                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors flex items-center">
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
                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors flex items-center">
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
                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors flex items-center">
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
            class="p-6 bg-gray-50/30 dark:bg-gray-900/30 min-h-[400px]">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach(($record->kompetensiPelatihan ?? collect()) as $kompetensi)
                href="{{ \App\Filament\Clusters\Pelatihan\Resources\PelatihanResource::getUrl('view-kompetensi', [
                            'record' => $record,
                            'kompetensi_id' => $kompetensi->id,
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
            class="p-6 bg-white dark:bg-gray-800 min-h-[400px]">
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
            class="p-6 bg-white dark:bg-gray-800 min-h-[400px]">
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
            class="p-6 bg-white dark:bg-gray-800 min-h-[400px]">
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
            class="p-6 bg-gray-50/30 dark:bg-gray-900/30 min-h-[400px]">
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
            class="p-6 bg-white dark:bg-gray-800 min-h-[400px]">
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
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <!-- CHART 1: Rata-rata Pretest vs Posttest -->
                <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm ring-1 ring-gray-950/5 dark:ring-white/10 p-6">
                    <h4 class="text-base font-semibold text-gray-950 dark:text-white mb-6">Rata-rata Pretest vs Posttest</h4>
                    <div class="flex items-end justify-center gap-8 h-32 mb-4 px-8">
                        <!-- Pretest Bar -->
                        <div class="w-full h-full flex flex-col justify-end items-center gap-2 group">
                            <span class="text-sm font-bold text-gray-600 dark:text-gray-300">{{ $evalData['avgPretest'] }}</span>
                            <div class="w-full bg-sky-300 dark:bg-sky-500 rounded-t-lg transition-all group-hover:bg-sky-400" style="height: 
                            {{ min(100, max(10, ($evalData['avgPretest']/100)*100)) }}%;"></div>
                            <span class="text-xs font-medium text-gray-500">Pretest</span>
                        </div>
                        <!-- Posttest Bar -->
                        <div class="w-full h-full flex flex-col justify-end items-center gap-2 group">
                            <span class="text-sm font-bold text-blue-700 dark:text-blue-300">{{ $evalData['avgPosttest'] }}</span>
                            <div class="w-full bg-blue-600 dark:bg-blue-500 rounded-t-lg transition-all group-hover:bg-blue-500 shadow-lg shadow-blue-600/20" style="height: {{ min(100, max(10, ($evalData['avgPosttest']/100)*100)) }}%;"></div>
                            <span class="text-xs font-medium text-gray-500">Posttest</span>
                        </div>
                    </div>
                    <div class="bg-gray-50 dark:bg-white/5 rounded-lg py-2 px-4 text-center ring-1 ring-gray-950/5 dark:ring-white/10">
                        <span class="text-xs font-bold text-success-600 dark:text-success-400">Kenaikan +{{ $evalData['improvement'] }}</span>
                    </div>
                </div>

                <!-- CHART 2: Tingkat Kepuasan (CSAT) -->
                <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm ring-1 ring-gray-950/5 dark:ring-white/10 p-6 flex flex-col items-center justify-center text-center">
                    <h4 class="text-base font-semibold text-gray-950 dark:text-white mb-4">Tingkat Kepuasan (CSAT)</h4>
                    <div class="relative w-32 h-32 flex items-center justify-center">
                        <div class="w-full h-full rounded-full border-8 border-gray-100 dark:border-gray-800"></div>
                        <div class="absolute w-full h-full rounded-full border-8 border-success-500 border-t-transparent border-l-transparent transform -rotate-45" style="clip-path: circle(50%);"></div>
                        <div class="absolute inset-0 flex items-center justify-center flex-col">
                            <span class="text-4xl font-black text-gray-950 dark:text-white">{{ $evalData['csat'] }}</span>
                        </div>
                    </div>
                    <div class="flex gap-1 text-warning-400 mt-4 mb-2">
                        @for($i=1; $i
                        <=5; $i++)
                            <x-heroicon-s-star class="w-5 h-5 {{ $i <= round($evalData['csat']) ? 'text-warning-400' : 'text-gray-300 dark:text-gray-600' }}" />
                        @endfor
                    </div>
                    <span class="text-sm text-gray-500 dark:text-gray-400">Dari {{ $evalData['respondents'] }} Responden</span>
                </div>
            </div>

            <!-- TABLE: Rincian per Kompetensi -->
            <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm ring-1 ring-gray-950/5 dark:ring-white/10 overflow-hidden mb-8">
                <div class="p-4 border-b border-gray-200 dark:border-white/10 bg-gray-50 dark:bg-white/5">
                    <h4 class="text-base font-semibold text-gray-950 dark:text-white">Rincian Nilai per Kompetensi</h4>
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

            {{-- CHART IMPLEMENTATION (Custom Chart.js via Alpine) --}}
            <div x-data="surveyCharts(@js($evalData))" class="space-y-8 mt-8">
                <!-- CDN Chart.js -->
                <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

                <!-- Top Row Charts -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Total Distribution -->
                    <div class="bg-white dark:bg-gray-900 rounded-xl p-6 shadow-sm ring-1 ring-gray-950/5 dark:ring-white/10">
                        <h4 class="text-base font-semibold text-gray-950 dark:text-white mb-6">Akumulasi Sebaran Jawaban</h4>
                        <div class="relative h-64 w-full">
                            <canvas id="totalChart"></canvas>
                        </div>
                    </div>

                    <!-- Category Stacked Bar -->
                    <div class="bg-white dark:bg-gray-900 rounded-xl p-6 shadow-sm ring-1 ring-gray-950/5 dark:ring-white/10">
                        <h4 class="text-base font-semibold text-gray-950 dark:text-white mb-6">Kepuasan per Aspek</h4>
                        <div class="relative h-64 w-full">
                            <canvas id="categoryChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Detailed Question Charts -->
                <div class="space-y-6">
                    <h3 class="text-lg font-bold text-gray-800 dark:text-white border-b pb-2 dark:border-gray-700">Detail Pertanyaan Survei</h3>

                    <template x-for="(questions, category) in data.question_stats" :key="category">
                        <div class="bg-gray-50/50 dark:bg-white/5 p-6 rounded-xl ring-1 ring-gray-950/5 dark:ring-white/10">
                            <h4 class="font-bold text-primary-600 dark:text-primary-400 mb-6 text-lg flex items-center gap-2">
                                <span class="w-2 h-8 bg-primary-600 rounded-full inline-block"></span>
                                <span x-text="category"></span>
                            </h4>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <template x-for="(q, qIndex) in questions" :key="q.id">
                                    <div class="bg-white dark:bg-gray-800 p-5 rounded-lg shadow-sm border border-gray-100 dark:border-gray-700 flex flex-col">
                                        <p class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-4 min-h-[3rem] line-clamp-2" x-text="q.teks" :title="q.teks"></p>

                                        <div class="flex items-center gap-4 mt-auto">
                                            <!-- Chart (Left) -->
                                            <div class="relative w-32 h-32 flex-shrink-0">
                                                <canvas :id="'qChart-' + q.id"></canvas>
                                            </div>

                                            <!-- Legend (Right) -->
                                            <div class="flex-1 text-[11px] space-y-2">
                                                <template x-for="(item, index) in [
                                                    {label: 'Tidak Memuaskan', val: 1},
                                                    {label: 'Kurang Memuaskan', val: 2},
                                                    {label: 'Memuaskan', val: 3},
                                                    {label: 'Sangat Memuaskan', val: 4}
                                                ]">
                                                    <div class="flex items-center justify-between group">
                                                        <div class="flex items-center gap-1.5 min-w-0">
                                                            <!-- Color Box -->
                                                            <span class="w-2.5 h-2.5 rounded-sm flex-shrink-0" :style="'background-color: ' + chartColors[index]"></span>
                                                            <!-- Label Text with Color -->
                                                            <span class="truncate font-medium transition-colors"
                                                                :style="'color: ' + chartColors[index]"
                                                                x-text="item.label"></span>
                                                        </div>
                                                        <div class="flex items-center gap-1 font-mono text-gray-700 dark:text-gray-300">
                                                            <span class="font-bold" x-text="((q.counts[item.val] || 0) / (q.total_responden || 1) * 100).toFixed(1) + '%'"></span>
                                                        </div>
                                                    </div>
                                                </template>
                                            </div>
                                        </div>

                                        <div class="text-center mt-4 pt-3 border-t border-gray-100 dark:border-gray-700 text-xs text-gray-400">
                                            <span x-text="q.total_responden + ' Responden'"></span>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            <script>
                document.addEventListener('alpine:init', () => {
                    Alpine.data('surveyCharts', (incomingData) => ({
                        data: incomingData,
                        // Hex colors to ensure consistency between Chart JS and HTML styles
                        chartColors: ['#ef4444', '#f97316', '#3b82f6', '#22c55e'], // Red, Orange, Blue, Green

                        init() {
                            let cx = 0;
                            const checkChart = setInterval(() => {
                                if (typeof Chart !== 'undefined') {
                                    clearInterval(checkChart);
                                    this.renderCharts();
                                }
                                cx++;
                                if (cx > 50) clearInterval(checkChart);
                            }, 100);
                        },
                        renderCharts() {
                            // Common Options for Question Pies
                            const pieOptions = {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: {
                                        display: false
                                    },
                                    tooltip: {
                                        callbacks: {
                                            label: function(context) {
                                                let label = context.label || '';
                                                let value = context.parsed || 0;
                                                let total = context.dataset.data.reduce((a, b) => a + b, 0);
                                                let percentage = total > 0 ? ((value / total) * 100).toFixed(1) + '%' : '0%';
                                                return `${label}: ${value} (${percentage})`;
                                            }
                                        }
                                    }
                                }
                            };

                            // 1. Total Chart
                            if (document.getElementById('totalChart')) {
                                new Chart(document.getElementById('totalChart'), {
                                    type: 'doughnut',
                                    data: this.data.total_chart,
                                    options: {
                                        responsive: true,
                                        maintainAspectRatio: false,
                                        plugins: {
                                            legend: {
                                                position: 'bottom'
                                            }
                                        }
                                    }
                                });
                            }

                            // 2. Category Chart
                            if (document.getElementById('categoryChart')) {
                                new Chart(document.getElementById('categoryChart'), {
                                    type: 'bar',
                                    data: this.data.category_chart,
                                    options: {
                                        indexAxis: 'y',
                                        responsive: true,
                                        maintainAspectRatio: false,
                                        scales: {
                                            x: {
                                                stacked: true,
                                                max: Math.max(...this.data.category_chart.datasets[0].data) * 5
                                            },
                                            y: {
                                                stacked: true
                                            }
                                        },
                                        plugins: {
                                            legend: {
                                                position: 'bottom'
                                            }
                                        }
                                    }
                                });
                            }

                            // 3. Question Charts
                            if (this.data.question_stats) {
                                Object.values(this.data.question_stats).forEach(questions => {
                                    questions.forEach(q => {
                                        const el = document.getElementById('qChart-' + q.id);
                                        if (el) {
                                            new Chart(el, {
                                                type: 'pie',
                                                data: {
                                                    labels: ['Tidak Memuaskan', 'Kurang Memuaskan', 'Memuaskan', 'Sangat Memuaskan'],
                                                    datasets: [{
                                                        data: [
                                                            q.counts[1] || 0,
                                                            q.counts[2] || 0,
                                                            q.counts[3] || 0,
                                                            q.counts[4] || 0
                                                        ],
                                                        backgroundColor: this.chartColors,
                                                        borderWidth: 1
                                                    }]
                                                },
                                                options: pieOptions
                                            });
                                        }
                                    });
                                });
                            }
                        }
                    }));
                });
            </script>
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