<x-filament-widgets::widget>
    <x-filament::card>
        {{-- Menggunakan grid layout yang sudah kita setujui --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            
            {{-- Menggunakan @forelse dari file referensi Anda untuk keamanan --}}
            @forelse ($this->charts as $c)
                <div class="flex flex-col p-4 border border-gray-200 dark:border-gray-700 rounded-lg">
                    {{-- Teks pertanyaan dengan gaya dari versi kita --}}
                    <h3 class="text-sm font-medium text-center text-gray-900 dark:text-gray-100 mb-4">
                        {{ $c['question_label'] }}
                    </h3>

                    {{-- Mengambil SELURUH LOGIKA x-data dari file referensi Anda --}}
                    <div x-data="{
                        labels: @js($c['labels']),
                        data: @js($c['data']),
                        // Urutan warna disesuaikan untuk skala Likert
                        colorsBg: [
                            '#EF4444', // 1. Tidak Memuaskan (Merah)
                            '#F59E0B', // 2. Kurang Memuaskan (Oranye)
                            '#3B82F6', // 3. Memuaskan (Biru)
                            '#10B981', // 4. Sangat Memuaskan (Hijau)
                            '#8B5CF6', // 5. Opsi kelima (Ungu)
                        ],
                        pct: [],
                        init() {
                            const total = (this.data ?? []).reduce((a, b) => a + (Number(b) || 0), 0);
                            this.pct = (this.data ?? []).map(v => {
                                const p = total ? (Number(v) / total * 100) : 0;
                                return Number.isFinite(p) ? p : 0;
                            });
                    
                            const createChart = () => {
                                new Chart(this.$refs.canvas, {
                                    type: 'pie',
                                    data: {
                                        labels: this.labels,
                                        datasets: [{
                                            data: this.data,
                                            backgroundColor: this.colorsBg,
                                        }],
                                    },
                                    options: {
                                        responsive: true,
                                        maintainAspectRatio: true,
                                        aspectRatio: 1,
                                        plugins: {
                                            legend: { display: false }, // Legenda Chart.js dimatikan
                                            tooltip: {
                                                callbacks: {
                                                    label: (item) => {
                                                        const idx = item.dataIndex;
                                                        const name = this.labels?.[idx] ?? '';
                                                        const val = this.data?.[idx] ?? 0;
                                                        const p = (this.pct?.[idx] ?? 0).toFixed(1);
                                                        return `${name}: ${val} (${p}%)`;
                                                    },
                                                },
                                            },
                                        },
                                    },
                                });
                            };
                    
                            // Metode aman untuk memuat Chart.js dari file referensi
                            if (window.Chart) {
                                createChart();
                            } else {
                                const s = document.createElement('script');
                                s.src = 'https://cdn.jsdelivr.net/npm/chart.js';
                                s.onload = () => createChart();
                                document.head.appendChild(s);
                            }
                        },
                        percentStr(i) {
                            const p = (this.pct?.[i] ?? 0);
                            // Menggunakan titik sebagai pemisah desimal
                            return p.toFixed(1) + '%';
                        }
                    }" class="flex gap-4 items-center w-full flex-grow">
                        
                        {{-- PERUBAHAN: Wadah Canvas, porsinya sedikit dikurangi menjadi 7/12 --}}
                        <div class="w-7/12">
                            <canvas x-ref="canvas"></canvas>
                        </div>

                        {{-- PERUBAHAN: Legenda HTML kustom, porsinya ditambah menjadi 5/12 --}}
                        <div class="w-5/12">
                            {{-- Menambahkan kelas warna teks untuk dark mode --}}
                            <ul class="text-xs space-y-2 text-white dark:text-white" role="list">
                                <template x-for="(label, i) in labels" :key="i">
                                    <li class="flex items-center justify-between">
                                        <div class="flex items-center">
                                            {{-- PERUBAHAN: Kotak warna dibuat kotak (rounded-sm) dan diberi jarak (mr-2) --}}
                                            <span class="inline-block h-3 w-3 mr-2 flex-shrink-0 rounded-sm" :style="`background:${colorsBg[i]}`"></span>
                                            <span x-text="label"></span>
                                        </div>
                                        <span class="font-medium" x-text="percentStr(i)"></span>
                                    </li>
                                </template>
                            </ul>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-sm text-gray-500 col-span-full">Data tidak tersedia.</div>
            @endforelse
        </div>
    </x-filament::card>
</x-filament-widgets::widget>

