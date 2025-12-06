<x-filament-panels::page>
    @if(empty($surveyData))
        <div class="p-6 bg-white rounded-xl shadow-sm border border-gray-200 text-center">
            <p class="text-gray-500">Belum ada data survey untuk kompetensi ini.</p>
        </div>
    @else
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

        <div class="space-y-8">
            <!-- LEVEL 1: AKUMULASI TOTAL -->
            <section>
                <h2 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                    <span class="bg-blue-600 text-white w-6 h-6 rounded-full flex items-center justify-center text-xs mr-2">1</span>
                    Akumulasi Total (Indeks Kepuasan)
                </h2>
                
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 grid grid-cols-1 md:grid-cols-3 gap-8 items-center">
                    <!-- Skor Utama -->
                    <div class="text-center md:text-left border-b md:border-b-0 md:border-r border-gray-100 pb-6 md:pb-0">
                        <p class="text-sm text-gray-500 mb-1">Indeks Kepuasan Rata-rata (IKM)</p>
                        <div class="flex items-end justify-center md:justify-start gap-2">
                            <span class="text-5xl font-bold text-gray-900">{{ $surveyData['ikm'] }}</span>
                            <span class="text-lg text-gray-400 font-medium mb-1">/ 100</span>
                        </div>
                        <div class="mt-3 inline-block bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm font-bold">
                            Kategori: {{ $surveyData['ikm_category'] }}
                        </div>
                    </div>

                    <!-- Distribusi Jawaban -->
                    <div class="flex items-center gap-4 col-span-2">
                        <div class="h-32 w-32 relative shrink-0">
                            <canvas id="chartTotalAccumulation"></canvas>
                        </div>
                        <div class="flex-1 grid grid-cols-2 gap-4">
                            @php
                                $labels = ['Sangat Memuaskan', 'Memuaskan', 'Kurang Memuaskan', 'Tidak Memuaskan'];
                                $colors = ['bg-green-500', 'bg-blue-500', 'bg-yellow-500', 'bg-red-500'];
                                $totalResp = array_sum($surveyData['total_distribution']);
                            @endphp
                            @foreach($surveyData['total_distribution'] as $index => $val)
                                <div>
                                    <div class="text-xs text-gray-500">{{ $labels[$index] }}</div>
                                    <div class="font-bold text-lg text-gray-800">{{ $totalResp > 0 ? round(($val / $totalResp) * 100) : 0 }}%</div>
                                    <div class="w-full bg-gray-100 h-1.5 rounded-full mt-1">
                                        <div class="{{ $colors[$index] }} h-1.5 rounded-full" style="width: {{ $totalResp > 0 ? ($val / $totalResp) * 100 : 0 }}%"></div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </section>

            <!-- LEVEL 2: ANALISIS PER KATEGORI -->
            <section>
                <h2 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                    <span class="bg-blue-600 text-white w-6 h-6 rounded-full flex items-center justify-center text-xs mr-2">2</span>
                    Performa Per Kategori
                </h2>
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                    <div class="h-72">
                        <canvas id="chartCategories"></canvas>
                    </div>
                    <p class="text-xs text-gray-500 mt-4 text-center italic">*Grafik menunjukkan nilai rata-rata (Skala 1-4) untuk setiap grup kategori pertanyaan.</p>
                </div>
            </section>

            <!-- LEVEL 3: DETAIL PER PERTANYAAN -->
            <section class="space-y-8">
                <h2 class="text-lg font-bold text-gray-800 flex items-center">
                    <span class="bg-blue-600 text-white w-6 h-6 rounded-full flex items-center justify-center text-xs mr-2">3</span>
                    Detail Jawaban Per Pertanyaan
                </h2>

                @foreach($surveyData['question_stats'] as $category => $questions)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-8">
                        <div class="bg-gray-50 px-6 py-3 border-b border-gray-200">
                            <h3 class="font-bold text-gray-700 text-sm uppercase tracking-wider">{{ $loop->iteration }}. {{ $category }}</h3>
                        </div>
                        <div class="p-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                            @foreach($questions as $q)
                                <div class="flex flex-col items-center">
                                    <h4 class="text-sm font-semibold text-gray-800 mb-3 text-center h-12 flex items-center justify-center w-full px-2 leading-tight">
                                        <span class="mr-1 text-primary font-bold">{{ $q['nomor'] }}.</span> {{ $q['teks'] }}
                                    </h4>
                                    <div class="h-48 w-full relative">
                                        <canvas id="chart-q-{{ $q['id'] }}"></canvas>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </section>
        </div>

        <script>
            document.addEventListener('livewire:initialized', () => {
                const pieLabels = ['Sangat Memuaskan', 'Memuaskan', 'Kurang Memuaskan', 'Tidak Memuaskan'];
                const pieColors = ['#10B981', '#3B82F6', '#F59E0B', '#EF4444'];

                // 1. Total Chart
                new Chart(document.getElementById('chartTotalAccumulation').getContext('2d'), {
                    type: 'doughnut',
                    data: {
                        labels: pieLabels,
                        datasets: [{
                            data: @json($surveyData['total_distribution']),
                            backgroundColor: pieColors,
                            borderWidth: 0,
                            hoverOffset: 4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '70%',
                        plugins: { legend: { display: false } }
                    }
                });

                // 2. Category Chart
                const catLabels = @json(array_keys($surveyData['category_stats']));
                const catData = @json(array_values($surveyData['category_stats']));
                
                new Chart(document.getElementById('chartCategories').getContext('2d'), {
                    type: 'bar',
                    data: {
                        labels: catLabels,
                        datasets: [{
                            label: 'Nilai Rata-rata (Maks 4.0)',
                            data: catData,
                            backgroundColor: ['#60A5FA', '#FBBF24', '#A78BFA', '#34D399', '#F472B6'],
                            borderRadius: 6,
                            barThickness: 40
                        }]
                    },
                    options: {
                        indexAxis: 'y',
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            x: {
                                beginAtZero: true,
                                max: 4.0,
                                grid: { color: '#f3f4f6' }
                            },
                            y: {
                                grid: { display: false }
                            }
                        },
                        plugins: {
                            legend: { display: false }
                        }
                    }
                });

                // 3. Question Charts
                const questions = @json($surveyData['question_stats']->flatten(1));
                
                questions.forEach(q => {
                    const ctx = document.getElementById(`chart-q-${q.id}`);
                    if (ctx) {
                        new Chart(ctx.getContext('2d'), {
                            type: 'pie',
                            data: {
                                labels: pieLabels,
                                datasets: [{
                                    data: q.distribution,
                                    backgroundColor: pieColors,
                                    borderWidth: 2,
                                    borderColor: '#ffffff'
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: {
                                        position: 'bottom',
                                        labels: { boxWidth: 10, font: { size: 10 }, padding: 10 }
                                    }
                                }
                            }
                        });
                    }
                });
            });
        </script>
    @endif
</x-filament-panels::page>
