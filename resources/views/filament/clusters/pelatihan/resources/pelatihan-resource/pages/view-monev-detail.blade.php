<x-filament-panels::page>
    <div x-data="surveyCharts(@js($this->surveiData))" class="space-y-8">
        @if(empty($this->surveiData['total_chart']))
        <div class="text-center text-gray-500 py-12 bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700">
            <p>Belum ada data survei untuk kompetensi ini.</p>
        </div>
        @else
        <!-- SUMMARY CARDS & IKM -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- IKM Card -->
            <div class="bg-white dark:bg-gray-800 p-6 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm flex flex-col items-center justify-center text-center">
                <h3 class="text-gray-500 font-medium text-sm mb-2">Indeks Kepuasan (IKM)</h3>
                <div class="text-4xl font-bold text-primary-600 dark:text-primary-400 mb-2">{{ $this->surveiData['ikm'] }} <span class="text-lg text-gray-400 font-medium">/ 100</span></div>
                <span class="px-3 py-1 rounded-full text-xs font-bold bg-primary-50 text-primary-700 dark:bg-primary-900/20 dark:text-primary-300">
                    {{ $this->surveiData['ikm_category'] }}
                </span>
            </div>

            <!-- Responden Card -->
            <div class="bg-white dark:bg-gray-800 p-6 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm flex flex-col items-center justify-center text-center">
                <h3 class="text-gray-500 font-medium text-sm mb-2">Total Responden</h3>
                <div class="text-4xl font-bold text-gray-800 dark:text-gray-100 mb-2">{{ $this->surveiData['responden'] }}</div>
                <p class="text-xs text-gray-400">Peserta yang mengisi survei</p>
            </div>

            <div class="bg-white dark:bg-gray-900 p-6 rounded-xl shadow-sm ring-1 ring-gray-950/5 dark:ring-white/10">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Indeks Kepuasan (IKM)</p>
                        <h3 class="text-3xl font-bold text-primary-600 dark:text-primary-400 mt-1">{{ number_format($this->surveiData['avg'] ?? 0, 1) }}</h3>
                    </div>
                    <div class="p-2 bg-primary-50 dark:bg-primary-900/20 rounded-lg text-primary-600 dark:text-primary-400">
                        <x-heroicon-o-star class="w-6 h-6" />
                    </div>
                </div>
                <div class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400">
                    <span class="font-medium text-gray-900 dark:text-white">{{ $this->surveiData['responden'] ?? 0 }}</span>
                    <span>Responden</span>
                </div>
            </div>

            <!-- Chart Doughnut (Total) -->
            <div class="bg-white dark:bg-gray-900 p-6 rounded-xl shadow-sm ring-1 ring-gray-950/5 dark:ring-white/10 flex flex-col">
                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-4">Akumulasi Sebaran</h4>
                <div class="flex-1 relative min-h-[150px]">
                    <canvas id="totalChartMonev"></canvas>
                </div>
            </div>

            <!-- Chart Bar (Category) -->
            <div class="bg-white dark:bg-gray-900 p-6 rounded-xl shadow-sm ring-1 ring-gray-950/5 dark:ring-white/10 flex flex-col">
                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-4">Kepuasan per Aspek</h4>
                <div class="flex-1 relative min-h-[150px]">
                    <canvas id="categoryChartMonev"></canvas>
                </div>
            </div>
        </div>

        <!-- 2. DETAIL PERTANYAAN -->
        <div class="space-y-8">
            <template x-for="(group, kategori) in data.question_stats" :key="kategori">
                <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm ring-1 ring-gray-950/5 dark:ring-white/10 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-900/50 flex items-center gap-3">
                        <span class="w-1 h-6 bg-primary-500 rounded-full"></span>
                        <h3 class="font-bold text-gray-900 dark:text-white text-lg" x-text="kategori"></h3>
                    </div>

                    <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                        <template x-for="q in group" :key="q.id">
                            <div class="bg-white dark:bg-gray-900 p-5 rounded-lg shadow-sm border border-gray-100 dark:border-gray-700 flex flex-col">
                                <p class="text-sm font-medium text-gray-800 dark:text-white mb-4 min-h-[3rem]" x-text="q.teks"></p>

                                <div class="flex items-center gap-4 mt-auto">
                                    <div class="relative w-28 h-28 flex-shrink-0">
                                        <canvas :id="'qChartMonev-' + q.id"></canvas>
                                    </div>
                                    <!-- Legend -->
                                    <div class="flex-1 space-y-2">
                                        <template x-for="(item, index) in [
                                                    {label: 'Tidak Memuaskan', val: 1}, 
                                                    {label: 'Kurang Memuaskan', val: 2}, 
                                                    {label: 'Memuaskan', val: 3}, 
                                                    {label: 'Sangat Memuaskan', val: 4}
                                                ]">
                                            <div class="flex items-center justify-between text-xs">
                                                <div class="flex items-center gap-2">
                                                    <span class="w-2.5 h-2.5 rounded-sm" :style="'background-color: ' + chartColors[index]"></span>
                                                    <span class="font-medium truncate max-w-[100px]" :style="'color: ' + chartColors[index]" x-text="item.label"></span>
                                                </div>
                                                <span class="font-bold text-gray-700 dark:text-gray-300" x-text="((q.counts[item.val] || 0) / (q.total_responden || 1) * 100).toFixed(1) + '%'"></span>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                                <div class="mt-4 pt-3 border-t border-gray-100 dark:border-gray-700 text-center text-xs text-gray-400">
                                    <span x-text="q.total_responden + ' Responden'"></span>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </template>
        </div>
        @endif
        <!-- Script Alpine & Chart.js -->
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            document.addEventListener('alpine:init', () => {
                Alpine.data('surveyCharts', (incomingData) => ({
                    data: incomingData,
                    chartColors: ['#ef4444', '#f97316', '#3b82f6', '#22c55e'],
                    init() {
                        if (!this.data || Object.keys(this.data).length === 0) return;
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
                        if (document.getElementById('totalChartMonev') && this.data.total_chart) {
                            new Chart(document.getElementById('totalChartMonev'), {
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
                        if (document.getElementById('categoryChartMonev') && this.data.category_chart) {
                            new Chart(document.getElementById('categoryChartMonev'), {
                                type: 'bar',
                                data: this.data.category_chart,
                                options: {
                                    indexAxis: 'y',
                                    responsive: true,
                                    maintainAspectRatio: false,
                                    scales: {
                                        x: {
                                            stacked: true
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

                        // 3. Questions
                        if (this.data.question_stats) {
                            const groups = Array.isArray(this.data.question_stats) ?
                                this.data.question_stats :
                                Object.values(this.data.question_stats);

                            Object.entries(this.data.question_stats).forEach(([k, group]) => {
                                group.forEach(q => {
                                    const el = document.getElementById('qChartMonev-' + q.id);
                                    if (el) {
                                        if (Chart.getChart(el)) Chart.getChart(el).destroy();
                                        new Chart(el, {
                                            type: 'pie',
                                            data: {
                                                labels: ['Tidak Memuaskan', 'Kurang Memuaskan', 'Memuaskan', 'Sangat Memuaskan'],
                                                datasets: [{
                                                    data: [q.counts[1] || 0, q.counts[2] || 0, q.counts[3] || 0, q.counts[4] || 0],
                                                    backgroundColor: this.chartColors,
                                                    borderWidth: 1,
                                                    borderColor: '#ffffff'
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
    </div>
</x-filament-panels::page>