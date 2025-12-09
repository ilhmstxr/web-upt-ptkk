<x-filament-widgets::widget>
    <x-filament::card>
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-bold text-gray-800 dark:text-white flex items-center">
                <span class="bg-blue-600 text-white w-6 h-6 rounded-full flex items-center justify-center text-xs mr-2">1</span>
                Akumulasi Total (Indeks Kepuasan)
            </h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 items-center">
            <!-- Skor Utama -->
            <div class="text-center md:text-left border-b md:border-b-0 md:border-r border-gray-100 dark:border-gray-700 pb-6 md:pb-0">
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Indeks Kepuasan Rata-rata (IKM)</p>
                <div class="flex items-end justify-center md:justify-start gap-2">
                    <span class="text-5xl font-bold text-gray-900 dark:text-white">{{ $ikmScore }}</span>
                    <span class="text-lg text-gray-400 font-medium mb-1">/ 100</span>
                </div>
                <div class="mt-3 inline-block px-3 py-1 rounded-full text-sm font-bold
                    @if($ikmScore >= 88.31) bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300
                    @elseif($ikmScore >= 76.61) bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-300
                    @elseif($ikmScore >= 65) bg-yellow-100 text-yellow-700 dark:bg-yellow-900 dark:text-yellow-300
                    @else bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-300 @endif">
                    Kategori: {{ $ikmCategory }}
                </div>
            </div>

            <!-- Distribusi Jawaban (Doughnut Kecil) -->
            <div class="flex items-center gap-4 col-span-2">
                <div class="h-32 w-32 relative shrink-0"
                     x-data="{
                        labels: @js($chartData['labels']),
                        data: @js($chartData['counts']),
                        colors: @js($chartData['colors']),
                        init() {
                            new Chart(this.$refs.canvas, {
                                type: 'pie',
                                data: {
                                    labels: this.labels,
                                    datasets: [{
                                        data: this.data,
                                        backgroundColor: this.colors,
                                        borderWidth: 0,
                                        hoverOffset: 4
                                    }]
                                },
                                options: {
                                    responsive: true,
                                    maintainAspectRatio: false,
                                    // cutout removed for pie
                                    plugins: { legend: { display: false } }
                                }
                            });
                        }
                     }"
                >
                    <canvas x-ref="canvas"></canvas>
                </div>
                
                <div class="flex-1 grid grid-cols-2 gap-4">
                    @foreach($chartData['labels'] as $index => $label)
                        <div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">{{ $label }}</div>
                            <div class="font-bold text-lg text-gray-800 dark:text-white">{{ $chartData['percentages'][$index] }}%</div>
                            <div class="w-full bg-gray-100 dark:bg-gray-700 h-1.5 rounded-full mt-1">
                                <div class="h-1.5 rounded-full" style="width: {{ $chartData['percentages'][$index] }}%; background-color: {{ $chartData['colors'][$index] }}"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </x-filament::card>
</x-filament-widgets::widget>
