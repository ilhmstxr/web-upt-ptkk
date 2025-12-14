<x-filament-widgets::widget>
    <x-filament::section>
        <div 
            class="flex flex-col" 
            x-data="{
                chart: null,
                
                init() {
                    // Watcher: Alpine menggunakan $wire untuk melacak perubahan data Livewire
                    this.$watch('$wire.male', () => this.createChart());
                    this.$watch('$wire.female', () => this.createChart());
                    this.$watch('$wire.empty', () => this.createChart());
                    
                    this.createChart();
                },
                
                createChart() {
                    // Cek apakah data sudah dimuat oleh Livewire
                    if (this.$wire.male === undefined) {
                        return;
                    }

                    if (typeof Chart === 'undefined') {
                        console.error('Chart.js library is not loaded.');
                        return;
                    }

                    if (this.chart) {
                        this.chart.destroy();
                    }

                    const ctx = this.$refs.canvas.getContext('2d');
                    
                    this.chart = new Chart(ctx, {
                        type: 'doughnut',
                        data: {
                            labels: ['Laki-laki', 'Perempuan', 'Kosong'],
                            datasets: [{
                                // Akses data di Alpine melalui $wire
                                data: [this.$wire.male, this.$wire.female, this.$wire.empty], 
                                backgroundColor: [
                                    '#3b82f6', // Blue
                                    '#ec4899', // Pink
                                    '#e5e7eb'  // Gray
                                ],
                                borderWidth: 0,
                                cutout: '75%',
                                hoverOffset: 4
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: { display: false },
                                tooltip: { enabled: true }
                            }
                        }
                    });
                }
            }"
            x-init="init()"
        >
            <div class="mb-6 flex justify-between items-start">
                <div>
                    <h3 class="font-bold text-gray-800 text-lg dark:text-white">Ketersediaan Asrama</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Real-time status okupansi kamar.</p>
                </div>
                <div class="bg-blue-50 text-blue-600 px-2 py-1 rounded text-xs font-medium max-w-[150px] truncate" title="{{ $currentTrainingName }}">
                    {{ $currentTrainingName }}
                </div>
            </div>

            <div class="relative h-48 mb-6">
                <canvas x-ref="canvas"></canvas>
                <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
                    <span class="text-4xl font-bold text-gray-800 dark:text-white">{{ $this->percent }}%</span>
                    <span class="text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-400">Terisi</span>
                </div>
            </div>

            <div class="space-y-3">
                <div class="flex items-center justify-between text-sm">
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full bg-blue-500"></span>
                        <span class="text-gray-600 dark:text-gray-300">Laki-laki</span>
                    </div>
                    <span class="font-bold text-gray-800 dark:text-white">{{ $this->male }} Org</span>
                </div>
                <div class="flex items-center justify-between text-sm">
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full bg-pink-500"></span>
                        <span class="text-gray-600 dark:text-gray-300">Perempuan</span>
                    </div>
                    <span class="font-bold text-gray-800 dark:text-white">{{ $this->female }} Org</span>
                </div>
                <div class="flex items-center justify-between text-sm">
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full bg-gray-200 dark:bg-gray-700"></span>
                        <span class="text-gray-600 dark:text-gray-300">Kosong</span>
                    </div>
                    <span class="font-bold text-green-600 dark:text-green-400">{{ $this->empty }} Bed</span>
                </div>
            </div>
        </div>
    </x-filament::section>
    
    @once
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @endonce
</x-filament-widgets::widget>