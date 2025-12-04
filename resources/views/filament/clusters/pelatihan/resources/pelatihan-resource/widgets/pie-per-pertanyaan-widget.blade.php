<x-filament-widgets::widget>
    <div class="space-y-8">
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-bold text-gray-800 dark:text-white flex items-center">
                <span class="bg-blue-600 text-white w-6 h-6 rounded-full flex items-center justify-center text-xs mr-2">3</span>
                Detail Jawaban Per Pertanyaan
            </h2>
        </div>

        @foreach ($chartsByCategory as $category => $charts)
            <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden mb-8">
                <div class="bg-gray-50 dark:bg-gray-800 px-6 py-3 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="font-bold text-gray-700 dark:text-gray-200 text-sm uppercase tracking-wider">
                        {{ $loop->iteration }}. {{ $category }}
                    </h3>
                </div>
                
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach ($charts as $c)
                        <div class="flex flex-col items-center">
                            <h4 class="text-sm font-semibold text-gray-800 dark:text-white mb-3 text-center h-12 flex items-center justify-center w-full px-2 leading-tight">
                                {{ $c['question_label'] }}
                            </h4>
                            <div class="h-48 w-full relative"
                                 x-data="{
                                    labels: @js($c['labels']),
                                    data: @js($c['data']),
                                    colors: @js($c['colors']),
                                    init() {
                                        new Chart(this.$refs.canvas, {
                                            type: 'pie',
                                            data: {
                                                labels: this.labels,
                                                datasets: [{
                                                    data: this.data,
                                                    backgroundColor: this.colors,
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
                                                    },
                                                    tooltip: {
                                                        callbacks: {
                                                            label: function(context) {
                                                                return context.label + ': ' + context.raw + ' (' + context.parsed + '%)';
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                        });
                                    }
                                 }"
                            >
                                <canvas x-ref="canvas"></canvas>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
</x-filament-widgets::widget>
