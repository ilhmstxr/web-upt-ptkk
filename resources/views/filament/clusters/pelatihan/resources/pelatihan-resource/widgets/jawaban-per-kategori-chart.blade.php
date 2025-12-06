<x-filament-widgets::widget>
    <x-filament::card>
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-bold text-gray-800 dark:text-white flex items-center">
                <span class="bg-blue-600 text-white w-6 h-6 rounded-full flex items-center justify-center text-xs mr-2">2</span>
                Performa Per Kategori
            </h2>
        </div>

        <div class="h-72" x-data="{
            labels: @js($chartData['labels'] ?? []),
            data: @js($chartData['data'] ?? []),
            colors: @js($chartData['colors'] ?? []),
            init() {
                if (this.labels.length === 0) return;
                
                new Chart(this.$refs.canvas, {
                    type: 'bar',
                    data: {
                        labels: this.labels,
                        datasets: [{
                            label: 'Nilai Rata-rata (Maks 4.0)',
                            data: this.data,
                            backgroundColor: this.colors,
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
            }
        }">
            <canvas x-ref="canvas"></canvas>
        </div>
        <p class="text-xs text-gray-500 dark:text-gray-400 mt-4 text-center italic">*Grafik menunjukkan nilai rata-rata (Skala 1-4) untuk setiap grup kategori pertanyaan.</p>
    </x-filament::card>
</x-filament-widgets::widget>
